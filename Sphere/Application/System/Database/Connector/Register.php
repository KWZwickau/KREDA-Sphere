<?php
namespace KREDA\Sphere\Application\System\Database\Connector;

use KREDA\Sphere\Application\System\Database\Identifier;
use KREDA\Sphere\Common\AbstractAddOn;
use MOC\V\Component\Database\Component\IBridgeInterface;

/**
 * Class Register
 *
 * @package KREDA\Sphere\Application\System\Database\Connector
 */
class Register extends AbstractAddOn
{

    /** @var Register $Singleton */
    private static $Singleton = null;
    /** @var IBridgeInterface[] $Register */
    private static $Register = array();

    /**
     * Private: MUST NOT USE
     */
    final private function __construct()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

    }

    /**
     * @return Register
     */
    final public static function getSingleton()
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        if (null === self::$Singleton) {
            self::$Singleton = new self;
        }
        return self::$Singleton;
    }

    /**
     * @param Identifier $Identifier
     *
     * @return bool
     */
    final public function hasDatabase( Identifier $Identifier )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return array_key_exists( $Identifier->getIdentifier(), self::$Register );
    }

    /**
     * @param Identifier       $Identifier
     * @param IBridgeInterface $Connection
     *
     * @return Register
     */
    final public function addDatabase( Identifier $Identifier, IBridgeInterface $Connection )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (!array_key_exists( $Identifier->getIdentifier(), self::$Register )) {
            self::$Register[$Identifier->getIdentifier()] = $Connection;
        }
        return $this;
    }

    /**
     * @param Identifier $Identifier
     *
     * @throws \Exception
     * @return IBridgeInterface
     */
    final public function getDatabase( Identifier $Identifier )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (array_key_exists( $Identifier->getIdentifier(), self::$Register )) {
            return self::$Register[$Identifier->getIdentifier()];
        } else {
            throw new \Exception();
        }
    }

    /**
     * Private: MUST NOT USE
     */
    final private function __clone()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

    }
}
