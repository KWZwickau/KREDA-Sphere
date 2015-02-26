<?php
namespace KREDA\Sphere\Common\Wire;

/**
 * Class Plug
 *
 * @package KREDA\Sphere\Common\Wire
 */
class Plug
{

    /** @var \ReflectionClass $Class */
    private $Class = null;
    /** @var \ReflectionMethod $Method */
    private $Method = null;

    /**
     * @param string $Class
     * @param string $Method
     */
    function __construct( $Class, $Method )
    {

        $this->Class = new \ReflectionClass( $Class );
        $this->Method = $this->Class->getMethod( $Method );
    }

    /**
     * @return \ReflectionClass
     */
    public function getClass()
    {

        return $this->Class;
    }

    /**
     * @return \ReflectionMethod
     */
    public function getMethod()
    {

        return $this->Method;
    }

    /**
     * @return string
     */
    public function getWire()
    {

        return sha1( $this->Class->getName().$this->Method->getName() );
    }
}
