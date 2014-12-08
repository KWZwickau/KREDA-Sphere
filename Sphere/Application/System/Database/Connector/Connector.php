<?php
namespace KREDA\Sphere\Application\System\Database\Connector;

use KREDA\Sphere\Application\System\Database\AbstractDriver;
use KREDA\Sphere\Application\System\Database\Identifier;
use KREDA\Sphere\Common\AbstractAddOn;
use MOC\V\Component\Database\Component\IBridgeInterface;
use MOC\V\Component\Database\Database;

/**
 * Class Connector
 *
 * @package KREDA\Sphere\Application\System\Database\Connector
 */
class Connector extends AbstractAddOn
{

    /**
     * @return Connector
     */
    final static public function getInstance()
    {

        self::getDebugger()->addMethodCall( __METHOD__ );

        return new Connector();
    }

    /**
     * @param Identifier $Identifier
     *
     * @return bool
     */
    public function hasConnection( Identifier $Identifier )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return Register::getSingleton()->hasDatabase( $Identifier );
    }

    /**
     * @param Identifier     $Identifier
     * @param AbstractDriver $Driver
     * @param string         $Username
     * @param string         $Password
     * @param string         $Database
     * @param string         $Host
     * @param null|int       $Port
     */
    final public function addConnection(
        Identifier $Identifier,
        AbstractDriver $Driver,
        $Username,
        $Password,
        $Database,
        $Host,
        $Port = null
    ) {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Consumer = $Identifier->getConsumer();
        Register::getSingleton()->addDatabase( $Identifier,
            Database::getDatabase( $Username, $Password, $Database.( empty( $Consumer ) ? '' : '_'.$Consumer ),
                $Driver->getIdentifier(), $Host, $Port )
        );
    }

    /**
     * @param Identifier $Identifier
     *
     * @return IBridgeInterface
     * @throws \Exception
     */
    final public function getConnection( Identifier $Identifier )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return Register::getSingleton()->getDatabase( $Identifier );
    }
}
