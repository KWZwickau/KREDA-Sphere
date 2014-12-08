<?php
namespace KREDA\Sphere\Application\System\Database;

use KREDA\Sphere\Common\AbstractAddOn;

/**
 * Class Identifier
 *
 * @package KREDA\Sphere\Application\System\Database
 */
class Identifier extends AbstractAddOn
{

    /** @var string $Identifier */
    private $Identifier = null;
    /** @var string $Application */
    private $Application = '';
    /** @var string $Service */
    private $Service = '';
    /** @var string $Consumer */
    private $Consumer = '';

    /**
     * @param string $Application
     * @param string $Service
     * @param string $Consumer
     */
    final public function __construct( $Application, $Service = '', $Consumer = '' )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->Application = $Application;
        $this->Service = $Service;
        $this->Consumer = $Consumer;
        $this->Identifier = sha1( $Application ).sha1( $Service ).sha1( $Consumer );
    }

    /**
     * @return string
     */
    final public function getApplication()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Application;
    }

    /**
     * @return string
     */
    final public function getConsumer()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Consumer;
    }

    /**
     * @return string
     */
    final public function getService()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Service;
    }

    /**
     * @return string
     */
    public function __toString()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->getIdentifier();
    }

    /**
     * @return string
     */
    final public function getIdentifier()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Identifier;
    }

}
