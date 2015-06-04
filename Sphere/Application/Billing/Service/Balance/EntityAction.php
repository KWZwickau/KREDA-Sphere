<?php
namespace KREDA\Sphere\Application\Billing\Service\Balance;

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
     * @param TblDebtor $serviceBilling_Banking
     * @param TblInvoice $serviceBilling_Invoice
     * @param $ExportDate
     *
     * @return TblBalance|null|object
     */
    protected function actionCreateBalance( TblDebtor $serviceBilling_Banking, TblInvoice $serviceBilling_Invoice, $ExportDate)
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountKey' )->findOneBy( array(
            'serviceBilling_Banking' => $serviceBilling_Banking->getId(),
            'serviceBilling_Invoice' => $serviceBilling_Invoice->getId(),
            'ExportDate' => $ExportDate ));

        if (null === $Entity)
        {
            $Entity = new TblBalance();
            $Entity->setServiceBillingBanking( $serviceBilling_Banking );
            $Entity->setServiceBillingInvoice( $serviceBilling_Invoice );
            $Entity->setExportDate( $ExportDate );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        }
        return $Entity;
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
