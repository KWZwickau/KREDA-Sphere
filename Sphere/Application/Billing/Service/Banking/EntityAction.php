<?php
namespace KREDA\Sphere\Application\Billing\Service\Banking;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtorCommodity;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
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
     * @return bool|TblDebtor
     */
    protected function entityDebtorById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblDebtor', $Id );
        return (null === $Entity ? false : $Entity);
    }

    /**
     * @param $LeadTimeFollow
     * @param $LeadTimeFirst
     * @param $DebtorNumber
     * @param $ServiceManagement_Person
     * @return TblDebtor
     */
    protected function actionAddDebtor($DebtorNumber, $LeadTimeFirst, $LeadTimeFollow, $ServiceManagement_Person )
    {

        $Manager = $this->getEntityManager();

        $Entity = new TblDebtor();
        $Entity->setLeadTimeFirst( $LeadTimeFirst );
        $Entity->setLeadTimeFollow( $LeadTimeFollow );
        $Entity->setDebtorNumber( $DebtorNumber );
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

}