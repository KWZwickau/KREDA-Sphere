<?php
namespace KREDA\TestSuite\Tests\Application;

use KREDA\Sphere\Common\AbstractService;

/**
 * Class ServiceTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
class ServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testAbstractService()
    {

        /** @var \KREDA\Sphere\Common\AbstractService $MockService */
        $MockService = $this->getMockForAbstractClass( 'KREDA\Sphere\Common\AbstractService' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractExtension', $MockService );
        $this->assertInstanceOf( 'KREDA\Sphere\IServiceInterface', $MockService );
    }

    public function testServiceCodeStyle()
    {

        /**
         * Gatekeeper
         */
        $Namespace = 'KREDA\Sphere\Application\Gatekeeper\Service';
        $this->checkServiceMethodName( $Namespace.'\Access' );
        $this->checkServiceMethodName( $Namespace.'\Account' );
        $this->checkServiceMethodName( $Namespace.'\Consumer' );
        $this->checkServiceMethodName( $Namespace.'\Token' );
        /**
         * Management
         */
        $Namespace = 'KREDA\Sphere\Application\Management\Service';
        $this->checkServiceMethodName( $Namespace.'\Address' );
        $this->checkServiceMethodName( $Namespace.'\Education' );
        $this->checkServiceMethodName( $Namespace.'\Person' );
        /**
         * Graduation
         */
        $Namespace = 'KREDA\Sphere\Application\Graduation\Service';
        $this->checkServiceMethodName( $Namespace.'\Grade' );
        $this->checkServiceMethodName( $Namespace.'\Score' );
        $this->checkServiceMethodName( $Namespace.'\Weight' );
        /**
         * System
         */
        $Namespace = 'KREDA\Sphere\Application\System\Service';
        $this->checkServiceMethodName( $Namespace.'\Database' );
        $this->checkServiceMethodName( $Namespace.'\Protocol' );

    }

    /**
     * @param string $Service
     */
    private function checkServiceMethodName( $Service )
    {

        $Name = '__construct|get(Api|ConsumerSuffix|DatabaseHandler)|setDatabaseHandler|setupDatabase(Schema|Content)';
        $Prefix = 'entity|checkIs|getTable|execute(Create|Destroy|Add|Remove|Change|Action)|extension';
        $this->checkMethodName( $Service, '!^(('.$Name.')|('.$Prefix.')[a-zA-Z]+)$!',
            \ReflectionMethod::IS_PUBLIC );
    }

    /**
     * @param string $Class
     * @param string $Pattern
     * @param int    $Realm
     */
    private function checkMethodName( $Class, $Pattern, $Realm = \ReflectionMethod::IS_PUBLIC )
    {

        $Class = new \ReflectionClass( $Class );
        $MethodList = $Class->getMethods( $Realm );
        /** @var \ReflectionMethod $Method */
        foreach ($MethodList as $Method) {
            $this->assertEquals( 1, preg_match( $Pattern, $Method->getShortName(), $Result ),
                $Class->getName().'::'.$Method->getShortName()."\n".' -> '.$Pattern );

            if (!$Class->isAbstract()) {
                /** @var AbstractService $Object */
                $Object = $Class->newInstance();

                if (in_array( 'getApi', $Result )) {
                    $this->assertInstanceOf( '\KREDA\Sphere\Common\AbstractService',
                        $Object->{$Method->getShortName()}() );
                }
                if (in_array( 'getDatabaseHandler', $Result )) {
                    $this->assertInstanceOf( 'KREDA\Sphere\Common\Database\Handler',
                        $Object->{$Method->getShortName()}() );
                }
                if (in_array( 'setupDatabaseContent', $Result )) {
                    $Object->{$Method->getShortName()}();
                }
                if (in_array( 'getTable', $Result )) {
                    $this->assertInstanceOf( '\Doctrine\DBAL\Schema\Table',
                        $Object->{$Method->getShortName()}() );
                }
                if (in_array( 'entity', $Result )) {
                    $this->checkEntity( $Object, $Method );
                }
            }
        }
    }

    /**
     * @param AbstractService   $Object
     * @param \ReflectionMethod $Method
     */
    private function checkEntity( AbstractService $Object, \ReflectionMethod $Method )
    {

        if (preg_match( '!^entity([a-zA-Z]+)All$!', $Method->getShortName() )) {
            $Result = $Object->{$Method->getShortName()}();
            if ($Result) {
                if (count( $Result )) {
                    $this->assertContainsOnlyInstancesOf( '\KREDA\Sphere\Common\AbstractEntity', $Result );
                } else {
                    $this->assertFalse( $Result );
                }
            } else {
                $this->assertInstanceOf( '\KREDA\Sphere\Common\AbstractEntity', $Result );
            }
        }
        if (preg_match( '!^entity([a-zA-Z]+)ById$!', $Method->getShortName() )) {
            $Result = $Object->{$Method->getShortName()}( 0 );
            $this->assertFalse( $Result );
            $Result = $Object->{$Method->getShortName()}( 1 );
            if ($Result) {
                $this->assertInstanceOf( '\KREDA\Sphere\Common\AbstractEntity', $Result );
            } else {
                $this->assertFalse( $Result );
            }
        }
    }

    public function testSchemaCodeStyle()
    {

        /**
         * Gatekeeper
         */
        $Namespace = 'KREDA\Sphere\Application\Gatekeeper\Service';
        $this->checkSchemaMethodName( $Namespace.'\Access\EntitySchema' );
        $this->checkSchemaMethodName( $Namespace.'\Account\EntitySchema' );
        $this->checkSchemaMethodName( $Namespace.'\Consumer\EntitySchema' );
        $this->checkSchemaMethodName( $Namespace.'\Token\EntitySchema' );
        /**
         * Management
         */
        $Namespace = 'KREDA\Sphere\Application\Management\Service';
        $this->checkSchemaMethodName( $Namespace.'\Address\EntitySchema' );
        $this->checkSchemaMethodName( $Namespace.'\Education\EntitySchema' );
        $this->checkSchemaMethodName( $Namespace.'\Person\EntitySchema' );
    }

    /**
     * @param string $Schema
     */
    private function checkSchemaMethodName( $Schema )
    {

        $Name = 'get(Api|ConsumerSuffix|DatabaseHandler|ClientServiceRoute|EntityManager)|setupDatabase(Schema|Content)|setDatabaseHandler';
        $Prefix = 'setTable|getTable|extension';
        $this->checkMethodName( $Schema, '!^(('.$Name.')|('.$Prefix.')[a-zA-Z]+)$!',
            \ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_PRIVATE );
    }

    public function testActionCodeStyle()
    {

        /**
         * Gatekeeper
         */
        $Namespace = 'KREDA\Sphere\Application\Gatekeeper\Service';
        $this->checkActionMethodName( $Namespace.'\Access\EntityAction' );
        $this->checkActionMethodName( $Namespace.'\Account\EntityAction' );
        $this->checkActionMethodName( $Namespace.'\Consumer\EntityAction' );
        $this->checkActionMethodName( $Namespace.'\Token\EntityAction' );
        /**
         * Management
         */
        $Namespace = 'KREDA\Sphere\Application\Management\Service';
        $this->checkActionMethodName( $Namespace.'\Address\EntityAction' );
        $this->checkActionMethodName( $Namespace.'\Education\EntityAction' );
        $this->checkActionMethodName( $Namespace.'\Person\EntityAction' );
    }

    /**
     * @param string $Action
     */
    private function checkActionMethodName( $Action )
    {

        $Name = 'get(ClientServiceRoute|EntityManager)';
        $Prefix = 'getTable|action(Create|Destroy|Add|Remove|Change)|entity';
        $this->checkMethodName( $Action, '!^(('.$Name.')|('.$Prefix.')[a-zA-Z]+)$!',
            \ReflectionMethod::IS_PROTECTED );
    }
}
