<?php
namespace KREDA\Sphere\Common;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\View;
use MOC\V\Component\Database\Component\IBridgeInterface;
use MOC\V\Component\Database\Database;

/**
 * Class AbstractDatabase
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractDatabase extends AbstractDebugger
{

    /** @var IBridgeInterface $Database */
    protected static $Database = null;
    /** @var IBridgeInterface[] $DatabaseMaster */
    protected static $DatabaseMaster = array();
    /** @var IBridgeInterface[] $DatabaseSlave */
    protected static $DatabaseSlave = array();

    /**
     * @return IBridgeInterface
     */
    final protected function readData()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (empty( static::$DatabaseSlave )) {
            return static::$Database;
        } else {
            return static::$DatabaseSlave[array_rand( static::$DatabaseSlave )];
        }
    }

    /**
     * @param $Service
     * @param $Cluster
     *
     * @throws \Exception
     */
    final protected function connectDatabase( $Service, $Cluster = 'Default' )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Config = __DIR__.'/../Application/System/Service/Database/Config/'.$Service.'.ini';
        if (false !== ( $Config = realpath( $Config ) )) {
            $Setting = parse_ini_file( $Config, true );
            foreach ((array)$Setting as $Key => $Group) {
                $Key = explode( ':', $Key );
                if (strtoupper( $Key[1] ) != strtoupper( $Cluster )) {
                    continue;
                }

                switch (strtoupper( $Key[0] )) {
                    case 'MASTER':
                        $this->registerDatabaseMaster(
                            $Group['Username'],
                            $Group['Password'],
                            $Group['Database'],
                            $Group['Driver'],
                            $Group['Host'],
                            $Group['Port']
                        );
                        break;
                    case 'SLAVE':
                        $this->registerDatabaseSlave(
                            $Group['Username'],
                            $Group['Password'],
                            $Group['Database'],
                            $Group['Driver'],
                            $Group['Host'],
                            $Group['Port']
                        );
                        break;
                }
            }

        } else {
            throw new \Exception( 'Missing Setting '.$Service );
        }
    }

    /**
     * AbstractDatabase Write Access
     *
     * @param string $Username
     * @param string $Password
     * @param string $Database
     * @param int    $Driver
     * @param string $Host
     * @param null   $Port
     *
     * @return $this
     */
    final private function registerDatabaseMaster( $Username, $Password, $Database, $Driver, $Host, $Port = null )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Identifier = sha1( serialize( func_get_args() ) );
        if (!array_key_exists( $Identifier, static::$DatabaseMaster )) {
            static::$DatabaseMaster[$Identifier] = Database::getDatabase( $Username, $Password, $Database, $Driver,
                $Host, $Port );
        }
        static::$Database = static::$DatabaseMaster[$Identifier];
        return $this;
    }

    /**
     * AbstractDatabase Read Access
     *
     * @param string $Username
     * @param string $Password
     * @param string $Database
     * @param int    $Driver
     * @param string $Host
     * @param null   $Port
     *
     * @return $this
     */
    final private function registerDatabaseSlave( $Username, $Password, $Database, $Driver, $Host, $Port = null )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $Identifier = sha1( serialize( func_get_args() ) );
        if (!array_key_exists( $Identifier, static::$DatabaseSlave )) {
            static::$DatabaseSlave[$Identifier] = Database::getDatabase( $Username, $Password, $Database, $Driver,
                $Host, $Port );
        }
        return $this;
    }

    /**
     * @param string $TableName
     *
     * @return bool
     */
    final protected function dbHasTable( $TableName )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $SchemaManager = static::$Database->getSchemaManager();
        return in_array( $TableName, $SchemaManager->listTableNames() );
    }

    /**
     * @return IBridgeInterface
     */
    final protected function writeData()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return static::$Database;
    }

    /**
     * @param string $ViewName
     *
     * @return bool
     */
    final protected function dbHasView( $ViewName )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $SchemaManager = static::$Database->getSchemaManager();
        $NameList = array_map( function ( View $V ) {

            return $V->getName();
        }, $SchemaManager->listViews() );
        return in_array( $ViewName, $NameList );
    }

    /**
     * @param string $TableName
     * @param string $ColumnName
     *
     * @return bool
     */
    final protected function dbTableHasColumn( $TableName, $ColumnName )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $SchemaManager = static::$Database->getSchemaManager();
        $NameList = array_map( function ( Column $V ) {

            return $V->getName();
        }, $SchemaManager->listTableColumns( $TableName ) );
        return in_array( $ColumnName, $NameList );
    }
}
