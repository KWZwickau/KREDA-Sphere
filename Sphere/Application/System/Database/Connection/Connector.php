<?php
namespace KREDA\Sphere\Application\System\Database\Connection;

use KREDA\Sphere\Application\System\Database\Driver\AbstractDriver;
use MOC\V\Component\Database\Component\IBridgeInterface;
use MOC\V\Component\Database\Database;

/**
 * Class Connector
 *
 * @package KREDA\Sphere\Application\System\Database\Connection
 */
class Connector
{

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

        $Consumer = $Identifier->getConsumer();
        Register::getSingleton()->addDatabase( $Identifier,
            Database::getDatabase( $Username, $Password, $Database.( empty( $Consumer ) ? '' : '_'.$Consumer ),
                $Driver->getIdentifier(), $Host, $Port )
        );
    }

    /**
     * @param Identifier $Identifier
     *
     * @return bool
     */
    public function hasConnection( Identifier $Identifier )
    {

        return Register::getSingleton()->hasDatabase( $Identifier );
    }

    /**
     * @param Identifier $Identifier
     *
     * @return IBridgeInterface
     * @throws \Exception
     */
    final public function getConnection( Identifier $Identifier )
    {

        return Register::getSingleton()->getDatabase( $Identifier );
    }
}
