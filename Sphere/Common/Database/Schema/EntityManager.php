<?php
namespace KREDA\Sphere\Common\Database\Schema;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;

/**
 * Class EntityManager
 *
 * @package KREDA\Sphere\Common\Database
 */
class EntityManager
{

    /** @var \Doctrine\ORM\EntityManager $EntityManager */
    private $EntityManager = null;
    /** @var string $Namespace */
    private $Namespace = '';

    /**
     * @param \Doctrine\ORM\EntityManager $EntityManager
     * @param string                      $Namespace
     */
    final function __construct( \Doctrine\ORM\EntityManager $EntityManager, $Namespace )
    {

        $this->EntityManager = $EntityManager;
        $this->Namespace = $Namespace;
    }

    /**
     * @param string $ClassName
     *
     * @return EntityRepository
     */
    final public function getEntity( $ClassName )
    {

        return $this->EntityManager->getRepository( $this->Namespace.$ClassName );
    }

    /**
     * @param string $ClassName
     * @param int    $Id
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     * @return Entity
     */
    final public function getEntityById( $ClassName, $Id )
    {

        return $this->EntityManager->find( $this->Namespace.$ClassName, $Id );
    }

    /**
     * @param $Entity
     *
     * @return EntityManager
     */
    final public function killEntity( $Entity )
    {

        $this->EntityManager->remove( $Entity );
        $this->flushCache();
        return $this;
    }

    /**
     * @return EntityManager
     */
    final public function flushCache()
    {

        $this->EntityManager->flush();
        return $this;
    }

    /**
     * @param $Entity
     *
     * @return EntityManager
     */
    final public function saveEntity( $Entity )
    {

        $this->EntityManager->persist( $Entity );
        $this->flushCache();
        return $this;
    }
}
