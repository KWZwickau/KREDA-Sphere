<?php
namespace KREDA\Sphere\Application\System\Database\Handler;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\DBAL\Schema\AbstractSchemaManager as SchemaManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\View;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use KREDA\Sphere\Application\System\Database\Connector\Connector;
use KREDA\Sphere\Application\System\Database\Identifier;
use KREDA\Sphere\Common\AbstractAddOn;
use MOC\V\Component\Database\Component\IBridgeInterface;

/**
 * Class Model
 *
 * @package KREDA\Sphere\Application\System\Database\Handler
 */
class Model extends AbstractAddOn
{

    /** @var null|SchemaManager */
    private static $SchemaManager = null;
    /** @var null|EntityManager */
    private static $EntityManager = null;
    /** @var IBridgeInterface $Connection */
    private $Connection = null;
    /** @var array $Protocol */
    private $Protocol = array();

    /**
     * @param Identifier $Identifier
     */
    final public function __construct( Identifier $Identifier )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->Connection = Connector::getInstance()->getConnection( $Identifier );
    }

    /**
     * @param string $EntityPath
     *
     * @return EntityManager
     * @throws ORMException
     */
    final public function getEntityManager( $EntityPath )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (null === self::$EntityManager) {
            $this->getDebugger()->addFileLine( __FILE__, __LINE__ );
            $Config = Setup::createAnnotationMetadataConfiguration( array( $EntityPath ) );
            $Config->setQueryCacheImpl( new ApcCache() );
            $Config->setMetadataCacheImpl( new ApcCache() );
            self::$EntityManager = EntityManager::create( $this->Connection->getConnection(), $Config );
        }
        return self::$EntityManager;
    }

    /**
     * @return Schema
     */
    final public function getSchema()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->getSchemaManager()->createSchema();
    }

    /**
     * @return SchemaManager
     */
    final public function getSchemaManager()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (null === self::$SchemaManager) {
            $this->getDebugger()->addFileLine( __FILE__, __LINE__ );
            self::$SchemaManager = $this->Connection->getSchemaManager();
        }
        return self::$SchemaManager;
    }

    /**
     * @param string $ViewName
     *
     * @return bool
     */
    final public function hasView( $ViewName )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $SchemaManager = $this->getSchemaManager();
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
    final public function hasColumn( $TableName, $ColumnName )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $SchemaManager = $this->getSchemaManager();
        $NameList = array_map( function ( Column $V ) {

            return $V->getName();
        }, $SchemaManager->listTableColumns( $TableName ) );
        return in_array( $ColumnName, $NameList );
    }

    /**
     * @param string $TableName
     *
     * @return bool
     */
    final public function hasTable( $TableName )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $SchemaManager = $this->getSchemaManager();
        return in_array( $TableName, $SchemaManager->listTableNames() );
    }

    /**
     * @param string $Item
     */
    final public function addProtocol( $Item )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

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

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (count( $this->Protocol ) == 1) {
            $this->Protocol[0] .= '<br/>';
            $Protocol = '<div class="alert alert-success text-left">'
                .'<span class="glyphicon glyphicon-ok"></span>&nbsp;'
                .implode( '', $this->Protocol )
                .'<hr/><span class="glyphicon glyphicon-refresh"></span>&nbsp;Kein Update notwendig'
                .'</div>';
        } else {
            $this->Protocol[0] .= '<hr/>';
            $Protocol = '<div class="alert alert-info text-left">'
                .'<span class="glyphicon glyphicon-flash"></span>&nbsp;'
                .implode( '', $this->Protocol )
                .( $Simulate
                    ? '<hr/><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;Update notwendig'
                    : '<hr/><span class="glyphicon glyphicon-saved"></span>&nbsp;Update durchgeführt'
                )
                .'</div>';
        }
        $this->Protocol = array();

        return $Protocol;
    }

    /**
     * @return IBridgeInterface
     */
    final public function getConnection()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return $this->Connection;
    }
}
