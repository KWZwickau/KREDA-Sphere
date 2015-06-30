<?php
namespace KREDA\Sphere\Application\Management\Service\Group\Action;

use KREDA\Sphere\Application\Management\Service\Group\Entity\TblGroup;
use KREDA\Sphere\Application\Management\Service\Group\Schema;

/**
 * Class Fetch
 *
 * @package KREDA\Sphere\Application\Management\Service\Group\Action
 */
abstract class Fetch extends Schema
{

    /**
     * @param integer $Id
     *
     * @return bool|TblGroup
     */
    protected function fetchGroupById( $Id )
    {

        $Entity = $this->getEntityManager()->getEntityById( 'TblGroup', $Id );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblGroup
     */
    protected function fetchGroupByName( $Name )
    {

        $Entity = $this->getEntityManager()->getEntity( 'TblGroup' )->findOneBy( array(
            TblGroup::ATTR_NAME => $Name
        ) );
        return ( null === $Entity ? false : $Entity );
    }

    /**
     * @return bool|TblGroup[]
     */
    protected function fetchGroupAll()
    {

        $EntityList = $this->getEntityManager()->getEntity( 'TblGroup' )->findAll();
        return ( empty( $EntityList ) ? false : $EntityList );
    }
}
