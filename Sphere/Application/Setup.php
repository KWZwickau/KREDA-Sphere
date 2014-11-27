<?php
namespace KREDA\Sphere\Application;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup as ORMSetup;

/**
 * Class Setup
 *
 * @package KREDA\Sphere\Application
 */
abstract class Setup extends Service
{

    /** @var EntityManager $EntityManager */
    protected static $EntityManager = null;
    /** @var null|AbstractSchemaManager $SchemaManager */
    protected static $SchemaManager = null;
    /** @var null|Schema $Schema */
    protected $Schema = null;


    /**
     * @return Schema|null
     */
    protected function loadSchema()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (null === $this->Schema) {

            $this->getDebugger()->addFileLine( __FILE__, __LINE__ );

            $this->Schema = $this->loadSchemaManager()->createSchema();
        }
        return $this->Schema;
    }

    /**
     * @return AbstractSchemaManager|null
     */
    protected function loadSchemaManager()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        if (null === self::$SchemaManager) {

            $this->getDebugger()->addFileLine( __FILE__, __LINE__ );

            self::$SchemaManager = $this->writeData()->getSchemaManager();
        }
        return self::$SchemaManager;
    }


    /**
     * @return EntityManager
     * @throws ORMException
     */
    protected function loadEntityManager()
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
