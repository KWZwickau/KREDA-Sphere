<?php
namespace KREDA\Sphere\Application\Demo\Service\DemoService;

use KREDA\Sphere\Application\Demo\Service\DemoService\Entity\TblDemoCompleter;
use KREDA\Sphere\Application\System\System;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Demo\Service\DemoService
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @return bool|TblDemoCompleter[]
     */
    protected function entityDemoCompleterAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblDemoCompleter' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }

    /**
     * @param string $DemoCompleter
     *
     * @return TblDemoCompleter
     */
    protected function actionCreateDemoCompleter( $DemoCompleter )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblDemoCompleter' )
            ->findOneBy( array( TblDemoCompleter::ATTR_NAME => $DemoCompleter ) );
        if (null === $Entity) {

            $Entity = new TblDemoCompleter();
            $Entity->setName( $DemoCompleter );
            $Manager->saveEntity( $Entity );

            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }
}
