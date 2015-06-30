<?php
namespace KREDA\Sphere\Application\Management\Service\Group\Action;

use KREDA\Sphere\Application\Management\Service\Group\Entity\TblGroup;
use KREDA\Sphere\Application\System\System;

/**
 * Class Destroy
 *
 * @package KREDA\Sphere\Application\Management\Service\Group\Action
 */
abstract class Destroy extends Update
{

    /**
     * @param TblGroup $tblGroup
     *
     * @return bool
     */
    protected function destroyGroup( TblGroup $tblGroup )
    {

        $Manager = $this->getEntityManager();
        /** @var TblGroup $Entity */
        $Entity = $Manager->getEntityById( 'TblGroup', $tblGroup->getId() );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }
}
