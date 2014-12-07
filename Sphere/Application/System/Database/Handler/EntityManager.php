<?php
namespace KREDA\Sphere\Application\System\Database\Handler;

use KREDA\Sphere\Common\AbstractAddOn;

/**
 * Class EntityManager
 *
 * @package KREDA\Sphere\Application\System\Database\Handler
 */
class EntityManager extends AbstractAddOn
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

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->EntityManager = $EntityManager;
        $this->Namespace = $Namespace;
    }

    /**
     * @param $ClassName
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    final public function getEntity( $ClassName )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->EntityManager->getRepository( $this->Namespace.$ClassName );
    }

    /**
     * @param $Entity
     *
     * @return EntityManager
     */
    final public function killEntity( $Entity )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->EntityManager->remove( $Entity );
        $this->flushCache();
        return $this;
    }

    /**
     * @return EntityManager
     */
    final public function flushCache()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

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

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->EntityManager->persist( $Entity );
        $this->flushCache();
        return $this;
    }
}
