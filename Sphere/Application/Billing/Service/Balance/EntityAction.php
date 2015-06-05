<?php
namespace KREDA\Sphere\Application\Billing\Service\Balance;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Balance\Entity\TblBalance;
use KREDA\Sphere\Application\Billing\Service\Balance\Entity\TblPayment;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Billing\Service\Balance
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblBalance
     */
    protected function entityBalanceById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblBalance', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblInvoice $tblInvoice
     *
     * @return bool|TblBalance
     */
    protected function entityBalanceByInvoice(TblInvoice $tblInvoice )
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblBalance')->findOneBy(array(TblBalance::ATTR_SERVICE_BILLING_INVOICE => $tblInvoice->getId()));
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblBalance $tblBalance
     * @return string
     */
    protected function sumPriceItemByBalance( TblBalance $tblBalance)
    {
        $sum = 0.00;
        $tblPaymentList = $this->entityPaymentByBalance( $tblBalance);
        foreach($tblPaymentList as $tblPayment)
        {
            $sum += $tblPayment->getValue();
        }

        return str_replace('.', ',', round($sum, 2)) . " €";
    }

    /**
     * @param TblInvoice $tblInvoice
     * @param TblBalance $tblBalance
     *
     * @return bool|\KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoiceItem[]
     */
    protected function entityInvoiceLikePayment( TblInvoice $tblInvoice, TblBalance $tblBalance )
    {
        $EntityListInvoice = Billing::serviceInvoice()->entityInvoiceItemAllByInvoice( $tblInvoice );
        $sumInvoice = 0.00;
        if(!empty($EntityListInvoice))
        {
            foreach ($EntityListInvoice as $EntityInvoice)
            {
                $sumInvoice +=  $EntityInvoice->getItemPrice() * $EntityInvoice->getItemQuantity();
            }
        }
        $sumInvoice = round($sumInvoice,2);
        $EntityListPayment = $this->getEntityManager()->getEntity( 'TblPayment' )
            ->findBy(array(TblPayment::ATTR_TBL_BALANCE => $tblBalance->getId()));
        $sumPayment = 0.00;
        if(!empty($EntityListPayment))
        {
            foreach ($EntityListPayment as $EntityPayment)
            {
                $sumPayment += $EntityPayment->getValue();
            }
        }
        $sumPayment = round($sumPayment,2);

        if( $sumInvoice == $sumPayment )
        {
            return ( null === $EntityListPayment ? false : $EntityListPayment );
        }
        else
        {
            return false;
        }


    }

    /**
     * @param TblBalance $tblBalance
     *
     * @return bool|TblPayment[]
     */
    protected function entityPaymentByBalance (TblBalance $tblBalance)
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblPayment')->findBy(array(TblPayment::ATTR_TBL_BALANCE => $tblBalance->getId()));
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblBalance[]
     */
    protected function entityBalanceAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblBalance' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblPayment
     */
    protected function entityPaymentById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblPayment', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblPayment[]
     */
    protected function entityPaymentAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblPayment' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblDebtor $tblDebtor
     * @return bool
     */
    protected function checkPaymentFromDebtorExistsByDebtor( TblDebtor $tblDebtor )
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblPayment' )->findOneBy(array(TblPayment::ATTR_TBL_BANKING => $tblDebtor->getId()));
        return ( null === $Entity ? false : true );
    }


    /**
     * @param TblDebtor $serviceBilling_Banking
     * @param TblInvoice $serviceBilling_Invoice
     * @param $ExportDate
     *
     * @return bool
     */
    protected function actionCreateBalance( TblDebtor $serviceBilling_Banking, TblInvoice $serviceBilling_Invoice, $ExportDate)
    {
        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblBalance' )->findOneBy( array(
            TblBalance::ATTR_SERVICE_BILLING_BANKING => $serviceBilling_Banking->getId(),
            TblBalance::ATTR_SERVICE_BILLING_INVOICE => $serviceBilling_Invoice->getId()
        ));
        if (null === $Entity)
        {
            $Entity = new TblBalance();
            $Entity->setServiceBillingBanking( $serviceBilling_Banking );
            $Entity->setServiceBillingInvoice( $serviceBilling_Invoice );
            if ($ExportDate !== null)
            {
                $Entity->setExportDate( $ExportDate );
            }
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

            return true;
        }

        return false;
    }

    /**
     * @param TblBalance $tblBalance
     *
     * @return bool
     */
    protected function actionRemoveBalance(
        TblBalance $tblBalance
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblBalance' )->findOneBy(
            array(
                'Id' => $tblBalance->getId()
            ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblBalance $tblBalance
     * @param $Value
     * @param $Date
     *
     * @return TblPayment|null|object
     */
    protected function actionCreatePayment( TblBalance $tblBalance, $Value, $Date)
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblPayment' )->findOneBy( array(
            'tblBalance' => $tblBalance->getId(),
            'Value'      => $Value,
            '$Date'      => $Date ));

        if (null === $Entity)
        {
            $Entity = new TblPayment();
            $Entity->setTblBalance( $tblBalance );
            $Entity->setValue( $Value );
            $Entity->setDate( $Date );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblPayment $tblPayment
     *
     * @return bool
     */
    protected function actionRemovePayment(
        TblPayment $tblPayment
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblPayment' )->findOneBy(
            array(
                'Id' => $tblPayment->getId()
            ) );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

}
