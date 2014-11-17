<?php
namespace KREDA\Sphere\Application;

use KREDA\Sphere\IServiceInterface;
use MOC\V\Component\Database\Component\IBridgeInterface;
use MOC\V\Component\Database\Database;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Service
 *
 * @package KREDA\Sphere
 */
abstract class Service implements IServiceInterface
{

    /** @var IBridgeInterface $DatabaseMaster */
    protected static $DatabaseMaster = null;
    /** @var IBridgeInterface[] $DatabaseSlave */
    protected static $DatabaseSlave = array();
    /** @var null|string $BaseRoute Client-Application Route */
    protected static $BaseRoute = null;

    protected $InstallProtocol = array();

    /**
     * @param null|string $BaseRoute Client-Application Route
     *
     * @return static Service Instance
     */
    final public static function getApi( $BaseRoute = null )
    {

        static::$BaseRoute = $BaseRoute;
        return new static;
    }

    abstract public function setupDataStructure();

    /**
     * @param string $Item
     *
     * @return array
     */
    public function addInstallProtocol( $Item )
    {

        if (empty( $this->InstallProtocol )) {
            $this->InstallProtocol[] = '<samp>'.$Item.'</samp>';
        } else {
            $this->InstallProtocol[] = '<div><span class="glyphicon glyphicon-transfer"></span>&nbsp;<samp>'.$Item.'</samp></div>';
        }
    }

    /**
     * @return string
     */
    public function getInstallProtocol()
    {

        if (count( $this->InstallProtocol ) == 1) {
            $this->InstallProtocol[0] .= '<br/>';
            return '<div class="alert alert-success text-left">'
            .'<span class="glyphicon glyphicon-ok"></span>&nbsp;'
            .implode( '', $this->InstallProtocol )
            .'<span class="glyphicon glyphicon-saved"></span>&nbsp;Kein Update notwendig'
            .'</div>';
        }
        $this->InstallProtocol[0] .= '<hr/>';
        return '<div class="alert alert-info text-left">'
        .'<span class="glyphicon glyphicon-flash"></span>&nbsp;'
        .implode( '', $this->InstallProtocol )
        .'<span class="glyphicon glyphicon-saved"></span>&nbsp;Update durchgeführt'
        .'</div>';
    }

    /**
     * @param string $Route Service Route
     *
     * @return null|string Client-Application Route
     */
    final protected function useRoute( $Route )
    {

        return HttpKernel::getRequest()->getUrlBase().static::$BaseRoute.'/'.trim( $Route, '/' );
    }

    /**
     * @return IBridgeInterface
     */
    final protected function readData()
    {

        $SlaveCount = count( static::$DatabaseSlave );
        if ($SlaveCount == 0) {
            return static::$DatabaseMaster;
        } else {
            return static::$DatabaseSlave[rand( 0, $SlaveCount - 1 )];
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

        $Config = __DIR__.'/System/Service/Database/Config/'.$Service.'.ini';
        if (false !== ( $Config = realpath( $Config ) )) {
            $Setting = parse_ini_file( $Config, true );
            foreach ((array)$Setting as $Key => $Group) {
                $Key = explode( ':', $Key );
                if (strtoupper( $Key[1] ) != strtoupper( $Cluster )) {
                    continue;
                }
                try {
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

                } catch( \Exception $E ) {
                    throw new \Exception( 'Connection Error' );
                }
            }

        } else {
            throw new \Exception( 'Missing Setting '.$Service );
        }
    }


    /**
     * Database Write Access
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
    private final function registerDatabaseMaster( $Username, $Password, $Database, $Driver, $Host, $Port = null )
    {

        static::$DatabaseMaster = Database::getDatabase( $Username, $Password, $Database, $Driver, $Host, $Port );
        return $this;
    }

    /**
     * Database Read Access
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
    private final function registerDatabaseSlave( $Username, $Password, $Database, $Driver, $Host, $Port = null )
    {

        static::$DatabaseSlave[] = Database::getDatabase( $Username, $Password, $Database, $Driver, $Host, $Port );
        return $this;
    }

    /**
     * @param string $TableName
     *
     * @return bool
     */
    protected function dbHasTable( $TableName )
    {

        $SchemaManager = $this->writeData()->getSchemaManager();
        $NameList = $SchemaManager->listTableNames();
        $NameList = array_map( 'strtolower', $NameList );
        return in_array( strtolower( $TableName ), $NameList );
    }

    /**
     * @return IBridgeInterface
     */
    final protected function writeData()
    {

        return static::$DatabaseMaster;
    }

    /**
     * @param string $TableName
     * @param string $ColumnName
     *
     * @return bool
     */
    protected function dbTableHasColumn( $TableName, $ColumnName )
    {

        $SchemaManager = $this->writeData()->getSchemaManager();
        $NameList = array_keys( $SchemaManager->listTableColumns( $TableName ) );
        $NameList = array_map( 'strtolower', $NameList );
        return in_array( strtolower( $ColumnName ), $NameList );
    }
}
