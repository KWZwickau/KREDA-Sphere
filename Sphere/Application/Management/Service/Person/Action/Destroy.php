<?php
namespace KREDA\Sphere\Application\Management\Service\Person\Action;

use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\System\System;

/**
 * Class Destroy
 *
 * @package KREDA\Sphere\Application\Management\Service\Person\Action
 */
abstract class Destroy extends Change
{

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool
     */
    protected function actionDestroyPerson( TblPerson $tblPerson )
    {

        $Manager = $this->getEntityManager();
        /** @var TblPerson $Entity */
        $Entity = $Manager->getEntityById( 'TblPerson', $tblPerson->getId() );
        if (null !== $Entity) {
            System::serviceProtocol()->executeCreateDeleteEntry( $this->getDatabaseHandler()->getDatabaseName(),
                $Entity );
            $Manager->killEntity( $Entity );
            return true;
        }
        return false;
    }
}
