<?php
namespace KREDA\Sphere\Application\Management\Service\Group\Action;

use KREDA\Sphere\Application\Management\Service\Group\Entity\TblGroup;
use KREDA\Sphere\Application\Management\Service\Group\Schema;

/**
 * Class Select
 *
 * @package KREDA\Sphere\Application\Management\Service\Group\Action
 */
abstract class Select extends Schema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblGroup
     */
    protected function selectGroupById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblGroup', $Id );
        return ( null === $Entity ? false : $Entity );
    }
}
