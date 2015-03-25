<?php
namespace KREDA\Sphere\Common\Database\Schema;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\MemcachedCache;
use Doctrine\DBAL\Schema\AbstractSchemaManager as SchemaManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\View;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\FlashIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OffIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Common\Cache\Type\Memcached;
use KREDA\Sphere\Common\Database\Connection\Connector;
use KREDA\Sphere\Common\Database\Connection\Identifier;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageInfo;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageSuccess;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayout;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutCol;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutGroup;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutRow;
use MOC\V\Component\Database\Component\IBridgeInterface;

/**
 * Class Model
 *
 * @package KREDA\Sphere\Common\Database
 */
class Model
{

    /** @var array $hasViewCache */
    private static $hasViewCache = array();
    /** @var array $hasColumnCache */
    private static $hasColumnCache = array();
    /** @var array $hasTableCache */
    private static $hasTableCache = array();
    /** @var IBridgeInterface $Connection */
    private $Connection = null;
    /** @var array $Protocol */
    private $Protocol = array();

    /**
     * @param Identifier $Identifier
     */
    final public function __construct( Identifier $Identifier )
    {

        $Connector = new Connector();
        $this->Connection = $Connector->getConnection( $Identifier );
    }

    /**
     * @param string $EntityPath
     *
     * @return EntityManager
     * @throws ORMException
     */
    final public function getEntityManager( $EntityPath )
    {

        $MetadataConfiguration = Setup::createAnnotationMetadataConfiguration( array( $EntityPath ) );
        $MetadataConfiguration->setDefaultRepositoryClassName( '\KREDA\Sphere\Common\Database\Schema\EntityRepository' );
        $ConnectionConfig = $this->Connection->getConnection()->getConfiguration();
        if (class_exists( '\Memcached', false )) {
            $Cache = new MemcachedCache();
            $Cache->setMemcached( ( new Memcached() )->getServer() );
            $Cache->setNamespace( $EntityPath );
            $ConnectionConfig->setResultCacheImpl( $Cache );
            $MetadataConfiguration->setQueryCacheImpl( $Cache );
            $MetadataConfiguration->setHydrationCacheImpl( $Cache );
            if (function_exists( 'apc_fetch' )) {
                $MetadataConfiguration->setMetadataCacheImpl( new ApcCache() );
            } else {
                $MetadataConfiguration->setMetadataCacheImpl( new ArrayCache() );
            }
        } else {
            if (function_exists( 'apc_fetch' )) {
                $MetadataConfiguration->setQueryCacheImpl( new ApcCache() );
                $MetadataConfiguration->setMetadataCacheImpl( new ApcCache() );
                $MetadataConfiguration->setHydrationCacheImpl( new ApcCache() );
                $ConnectionConfig->setResultCacheImpl( new ApcCache() );
            } else {
                $MetadataConfiguration->setQueryCacheImpl( new ArrayCache() );
                $MetadataConfiguration->setMetadataCacheImpl( new ArrayCache() );
                $MetadataConfiguration->setHydrationCacheImpl( new ArrayCache() );
                $ConnectionConfig->setResultCacheImpl( new ArrayCache() );
            }
        }
        //$ConnectionConfig->setSQLLogger( new Logger() );
        return EntityManager::create( $this->Connection->getConnection(), $MetadataConfiguration );
    }

    /**
     * @return Schema
     */
    final public function getSchema()
    {

        return $this->getSchemaManager()->createSchema();
    }

    /**
     * @return SchemaManager
     */
    final public function getSchemaManager()
    {

        return $this->Connection->getSchemaManager();
    }

    /**
     * @param string $ViewName
     *
     * @return bool
     */
    final public function hasView( $ViewName )
    {

        if (in_array( $ViewName, self::$hasViewCache )) {
            return true;
        }
        $SchemaManager = $this->getSchemaManager();
        self::$hasViewCache = array_map( function ( View $V ) {

            return $V->getName();
        }, $SchemaManager->listViews() );
        return in_array( $ViewName, self::$hasViewCache );
    }

    /**
     * @param string $TableName
     * @param string $ColumnName
     *
     * @return bool
     */
    final public function hasColumn( $TableName, $ColumnName )
    {

        if (isset( self::$hasColumnCache[$TableName] )) {
            return in_array( $ColumnName, self::$hasColumnCache[$TableName] );
        }
        $SchemaManager = $this->getSchemaManager();
        self::$hasColumnCache[$TableName] = array_map( function ( Column $V ) {

            return $V->getName();
        }, $SchemaManager->listTableColumns( $TableName ) );
        return in_array( $ColumnName, self::$hasColumnCache[$TableName] );
    }

    /**
     * @param string $TableName
     *
     * @return bool
     */
    final public function hasTable( $TableName )
    {

        if (in_array( $TableName, self::$hasTableCache )) {
            return true;
        }
        $SchemaManager = $this->getSchemaManager();
        self::$hasTableCache = $SchemaManager->listTableNames();
        return in_array( $TableName, self::$hasTableCache );
    }

    /**
     * @param string $Item
     */
    final public function addProtocol( $Item )
    {

        if (empty( $this->Protocol )) {
            $this->Protocol[] = '<samp>'.$Item.'</samp>';
        } else {
            $this->Protocol[] = '<div><span class="glyphicon glyphicon-transfer"></span>&nbsp;<samp>'.$Item.'</samp></div>';
        }
    }

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    final public function getProtocol( $Simulate = false )
    {

        if (count( $this->Protocol ) == 1) {
            //$this->Protocol[0] .= '<br/>';
            $Protocol = new MessageSuccess(
                new GridLayout( new GridLayoutGroup( new GridLayoutRow( array(
                    new GridLayoutCol( new OkIcon().'&nbsp'.implode( '', $this->Protocol ), 9 ),
                    new GridLayoutCol( new OffIcon().'&nbsp;Kein Update notwendig', 3 )
                ) ) ) )
            );
        } else {
            //$this->Protocol[0] .= '<hr/>';
            $Protocol = new MessageInfo(
                new GridLayout( new GridLayoutGroup( new GridLayoutRow( array(
                    new GridLayoutCol( new FlashIcon().'&nbsp;'.implode( '', $this->Protocol ), 9 ),
                    new GridLayoutCol(
                        ( $Simulate
                            ? new WarningIcon().'&nbsp;Update notwendig'
                            : new OkIcon().'&nbsp;Update durchgeführt'
                        ), 3 )
                ) ) ) )
            );
        }
        $this->Protocol = array();

        return $Protocol;
    }

    /**
     * @return IBridgeInterface
     */
    final public function getConnection()
    {

        return $this->Connection;
    }
}
