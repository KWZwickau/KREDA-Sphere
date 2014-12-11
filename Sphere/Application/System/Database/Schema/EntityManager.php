<?php
namespace KREDA\Sphere\Application\System\Database\Schema;

use Doctrine\ORM\EntityRepository;

class EntityManager
{

    /** @var \Doctrine\ORM\EntityManager|null $EntityManager */
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @return EntityRepository
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
