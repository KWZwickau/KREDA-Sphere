<?php
namespace KREDA\Sphere\Application\Management\Service\Group\Action;

use KREDA\Sphere\Application\Management\Service\Group\Entity\TblGroup;
use KREDA\Sphere\Application\System\System;

/**
 * Class Create
 *
 * @package KREDA\Sphere\Application\Management\Service\Group\Action
 */
abstract class Create extends Select
{

    /**
     * @param string      $Name
     * @param string|null $Description
     *
     * @return TblGroup
     */
    protected function createGroup( $Name, $Description = null )
    {

        $Manager = $this->getEntityManager();
        $Entity = $Manager->getEntity( 'TblGroup' )->findOneBy( array( TblGroup::ATTR_NAME => $Name ) );
        if (null === $Entity) {
            $Entity = new TblGroup( $Name );
            $Entity->setDescription( $Description );
            $Manager->saveEntity( $Entity );
            System::serviceProtocol()->executeCreateInsertEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
        }
        return $Entity;
    }
}
