<?php
namespace KREDA\Sphere\Common\Database\Schema;

use Doctrine\Common\Cache\ApcCache;
use Doctrine\DBAL\Schema\AbstractSchemaManager as SchemaManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\View;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use KREDA\Sphere\Common\Database\Connection\Connector;
use KREDA\Sphere\Common\Database\Connection\Identifier;
use MOC\V\Component\Database\Component\IBridgeInterface;

/**
 * Class Model
 *
 * @package KREDA\Sphere\Common\Database
 */
class Model
{

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

        $Config = Setup::createAnnotationMetadataConfiguration( array( $EntityPath ) );
        $Config->setQueryCacheImpl( new ApcCache() );
        $Config->setMetadataCacheImpl( new ApcCache() );
        return EntityManager::create( $this->Connection->getConnection(), $Config );
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

        $SchemaManager = $this->getSchemaManager();
        return in_array( $TableName, $SchemaManager->listTableNames() );
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

        return $this->Connection;
    }
}
