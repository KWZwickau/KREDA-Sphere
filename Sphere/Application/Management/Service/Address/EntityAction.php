<?php
namespace KREDA\Sphere\Application\Management\Service\Address;

use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressCity;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressState;
use KREDA\Sphere\Application\Management\Service\Address\Entity\ViewAddress;

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

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntityById( 'TblAddress', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAddressCity
     */
    protected function entityAddressCityById( $Id )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntityById( 'TblAddressCity', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblAddressState
     */
    protected function entityAddressStateById( $Id )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntityById( 'TblAddressState', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblAddressState[]
     */
    protected function entityAddressState()
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAddressState' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Name
     *
     * @return TblAddressState
     */
    protected function actionCreateAddressState( $Name )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAddressState' )
            ->findOneBy( array( TblAddressState::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblAddressState();
            $Entity->setName( $Name );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
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

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAddressCity' )
            ->findOneBy( array(
                TblAddressCity::ATTR_CODE => $Code,
                TblAddressCity::ATTR_NAME => $Name
            ) );
        if (null === $Entity) {
            $Entity = new TblAddressCity();
            $Entity->setCode( $Code );
            $Entity->setName( $Name );
            $Entity->setDistrict( $District );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
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

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblAddress' )
            ->findOneBy( array(
                TblAddress::ATTR_TBL_ADDRESS_STATE => $TblAddressState->getId(),
                TblAddress::ATTR_TBL_ADDRESS_CITY  => $TblAddressCity->getId(),
                TblAddress::ATTR_STREET_NAME       => $StreetName,
                TblAddress::ATTR_STREET_Number     => $StreetNumber,
                TblAddress::ATTR_POST_OFFICE_BOX   => $PostOfficeBox
            ) );
        if (null === $Entity) {
            $Entity = new TblAddress();
            $Entity->setStreetName( $StreetName );
            $Entity->setStreetNumber( $StreetNumber );
            $Entity->setPostOfficeBox( $PostOfficeBox );
            $Entity->setTblAddressState( $TblAddressState );
            $Entity->setTblAddressCity( $TblAddressCity );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
        }
        return $Entity;
    }

    /**
     * @return ViewAddress[]|bool
     */
    protected function entityViewAddress()
    {

        $EntityList = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'ViewAddress' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }
}
