<?php
namespace KREDA\Sphere\Application\Management\Service\Company;

use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Company\Entity\TblCompany;
use KREDA\Sphere\Application\Management\Service\Company\Entity\TblCompanyAddress;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Management\Service\Company
 */
abstract class EntityAction extends EntitySchema
{
    /***
     * @param $Id
     *
     * @return bool|TblCompany
     */
    protected function entityCompanyById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblCompany', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param $Id
     *
     * @return bool|TblCompanyAddress
     */
    protected function entityCompanyAddressById( $Id )
    {
        $Entity = $this->getEntityManager()->getEntityById( 'TblCompanyAddress', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblCompany $tblCompany
     * @param TblAddress $tblAddress
     *
     * @return bool|TblCompanyAddress
     */
    protected function entityCompanyAddressByCompanyAndAddress(TblCompany $tblCompany, TblAddress $tblAddress )
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblCompanyAddress')->findOneBy(array(
            TblCompanyAddress::ATTR_TBL_Company => $tblCompany->getId(),
            TblCompanyAddress::ATTR_SERVICE_MANAGEMENT_ADDRESS => $tblAddress->getId()
        ));
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblCompany[]
     */
    protected function entityCompanyAll()
    {
        $Entity = $this->getEntityManager()->getEntity( 'TblCompany' )->findAll();
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblCompany $tblCompany
     * @return bool|TblAddress[]
     */
    protected function entityAddressAllByCompany( TblCompany $tblCompany )
    {
        $EntityList = $this->getEntityManager()->getEntity( 'TblCompanyAddress' )->findBy( array(
            TblCompanyAddress::ATTR_TBL_Company => $tblCompany->getId()
        ) );

        if (!empty( $EntityList )) {
            array_walk( $EntityList, function ( TblCompanyAddress &$Entity ) {

                $Entity = $Entity->getServiceManagementAddress();
            } );
        }

        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param $Name
     *
     * @return TblCompany
     */
    protected function actionCreateCompany(
        $Name
    )
    {
        $Manager = $this->getEntityManager();

        $Entity = new TblCompany();
        $Entity->setName( $Name );

        $Manager->saveEntity( $Entity );
        System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(), $Entity );

        return $Entity;
    }

    /**
     * @param TblCompany $tblCompany
     * @param TblAddress $tblAddress
     *
     * @return TblCompanyAddress|null
     */
    protected function actionAddCompanyAddress(
        TblCompany $tblCompany,
        TblAddress $tblAddress
    )
    {
        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntity( 'TblCompanyAddress' )->findOneBy( array(
                TblCompanyAddress::ATTR_TBL_Company => $tblCompany->getId(),
                TblCompanyAddress::ATTR_SERVICE_MANAGEMENT_ADDRESS => $tblAddress->getId()
        ));
        if (null === $Entity)
        {
            $Entity = new TblCompanyAddress();
            $Entity->setTblCompany( $tblCompany );
            $Entity->setServiceManagementAddress( $tblAddress );

            $Manager->SaveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }

        return $Entity;
    }

    /**
     * @param TblCompanyAddress $tblCompanyAddress
     *
     * @return bool
     */
    protected function actionRemoveCompanyAddress(
        TblCompanyAddress $tblCompanyAddress
    )
    {
        $Manager = $this->getEntityManager();

        $Entity = $Manager->getEntityById( 'TblCompanyAddress' , $tblCompanyAddress->getId() );
        if (null !== $Entity)
        {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @param TblCompany $tblCompany
     *
     * @return bool
     */
    protected function actionDestroyCompany(
        TblCompany $tblCompany
    )
    {
        if ($tblCompany !== null)
        {
            $Manager = $this->getEntityManager();

            $EntityList = $Manager->getEntity( 'TblCompanyAddress' )->findBy( array( TblCompanyAddress::ATTR_TBL_Company => $tblCompany->getId() ) );
            foreach ($EntityList as $Entity) {
                System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                    $Entity );
                $Manager->bulkKillEntity( $Entity );
            }

            $Entity = $Manager->getEntityById( 'TblCompany', $tblCompany->getId() );
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->bulkKillEntity( $Entity );

            $Manager->flushCache();

            return true;
        }

        return false;
    }

    /**
     * @param TblCompany $tblCompany
     * @param $Name
     *
     * @return bool
     */
    protected function actionEditCompany(
        TblCompany $tblCompany,
        $Name
    )
    {
        $Manager = $this->getEntityManager();

        /** @var TblCompany $Entity */
        $Entity = $Manager->getEntityById( 'TblCompany', $tblCompany->getId() );
        $Protocol = clone $Entity;
        if (null !== $Entity)
        {
            $Entity->setName( $Name );

            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Protocol,
                $Entity );

            return true;
        }

        return false;
    }
}
