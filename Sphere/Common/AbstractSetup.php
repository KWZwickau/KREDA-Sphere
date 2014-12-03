<?php
namespace KREDA\Sphere\Common;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup as ORMSetup;

/**
 * Class AbstractSetup
 *
 * @package KREDA\Sphere\Application
 */
abstract class AbstractSetup extends AbstractDatabase
{

    /** @var EntityManager $EntityManager */
    private static $EntityManager = null;
    /** @var null|AbstractSchemaManager $SchemaManager */
    private static $SchemaManager = null;
    /** @var null|Schema $Schema */
    private $Schema = null;
    /** @var array $InstallProtocol */
    private $InstallProtocol = array();

    /**
     * @param string $Item
     *
     * @return array
     */
    final public function addInstallProtocol( $Item )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (empty( $this->InstallProtocol )) {
            $this->InstallProtocol[] = '<samp>'.$Item.'</samp>';
        } else {
            $this->InstallProtocol[] = '<div><span class="glyphicon glyphicon-transfer"></span>&nbsp;<samp>'.$Item.'</samp></div>';
        }
    }

    /**
     * @param bool $Simulate
     *
     * @return string
     */
    final public function getInstallProtocol( $Simulate = false )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (count( $this->InstallProtocol ) == 1) {
            $this->InstallProtocol[0] .= '<br/>';
            return '<div class="alert alert-success text-left">'
            .'<span class="glyphicon glyphicon-ok"></span>&nbsp;'
            .implode( '', $this->InstallProtocol )
            .'<hr/><span class="glyphicon glyphicon-refresh"></span>&nbsp;Kein Update notwendig'
            .'</div>';
        }
        $this->InstallProtocol[0] .= '<hr/>';
        return '<div class="alert alert-info text-left">'
        .'<span class="glyphicon glyphicon-flash"></span>&nbsp;'
        .implode( '', $this->InstallProtocol )
        .( $Simulate
            ? '<hr/><span class="glyphicon glyphicon-exclamation-sign"></span>&nbsp;Update notwendig'
            : '<hr/><span class="glyphicon glyphicon-saved"></span>&nbsp;Update durchgeführt'
        )
        .'</div>';
    }

    /**
     * @return Schema|null
     */
    final protected function getSchema()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (null === $this->Schema) {

            $this->getDebugger()->addFileLine( __FILE__, __LINE__ );

            $this->Schema = $this->getSchemaManager()->createSchema();
        }
        return $this->Schema;
    }

    /**
     * @return AbstractSchemaManager|null
     */
    final protected function getSchemaManager()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (null === self::$SchemaManager) {

            $this->getDebugger()->addFileLine( __FILE__, __LINE__ );

            self::$SchemaManager = $this->readData()->getSchemaManager();
        }
        return self::$SchemaManager;
    }

    /**
     * @return EntityManager
     * @throws ORMException
     */
    final protected function getEntityManager()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (null === self::$EntityManager) {

            $this->getDebugger()->addFileLine( __FILE__, __LINE__ );

            $Config = ORMSetup::createAnnotationMetadataConfiguration( array( __DIR__.'/Schema' ) );
            $Config->setQueryCacheImpl( new ArrayCache() );
            $Config->setMetadataCacheImpl( new ArrayCache() );
            self::$EntityManager = EntityManager::create( $this->readData()->getConnection(), $Config );
        }
        return self::$EntityManager;
    }
}
