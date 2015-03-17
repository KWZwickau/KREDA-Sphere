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
     * @return int
     */
    public function count()
    {

        $Query = $this->createQueryBuilder( 'e' )->select( 'count(e)' )->getQuery();
        return $Query->getSingleScalarResult();
    }

    /**
     * @param $Criteria
     *
     * @return int
     */
    public function countBy( $Criteria = array() )
    {

        $EntityPersister = $this->_em->getUnitOfWork()->getEntityPersister( $this->_entityName );
        return $EntityPersister->count( $Criteria );
    }
}
