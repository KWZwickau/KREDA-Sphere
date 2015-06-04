<?php
namespace KREDA\Sphere\Application\Billing\Service\Account;

use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccountKey;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccountKeyType;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccountType;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblDebtor;
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
     *
     * @return bool|TblAccountKey
     */
    protected function entityAccountKeyById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccountKey', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $Id
     *
     * @return bool|TblAccountKeyType
     */
    protected function entityAccountKeyTypeById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccountKeyType', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $Number
     * @param $Description
     * @param $isActive
     * @param $Key
     * @param $Type
     *
     * @return TblAccount
     */
    protected function actionAddAccount( $Number, $Description, $isActive, TblAccountKey $Key, TblAccountType $Type )
    {

        $Manager = $this->getEntityManager();

        $Entity = new TblAccount();

        $Entity->setDescription( $Description );
        $Entity->setIsActive( $isActive );
        $Entity->setNumber( $Number );
        $Entity->setTblAccountKey( $Key );
        $Entity->setTblAccountType( $Type );

        $Manager->saveEntity( $Entity );

        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @return bool|TblAccountKey
     */
    protected function entityKeyValueAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblAccountKey' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblAccountType
     */
    protected function entityTypeValueAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblAccountType' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $Name
     * @param $Description
     *
     * @return TblAccountKeyType|null|object
     */
    protected function actionCreateKeyType( $Name, $Description )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountKeyType' )->findOneBy( array(
            'Name'        => $Name,
            'Description' => $Description
        ) );
        if (null === $Entity) {
            $Entity = new TblAccountKeyType();
            $Entity->setName( $Name );
            $Entity->setDescription( $Description );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param                   $ValidFrom
     * @param                   $Value
     * @param                   $ValidTo
     * @param                   $Description
     * @param                   $Code
     * @param TblAccountKeyType $tblAccountKeyType
     *
     * @return TblAccountKey|null|object
     */
    protected function actionCreateKey(
        $ValidFrom,
        $Value,
        $ValidTo,
        $Description,
        $Code,
        TblAccountKeyType $tblAccountKeyType
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountKey' )->findOneBy( array(
            'ValidFrom'         => new \DateTime( $ValidFrom ),
            'Value'             => $Value,
            'ValidTo'           => new \DateTime( $ValidTo ),
            'Description'       => $Description,
            'Code'              => $Code,
            'tblAccountKeyType' => $tblAccountKeyType->getId()
        ) );

        if (null === $Entity) {
            $Entity = new TblAccountKey();
            $Entity->setValidFrom( new \DateTime( $ValidFrom ) );
            $Entity->setValue( $Value );
            $Entity->setValidTo( new \DateTime( $ValidTo ) );
            $Entity->setDescription( $Description );
            $Entity->setCode( $Code );
            $Entity->setTableAccountKeyType( $tblAccountKeyType );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param $Name
     * @param $Description
     *
     * @return TblAccountType|null|object
     */
    protected function actionCreateType( $Name, $Description )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAccountType' )->findOneBy( array(
            'Name'        => $Name,
            'Description' => $Description
        ) );
        if (null === $Entity) {
            $Entity = new TblAccountType();
            $Entity->setName( $Name );
            $Entity->setDescription( $Description );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param $Id
     *
     * @return bool|TblAccount
     */
    protected function entityAccountById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAccount', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param bool $IsActive
     *
     * @return bool|TblAccount[]
     */
    protected function entityAccountAllByActiveState( $IsActive = true )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblAccount' )->findBy( array(
            TblAccount::ATTR_IS_ACTIVE => $IsActive
        ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return array|bool|TblAccount[]
     */
    protected function entityAccountAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblAccount' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblAccount     $tblAccount
     * @param                $Description
     * @param                $Number
     * @param                $IsActive
     * @param TblAccountKey  $tblAccountKey
     * @param TblAccountType $tblAccountType
     *
     * @return bool
     */
    protected function actionEditAccount(
        TblAccount $tblAccount,
        $Description,
        $Number,
        $IsActive,
        TblAccountKey $tblAccountKey,
        TblAccountType $tblAccountType
    ) {

        $Manager = $this->getEntityManager();

        /** @var TblAccount $Entity */
        $Entity = $Manager->getEntityById( 'TblAccount', $tblAccount->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setDescription( $Description );
            $Entity->setNumber( $Number );
            $Entity->setIsActive( $IsActive );
            $Entity->setTblAccountKey( $tblAccountKey );
            $Entity->setTblAccountType( $tblAccountType );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param $Id
     *
     * @return bool
     */
    protected function actionActivateAccount( $Id )
    {

        $Manager = $this->getEntityManager();

        /** @var TblAccount $Entity */
        $Entity = $Manager->getEntityById( 'TblAccount', $Id );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setIsActive( '1' );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param $Id
     *
     * @return bool
     */
    protected function actionDeactivateAccount( $Id )
    {

        $Manager = $this->getEntityManager();

        /** @var TblAccount $Entity */
        $Entity = $Manager->getEntityById( 'TblAccount', $Id );
        $Protocol = clone $Entity;
        if (null !== $Entity) {
            $Entity->setIsActive( '0' );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );
            return true;
        }
        return false;
    }

    /**
     * @return array|bool|TblAccountKey[]
     */
    protected function entityAccountKeyAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblAccountKey' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return array|bool|TblAccountType[]
     */
    protected function entityAccountTypeAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblAccountType' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }
}
