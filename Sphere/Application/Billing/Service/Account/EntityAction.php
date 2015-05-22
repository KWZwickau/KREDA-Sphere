<?php
namespace KREDA\Sphere\Application\Billing\Service\Account;

use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccountKeyType;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccountType;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccountKey;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblDebitor;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Billing\Service\Account
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblAccountType
     */
    protected function entityAccountTypeById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccountType', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $Id
     * @return bool|TblAccountKey
     */
    protected function entityAccountKeyById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccountKey', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $Id
     * @return bool|TblAccountKeyType
     */
    protected function entityAccountKeyTypeById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccountKeyType', $Id );
        return ( null === $Entity ? false : $Entity);
    }

    /**
     * @param $Id
     * @return bool|TblDebitor
     */
    protected function entityDebitorById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblDebitor', $Id );
        return (null === $Entity ? false : $Entity);
    }

    /**
     * @param $Number
     * @param $Description
     * @param $isActive
     * @param $Key
     * @param $Type
     * @return TblAccount
     */
    protected function actionAddAccount( $Number, $Description, $isActive, TblAccountKey $Key, TblAccountType $Type )
    {

        $Manager = $this->getEntityManager();

        $Entity = new TblAccount();

        $Entity->setDescription($Description);
        $Entity->setIsActive($isActive);
        $Entity->setNumber($Number);
        $Entity->setTblAccountKey( $Key );
        $Entity->setTblAccountType( $Type );

        $Manager->saveEntity($Entity);

        System::serviceProtocol()->executeCreateInsertEntry($this->getDatabaseHandler()->getDatabaseName(), $Entity);

        return $Entity;
    }

    /**
     * @param $First
     * @param $Second
     * @param $Number
     * @return TblDebitor
     */
    protected function actionAddDebitor($First, $Second, $Number )
    {

        $Manager = $this->getEntityManager();

        $Entity = new TblDebitor();
        $Entity->setLeadTimeFirst($First);
        $Entity->setLeadTimeFollow($Second);
        $Entity->setDebitorNummer($Number);

        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @return bool|TblAccountKey
     */
    protected function entityKeyValueAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'tblAccountKey' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblAccountType
     */
    protected function entityTypeValueAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'tblAccountType' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $Name
     * @param $Description
     * @return TblAccountKeyType|null|object
     */
    protected function actionCreateKeyType( $Name, $Description)
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountKeyType' )->findOneBy( array( 'Name' => $Name, 'Description' => $Description ) );
        if (null === $Entity)
        {
            $Entity = new TblAccountKeyType();
            $Entity->setName( $Name );
            $Entity->setDescription( $Description );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        }
        return $Entity;
    }

    protected function actionCreateKey( $ValidFrom, $Value, $ValidTo, $Description, $Code, TblAccountKeyType $tblAccountKeyType)
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountKey' )->findOneBy( array(
            'ValidFrom' => new \DateTime( $ValidFrom ),
            'Value' => $Value,
            'ValidTo' => new \DateTime( $ValidTo ),
            'Description' => $Description,
            'Code' => $Code,
            'tblAccountKeyType' => $tblAccountKeyType->getId() ));

        if (null === $Entity)
        {
            $Entity = new TblAccountKey();
            $Entity->setValidFrom( new \DateTime( $ValidFrom ) );
            $Entity->setValue( $Value );
            $Entity->setValidTo( new \DateTime( $ValidTo ) );
            $Entity->setDescription( $Description );
            $Entity->setCode( $Code );
            $Entity->setTableAccountKeyType( $tblAccountKeyType );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        }
        return $Entity;
    }

    protected function actionCreateType( $Name, $Description)
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountType' )->findOneBy( array( 'Name' => $Name, 'Description' => $Description ) );
        if (null === $Entity)
        {
            $Entity = new TblAccountType();
            $Entity->setName( $Name );
            $Entity->setDescription( $Description );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );
        }
        return $Entity;
    }

    /**
     * @param $Id
     * @return bool|TblAccount
     */
    protected function entityAccountById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblAccount', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblAccount[]
     */
    protected function entityAccountActiveAll()
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblAccount' )->findBy(array('IsActive'=>1));
        return ( null === $Entity ? false : $Entity );
    }
}