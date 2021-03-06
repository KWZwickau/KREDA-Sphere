<?php
namespace KREDA\Sphere\Common\Database\Connection;

use KREDA\Sphere\Common\AbstractExtension;
use KREDA\Sphere\Common\Database\Driver\AbstractDriver;
use MOC\V\Component\Database\Component\IBridgeInterface;

/**
 * Class Connector
 *
 * @package KREDA\Sphere\Common\Database
 */
class Connector extends AbstractExtension
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
            $this->extensionDatabase( $Username, $Password, $Database.( empty( $Consumer ) ? '' : '_'.$Consumer ),
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
