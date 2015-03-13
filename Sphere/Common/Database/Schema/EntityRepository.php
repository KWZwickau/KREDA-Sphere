<?php
namespace KREDA\Sphere\Common\Database\Schema;

use Doctrine\Common\Collections\Selectable;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Class EntityRepository
 *
 * @package KREDA\Sphere\Common\Database\Schema
 */
class EntityRepository extends \Doctrine\ORM\EntityRepository implements ObjectRepository, Selectable
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

    /**
     * @param $criteria
     *
     * @return int
     */
    public function countBy( $criteria = array() )
    {

        $Persister = $this->_em->getUnitOfWork()->getEntityPersister( $this->_entityName );
        return $Persister->count( $criteria );
    }
}
