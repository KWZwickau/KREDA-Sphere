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
    protected function sumPriceItemStringByBalance( TblBalance $tblBalance)
    {
        return str_replace('.', ',', round($this->sumPriceItemByBalance( $tblBalance ), 2)) . " €";
    }

    /**
     * @param TblBalance $tblBalance
     * @return float
     */
    protected function sumPriceItemByBalance( TblBalance $tblBalance)
    {
        $sum = 0.00;
        $tblPaymentList = $this->entityPaymentByBalance( $tblBalance);
        foreach($tblPaymentList as $tblPayment)
        {
            $sum += $tblPayment->getValue();
        }

        return $sum;
    }

    /**
     *
     * @return bool|\KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblInvoice[]
     */
    protected function entityInvoiceHasFullPaymentAll()
    {
        $invoiceHasFullPaymentAll = array();
        $balanceAll = $this->entityBalanceAll();
        if ($balanceAll)
        {
            foreach ($balanceAll as $balance)
            {
                $invoice =$balance->getServiceBillingInvoice();
                $sumInvoicePrice = Billing::serviceInvoice()->sumPriceItemAllByInvoice( $invoice );
                $sumPaymentPrice = $this->sumPriceItemByBalance( $balance );

                $sumInvoicePrice = round($sumInvoicePrice,2);
                $sumPaymentPrice = round($sumPaymentPrice,2);

                if( $sumInvoicePrice <= $sumPaymentPrice )
                {
                    $invoiceHasFullPaymentAll[] = $invoice;
                }
            }
        }

        return ( empty( $invoiceHasFullPaymentAll ) ? false : $invoiceHasFullPaymentAll );
    }

    /**
     * @return bool|TblInvoice[]
     */
    protected  function entityInvoiceHasExportDateAll()
    {
        $invoiceHasExportDateAll = array();
        $balanceAll = $this->entityBalanceAll();
        if ($balanceAll)
        {
            foreach ($balanceAll as $balance)
            {
                $invoice =$balance->getServiceBillingInvoice();
                $BalanceDate = $balance->getExportDate();

                if( $BalanceDate !== false )
                {
                    $invoiceHasExportDateAll[] = $invoice;
                }
            }
        }

        return ( empty( $invoiceHasExportDateAll ) ? false : $invoiceHasExportDateAll );
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
        /** @var TblBalance[] $balanceAllByDebtor */
        $balanceAllByDebtor = $this->getEntityManager() -> getEntity('TblBalance')->findBy(
            array(TblBalance::ATTR_SERVICE_BILLING_BANKING => $tblDebtor->getId()));
        foreach ( $balanceAllByDebtor as $balance )
        {
            $Entity = $this->getEntityManager()->getEntity( 'TblPayment' )->findOneBy(array(TblPayment::ATTR_TBL_BALANCE => $balance->getId()));
            if ($Entity !== null)
            {
                return true;
            }
        }

        return false;
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
    protected function actionSetExportDateBalance(
        TblBalance $tblBalance
    )
    {
        $Manager = $this->getEntityManager();

        /** @var TblBalance $Entity */
        $Entity = $Manager->getEntityById( 'TblInvoice', $tblBalance->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setExportDate( new \DateTime('now') );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
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
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblBalance $tblBalance
     * @param $Value
     * @param \DateTime $Date
     *
     * @return TblPayment|null|object
     */
    protected function actionCreatePayment( TblBalance $tblBalance, $Value,\DateTime $Date)
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblPayment' )->findOneBy( array(
            'tblBalance' => $tblBalance->getId(),
            'Value'      => $Value,
            'Date'      => $Date ));

        if (null === $Entity)
        {
            $Entity = new TblPayment();
            $Entity->setTblBalance( $tblBalance );
            $Entity->setValue( $Value );
            $Entity->setDate(  $Date );

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
