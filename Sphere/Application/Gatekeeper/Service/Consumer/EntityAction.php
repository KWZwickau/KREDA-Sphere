<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Consumer;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumerTyp;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumerTypList;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\ViewConsumer;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;

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
     * @return bool|TblConsumerTyp
     */
    protected function entityConsumerTypById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblConsumerTyp', $Id );
        return ( null === $Entity ? false : $Entity );
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
        }
        return $Entity;
    }

    /**
     * @param string $Name
     *
     * @return TblConsumer
     */
    protected function actionCreateConsumerTyp( $Name )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblConsumerTyp' )
            ->findOneBy( array( TblConsumerTyp::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblConsumerTyp( $Name );
            $Manager->saveEntity( $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblConsumer    $tblConsumer
     * @param TblConsumerTyp $tblConsumerTyp
     *
     * @return TblConsumerTypList
     */
    protected function actionCreateConsumerTypList( TblConsumer $tblConsumer, TblConsumerTyp $tblConsumerTyp )
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->entityConsumerTypList( $tblConsumer, $tblConsumerTyp );
        if (!$Entity) {
            $Entity = new TblConsumerTypList();
            $Entity->setTblConsumer( $tblConsumer );
            $Entity->setTblConsumerTyp( $tblConsumerTyp );
            $Manager->saveEntity( $Entity );
        }
        return $Entity;
    }

    /**
     * @param TblConsumer    $tblConsumer
     * @param TblConsumerTyp $tblConsumerTyp
     *
     * @return bool|TblConsumer
     */
    private function entityConsumerTypList( TblConsumer $tblConsumer, TblConsumerTyp $tblConsumerTyp )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblConsumerTypList' )
            ->findOneBy( array(
                TblConsumerTypList::ATTR_TBL_CONSUMER     => $tblConsumer->getId(),
                TblConsumerTypList::ATTR_TBL_CONSUMER_TYP => $tblConsumerTyp->getId()
            ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param TblConsumer    $tblConsumer
     * @param TblConsumerTyp $tblConsumerTyp
     *
     * @return bool
     */
    protected function actionDestroyConsumerTypList( TblConsumer $tblConsumer, TblConsumerTyp $tblConsumerTyp )
    {

        $Manager = $this->getEntityManager();
        $Entity = $this->entityConsumerTypList( $tblConsumer, $tblConsumerTyp );
        if ($Entity) {
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }

    /**
     * @return ViewConsumer[]|bool
     */
    protected function entityViewConsumer()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'ViewConsumer' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }
}
