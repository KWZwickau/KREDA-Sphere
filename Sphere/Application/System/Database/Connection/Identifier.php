<?php
namespace KREDA\Sphere\Application\System\Database\Connection;

/**
 * Class Identifier
 *
 * @package KREDA\Sphere\Application\System\Database\Connection
 */
class Identifier
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

        return $this->Application;
    }

    /**
     * @return string
     */
    final public function getConsumer()
    {

        return $this->Consumer;
    }

    /**
     * @return string
     */
    final public function getService()
    {

        return $this->Service;
    }

    /**
     * @return string
     */
    public function __toString()
    {

        return $this->getIdentifier();
    }

    /**
     * @return string
     */
    final public function getIdentifier()
    {

        return $this->Identifier;
    }

}
