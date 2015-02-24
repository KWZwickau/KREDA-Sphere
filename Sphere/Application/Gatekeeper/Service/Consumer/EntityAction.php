<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Consumer;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumerType;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumerTypeList;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Consumer
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param string $Name
     *
     * @return bool|TblConsumer
     */
    protected function entityConsumerByName( $Name )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblConsumer' )
            ->findOneBy( array( TblConsumer::ATTR_NAME => $Name ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Suffix
     *
     * @return bool|TblConsumer
     */
    protected function entityConsumerBySuffix( $Suffix )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblConsumer' )
            ->findOneBy( array( TblConsumer::ATTR_SUFFIX => $Suffix ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblConsumer
     */
    protected function entityConsumerById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblConsumer', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblConsumerType
     */
    protected function entityConsumerTypeById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblConsumerType', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string          $Name
     * @param string          $DatabaseSuffix
     * @param null|TblAddress $TblAddress
     * @param string          $TableSuffix
     *
     * @return TblConsumer
     */
    protected function actionCreateConsumer( $Name, $DatabaseSuffix, TblAddress $TblAddress = null, $TableSuffix = '' )
    {

        if (empty( $TableSuffix )) {
            $TableSuffix = $DatabaseSuffix;
        }

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblConsumer' )
            ->findOneBy( array( TblConsumer::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblConsumer( $Name );
            $Entity->setDatabaseSuffix( $DatabaseSuffix );
            $Entity->setTableSuffix( $TableSuffix );
            $Entity->setServiceManagementAddress( $TblAddress );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblConsumer
     */
    protected function actionCreateConsumerType( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblConsumerType' )
            ->findOneBy( array( TblConsumerType::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblConsumerType( $Name );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblConsumer    $tblConsumer
     * @param TblConsumerType $tblConsumerType
     *
     * @return TblConsumerTypeList
     */
    protected function actionCreateConsumerTypeList( TblConsumer $tblConsumer, TblConsumerType $tblConsumerType )
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->entityConsumerTypeList( $tblConsumer, $tblConsumerType );
        if (!$Entity) {
            $Entity = new TblConsumerTypeList();
            $Entity->setTblConsumer( $tblConsumer );
            $Entity->setTblConsumerType( $tblConsumerType );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblConsumer    $tblConsumer
     * @param TblConsumerType $tblConsumerType
     *
     * @return bool|TblConsumer
     */
    private function entityConsumerTypeList( TblConsumer $tblConsumer, TblConsumerType $tblConsumerType )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblConsumerTypeList' )
            ->findOneBy( array(
                TblConsumerTypeList::ATTR_TBL_CONSUMER      => $tblConsumer->getId(),
                TblConsumerTypeList::ATTR_TBL_CONSUMER_TYPE => $tblConsumerType->getId()
            ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblConsumer    $tblConsumer
     * @param TblConsumerType $tblConsumerType
     *
     * @return bool
     */
    protected function actionDestroyConsumerTypeList( TblConsumer $tblConsumer, TblConsumerType $tblConsumerType )
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->entityConsumerTypeList( $tblConsumer, $tblConsumerType );
        if ($Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @return tblConsumer[]|bool
     */
    protected function entityConsumerAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblConsumer' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param TblAddress       $tblAddress
     * @param null|TblConsumer $tblConsumer
     *
     * @return bool
     */
    protected function actionChangeAddress( TblAddress $tblAddress, TblConsumer $tblConsumer = null )
    {

        if (null === $tblConsumer) {
            $tblConsumer = $this->entityConsumerBySession();
        }
        $Manager = $this->getEntityManager();
        /**
         * @var TblConsumer $From
         * @var TblConsumer $To
         */
        $To = $Manager->getEntityById( 'TblConsumer', $tblConsumer->getId() );
        $From = clone $To;
        if (null !== $To) {
            $To->setServiceManagementAddress( $tblAddress );
            $Manager->saveEntity( $To );
            System::serviceProtocol()->executeCreateUpdateEntry( $this->getDatabaseHandler()->getDatabaseName(), $From,
                $To );
            return true;
        }
        return false;
    }

    /**
     * @param null|string $Session
     *
     * @return bool|TblConsumer
     */
    protected function entityConsumerBySession( $Session = null )
    {

        if (false !== ( $tblAccount = Gatekeeper::serviceAccount()->entityAccountBySession( $Session ) )) {
            return $tblAccount->getServiceGatekeeperConsumer();
        } else {
            return false;
        }
    }
}
