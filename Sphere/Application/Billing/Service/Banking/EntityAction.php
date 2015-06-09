<?php
namespace KREDA\Sphere\Application\Billing\Service\Banking;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtorCommodity;
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
    protected function entityDebtorByServiceManagement_Person( $ServiceManagement_Person )
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
     * @param TblDebtor $tblDebtor
     *
     * @return bool
     */
    protected function actionDeactivateReference( TblDebtor $tblDebtor )
    {
        $Manager = $this->getEntityManager();
        $Reference = Billing::serviceBanking()->entityReferenceByDebtor( $tblDebtor );
        /** @var TblReference $Entity */
        $Entity = $Manager->getEntityById( 'TblReference', $Reference->getId() );
        print_r( $Entity );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setIsVoid( false );

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
     * @param $IBAN
     * @param $SWIFT
     * @param $Description
     * @param $ServiceManagement_Person
     *
     * @return TblDebtor
     */
    protected function actionAddDebtor($DebtorNumber, $LeadTimeFirst, $LeadTimeFollow, $IBAN, $BIC, $Owner, $Description, $ServiceManagement_Person )
    {

        $Manager = $this->getEntityManager();

        $Entity = new TblDebtor();
        $Entity->setLeadTimeFirst( $LeadTimeFirst );
        $Entity->setLeadTimeFollow( $LeadTimeFollow );
        $Entity->setDebtorNumber( $DebtorNumber );
        $Entity->setIBAN( $IBAN );
        $Entity->setBIC( $BIC );
        $Entity->setOwner( $Owner );
        $Entity->setDescription( $Description );
        $Entity->setServiceManagement_Person( $ServiceManagement_Person );

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

    protected function actionAddReference( $Reference, $DebtorNumber  )
    {
        $Manager = $this->getEntityManager();

        $Entity = new TblReference();
        $Entity->setReference( $Reference );
        $Entity->setIsVoid( true );
        $Entity->setServiceTblDebtor( Billing::serviceBanking()->entityDebtorByDebtorNumber( $DebtorNumber ) );
        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @param TblDebtor $tblDebtor
     *
     * @return bool|TblReference
     */
    protected function entityReferenceByDebtor( TblDebtor $tblDebtor )
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblReference' )
            ->findOneBy( array( TblReference::ATTR_TBL_DEBTOR => $tblDebtor->getId(), TblReference::ATTR_IS_VOID => true ) );
        return ( null === $Entity ? false : $Entity );
    }

}
