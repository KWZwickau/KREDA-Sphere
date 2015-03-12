<?php
namespace KREDA\Sphere\Common\Database\Schema;

/**
 * Class EntityRepository
 *
 * @package KREDA\Sphere\Common\Database\Schema
 */
class EntityRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * returns the number of entity's rows
     *
     * @return int
     */
    public function count()
    {

        $query = $this->createQueryBuilder( 'e' )->select( 'count(e)' )->getQuery();
        return $query->getSingleScalarResult();
    }
}
