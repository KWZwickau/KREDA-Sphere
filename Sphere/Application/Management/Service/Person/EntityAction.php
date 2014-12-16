<?php
namespace KREDA\Sphere\Application\Management\Service\Person;

use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;

/**
 * Class EntityAction
 *
 * @package KREDA\Sphere\Application\Management\Service\Person
 */
abstract class EntityAction extends EntitySchema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblPerson
     */
    protected function entityPersonById( $Id )
    {

        $Entity = $this->getDatabaseHandler()->getEntityManager()->getEntityById( 'TblPerson', $Id );
        return ( null === $Entity ? false : $Entity );
    }
}