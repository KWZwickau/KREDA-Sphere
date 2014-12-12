<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Consumer;

use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;

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

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblConsumer' )
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

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntityById( 'TblConsumer', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Name
     *
     * @return TblConsumer
     */
    protected function actionCreateConsumer( $Name )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblConsumer' )
            ->findOneBy( array( TblConsumer::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblConsumer( $Name );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
        }
        return $Entity;
    }

    /**
     * @return ViewConsumer[]|bool
     */
    protected function entityViewConsumer()
    {

        $EntityList = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'ViewConsumer' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }
}
