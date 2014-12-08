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
     * @param string $Identifier
     *
     * @return bool|TblConsumer
     */
    protected function entityConsumerByIdentifier( $Identifier )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblConsumer' )
            ->findOneBy( array( TblConsumer::ATTR_IDENTIFIER => $Identifier ) );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblConsumer
     */
    protected function entityConsumerById( $Id )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblConsumer' )->find( $Id );
        if (null === $Entity) {
            return false;
        } else {
            return $Entity;
        }
    }

    /**
     * @param string $Identifier
     *
     * @return TblConsumer
     */
    protected function actionCreateConsumer( $Identifier )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'TblConsumer' )
            ->findOneBy( array( TblConsumer::ATTR_IDENTIFIER => $Identifier ) );
        if (null === $Entity) {
            $Entity = new TblConsumer( $Identifier );
            $this->getDatabaseHandler()->getEntityManager()->saveEntity( $Entity );
        }
        return $Entity;
    }

    /**
     * @return ViewConsumer[]|bool
     */
    protected function entityViewConsumer()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $EntityList = $this->getDatabaseHandler()->getEntityManager()->getEntity( 'ViewConsumer' )->findAll();
        if (empty( $EntityList )) {
            return false;
        } else {
            return $EntityList;
        }
    }
}
