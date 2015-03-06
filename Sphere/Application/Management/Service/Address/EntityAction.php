<?php
namespace KREDA\Sphere\Application\Management\Service\Address;

use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressCity;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressState;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Management\Service\Address
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblAddress
     */
    protected function entityAddressById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAddress', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAddressCity
     */
    protected function entityAddressCityById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAddressCity', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblAddressCity[]
     */
    protected function entityAddressCityAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblAddressCity' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAddressState
     */
    protected function entityAddressStateById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblAddressState', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblAddressState[]
     */
    protected function entityAddressStateAll()
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblAddressState' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Name
     *
     * @return TblAddressState
     */
    protected function actionCreateAddressState( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAddressState' )
            ->findOneBy( array( TblAddressState::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAddressState();
            $Entity->setName( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param string      $Code
     * @param string      $Name
     * @param null|string $District
     *
     * @return TblAddressCity
     */
    protected function actionCreateAddressCity( $Code, $Name, $District = null )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAddressCity' )
            ->findOneBy( array(
                TblAddressCity::ATTR_CODE => $Code,
                TblAddressCity::ATTR_NAME => $Name
            ) );
        if (null === $Entity) {
            $Entity = new TblAddressCity();
            $Entity->setCode( $Code );
            $Entity->setName( $Name );
            $Entity->setDistrict( $District );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param null|TblAddressState $TblAddressState
     * @param null|TblAddressCity  $TblAddressCity
     * @param null|string          $StreetName
     * @param null|string          $StreetNumber
     * @param null|string          $PostOfficeBox
     *
     * @return TblAddress
     */
    protected function actionCreateAddress(
        TblAddressState $TblAddressState = null,
        TblAddressCity $TblAddressCity = null,
        $StreetName = null,
        $StreetNumber = null,
        $PostOfficeBox = null
    ) {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblAddress' )
            ->findOneBy( array(
                TblAddress::ATTR_TBL_ADDRESS_STATE => $TblAddressState->getId(),
                TblAddress::ATTR_TBL_ADDRESS_CITY  => $TblAddressCity->getId(),
                TblAddress::ATTR_STREET_NAME       => $StreetName,
                TblAddress::ATTR_STREET_NUMBER => $StreetNumber,
                TblAddress::ATTR_POST_OFFICE_BOX   => $PostOfficeBox
            ) );
        if (null === $Entity) {
            $Entity = new TblAddress();
            $Entity->setStreetName( $StreetName );
            $Entity->setStreetNumber( $StreetNumber );
            $Entity->setPostOfficeBox( $PostOfficeBox );
            $Entity->setTblAddressState( $TblAddressState );
            $Entity->setTblAddressCity( $TblAddressCity );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

}
