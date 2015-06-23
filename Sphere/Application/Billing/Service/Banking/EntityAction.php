<?php
namespace KREDA\Sphere\Application\Billing\Service\Banking;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtorCommodity;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblPaymentType;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblReference;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Billing\Service\Account
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param $Id
     *
     * @return bool|TblDebtor
     */
    protected function entityDebtorById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblDebtor', $Id );
        return (null === $Entity ? false : $Entity);
    }

    /**
     * @param $DebtorNumber
     *
     * @return TblDebtor|bool
     */
    protected function entityDebtorByDebtorNumber( $DebtorNumber )
    {
        $Entity = $this->getEntityManager()->getEntity('TblDebtor')->findOneBy( array(TblDebtor::ATTR_DEBTOR_NUMBER => $DebtorNumber) );
        return (null === $Entity ? false : $Entity);
    }

    /**
     * @param $ServiceManagement_Person
     *
     * @return TblDebtor[]|bool
     */
    protected function entityDebtorByServiceManagementPerson( $ServiceManagement_Person )
    {
        $Entity = $this->getEntityManager()->getEntity('TblDebtor')->findBy( array(TblDebtor::ATTR_SERVICE_MANAGEMENT_PERSON => $ServiceManagement_Person) );
        return (null === $Entity ? false : $Entity);
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return TblDebtor[]|bool
     */
    protected function entityDebtorAllByPerson( TblPerson $tblPerson )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblDebtor' )
            ->findBy( array( TblDebtor::ATTR_SERVICE_MANAGEMENT_PERSON => $tblPerson->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return bool|TblDebtorCommodity[]
     */
    protected function entityCommodityDebtorAllByDebtor( TblDebtor $tblDebtor )
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblDebtorCommodity' )
            ->findBy( array( TblDebtorCommodity::ATTR_TBL_DEBTOR => $tblDebtor->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }

    /**
     * @param $Id
     *
     * @return bool|\Doctrine\ORM\Mapping\Entity
     */
    protected function entityDebtorCommodityById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblDebtorCommodity', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblDebtorCommodity $tblDebtorCommodity
     *
     * @return bool
     */
    protected function actionRemoveDebtorCommodity(
        TblDebtorCommodity $tblDebtorCommodity
    ) {

        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblDebtorCommodity' )->findOneBy(
            array(
                'Id' => $tblDebtorCommodity->getId()
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
     * @param TblDebtor $tblDebtor
     * @param TblCommodity $tblCommodity
     *
     * @return TblDebtorCommodity
     */
    protected function actionAddDebtorCommodity(
        TblDebtor $tblDebtor,
        TblCommodity $tblCommodity
    ) {
        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblDebtorCommodity' )->findOneBy(
            array(
                TblDebtorCommodity::ATTR_TBL_DEBTOR => $tblDebtor->getId(),
                TblDebtorCommodity::ATTR_SERVICE_BILLING_COMMODITY => $tblCommodity->getId()
            ));
        if (null === $Entity)
        {
            $Entity = new TblDebtorCommodity();
            $Entity->setTblDebtor( $tblDebtor );
            $Entity->setServiceBillingCommodity( $tblCommodity );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return bool
     */
    protected function actionRemoveBanking(
        TblDebtor $tblDebtor
    )
    {
        $Manager = $this->getEntityManager();

        $EntityReferenceList = $Manager->getEntity( 'TblReference' )
            ->findBy( array(TblReference::ATTR_TBL_DEBTOR => $tblDebtor->getId() ) );
        if (null !== $EntityReferenceList)
        {
            foreach($EntityReferenceList as $EntityReference)
            {
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $EntityReference );
                $Manager->killEntity( $EntityReference );
            }
        }

        $EntityItemsDebtorCommodity = $Manager->getEntity( 'TblDebtorCommodity' )
            ->findBy( array(TblDebtorCommodity::ATTR_TBL_DEBTOR => $tblDebtor->getId() ) );
        if (null !== $EntityItemsDebtorCommodity)
        {
            foreach($EntityItemsDebtorCommodity as $Entity)
            {
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
                $Manager->killEntity( $Entity );
            }
        }

        $EntityItems = $Manager->getEntity( 'TblDebtor' )
            ->findBy( array(TblDebtor::ATTR_DEBTOR_NUMBER => $tblDebtor->getId() ) );
        if (null !== $EntityItems)
        {
            foreach($EntityItems as $Entity)
            {
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
                $Manager->killEntity( $Entity );
            }
        }

        $Entity = $Manager->getEntity( 'TblDebtor' )->findOneBy( array( 'Id' => $tblDebtor->getId() ) );
        if (null !== $Entity)
        {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return bool
     */
    protected function actionRemoveReference( TblDebtor $tblDebtor )
    {
        $Manager = $this->getEntityManager();
        $EntityList = $Manager->getEntity( 'TblReference' )->findBy( array( TblReference::ATTR_TBL_DEBTOR => $tblDebtor->getId() ) );

        if (null !== $EntityList)
        {
            foreach ($EntityList as $Entity)
            {
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
                $Manager->killEntity( $Entity );
            }
            return true;
        }
        return false;
    }

    /**
     * @param TblReference $tblReference
     *
     * @return bool
     */
    protected function actionDeactivateReference( TblReference $tblReference )
    {
        $Manager = $this->getEntityManager();

        /** @var TblReference $Entity */
        $Entity = $Manager->getEntityById( 'TblReference', $tblReference->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setIsVoid( true );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblDebtor $tblDebtor
     * @param TblCommodity $tblCommodity
     *
     * @return TblDebtorCommodity[]|bool
     */
    protected function entityDebtorCommodityAllByDebtorAndCommodity( TblDebtor $tblDebtor, TblCommodity $tblCommodity )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblDebtorCommodity' )
            ->findBy( array( TblDebtorCommodity::ATTR_TBL_DEBTOR => $tblDebtor->getId(), TblDebtorCommodity::ATTR_SERVICE_BILLING_COMMODITY => $tblCommodity->getId() ) );
        return ( null === $EntityList ? false : $EntityList );
    }


    /**
     * @param $LeadTimeFollow
     * @param $LeadTimeFirst
     * @param $DebtorNumber
     * @param $BankName
     * @param $Owner
     * @param $CashSign
     * @param $IBAN
     * @param $BIC
     * @param $Description
     * @param $PaymentType
     * @param $ServiceManagement_Person
     *
     * @return TblDebtor
     */
    protected function actionAddDebtor($DebtorNumber, $LeadTimeFirst, $LeadTimeFollow, $BankName, $Owner, $CashSign, $IBAN, $BIC, $Description, $PaymentType, $ServiceManagement_Person )
    {

        $Manager = $this->getEntityManager();

        $Entity = new TblDebtor();
        $Entity->setLeadTimeFirst( $LeadTimeFirst );
        $Entity->setLeadTimeFollow( $LeadTimeFollow );
        $Entity->setDebtorNumber( $DebtorNumber );
        $Entity->setBankName( $BankName );
        $Entity->setOwner( $Owner );
        $Entity->setCashSign( $CashSign );
        $Entity->setIBAN( $IBAN );
        $Entity->setBIC( $BIC );
        $Entity->setDescription( $Description );
        $Entity->setPaymentType ( Billing::serviceBanking()->entityPaymentTypeById( $PaymentType ) );
        $Entity->setServiceManagementPerson( $ServiceManagement_Person );

        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @return array|bool|TblDebtor[]
     */
    protected function entityDebtorAll()
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblDebtor' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $Reference
     * @param $DebtorNumber
     * @param $ReferenceDate
     * @param $tblCommodity
     * @return TblReference
     */
    protected function actionAddReference( $Reference, $DebtorNumber, $ReferenceDate, TblCommodity $tblCommodity  )
    {
        $Manager = $this->getEntityManager();

        $Entity = new TblReference();
        $Entity->setReference( $Reference );
        $Entity->setIsVoid( false );
        $Entity->setServiceTblDebtor( Billing::serviceBanking()->entityDebtorByDebtorNumber( $DebtorNumber ) );
        $Entity->setServiceBillingCommodity( $tblCommodity );
        if( $ReferenceDate )
        {
            $Entity->setReferenceDate( new \DateTime( $ReferenceDate ) );
        }
        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    protected function actionEditDebtor(
        TblDebtor $tblDebtor,
        $Description,
        $PaymentType,
        $Owner,
        $IBAN,
        $BIC,
        $CashSign,
        $BankName,
        $LeadTimeFirst,
        $LeadTimeFollow
    )
    {
        $Manager = $this->getEntityManager();

        /** @var TblDebtor $Entity */
        $Entity = $Manager->getEntityById( 'TblDebtor', $tblDebtor->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setDescription( $Description );
            $Entity->setPaymentType( Billing::serviceBanking()->entityPaymentTypeById( $PaymentType ) );
            $Entity->setOwner( $Owner );
            $Entity->setIBAN( $IBAN );
            $Entity->setBIC( $BIC );
            $Entity->setCashSign( $CashSign );
            $Entity->setBankName( $BankName );
            $Entity->setLeadTimeFirst( $LeadTimeFirst );
            $Entity->setLeadTimeFollow( $LeadTimeFollow );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return bool|TblReference[]
     */
    protected function entityReferenceByDebtor( TblDebtor $tblDebtor )
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblReference' )
            ->findBy( array( TblReference::ATTR_TBL_DEBTOR => $tblDebtor->getId(), TblReference::ATTR_IS_VOID => false ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $tblReference
     *
     * @return bool|TblReference
     */
    protected function entityReferenceById ( $tblReference )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblReference', $tblReference );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblDebtor $tblDebtor
     * @param TblCommodity $tblCommodity
     *
     * @return bool|TblReference
     */
    protected function entityReferenceByDebtorAndCommodity (TblDebtor $tblDebtor, TblCommodity $tblCommodity )
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblReference')->findOneBy(array(
            TblReference::ATTR_TBL_DEBTOR => $tblDebtor->getId(),
            TblReference::ATTR_SERVICE_BILLING_COMMODITY => $tblCommodity->getId(),
            TblReference::ATTR_IS_VOID => false
        ));
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $Reference
     *
     * @return bool|TblReference
     */
    protected function entityReferenceByReference ( $Reference )
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblReference' )
        ->findOneBy( array( TblReference::ATTR_REFERENCE => $Reference) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $PaymentType
     * @return TblPaymentType|null|object
     */
    protected function actionCreatePaymentType( $PaymentType )
    {
        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblPaymentType' )->findOneBy( array( TblPaymentType::ATTR_NAME => $PaymentType ) );
        if (null === $Entity)
        {
            $Entity = new TblPaymentType();
            $Entity->setName( $PaymentType );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        }

        return $Entity;
    }

    /**
     * @return bool|TblPaymentType[]
     */
    protected function entityPaymentTypeAll()
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblPaymentType' )->findAll();

        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $PaymentType
     *
     * @return bool|null|$tblPaymentType
     */
    protected function entityPaymentTypeByName($PaymentType)
    {
        $Entity = $this->getEntityManager()->getEntity('TblPaymentType')->findOneBy( array( TblPaymentType::ATTR_NAME => $PaymentType) );
        return (null === $Entity ? false : $Entity);
    }

    /**
     * @param $Id
     *
     * @return bool|TblPaymentType
     */
    protected function entityPaymentTypeById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblPaymentType', $Id );
        return (null === $Entity ? false : $Entity);
    }

}
