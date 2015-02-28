<?php
namespace KREDA\TestSuite\Tests\Application;

/**
 * Class AbstractSchemaTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
abstract class AbstractSchemaTest extends \PHPUnit_Framework_TestCase implements IAbstractTest
{

    /**
     * @param string $Schema
     */
    protected function checkMethodName( $Schema )
    {

        $Name = 'get(Api|ConsumerSuffix|DatabaseHandler|ClientServiceRoute|EntityManager)|setupDatabase(Schema|Content)|setDatabaseHandler|schema(Migration|Table(Create|AddForeignKey))';
        $Prefix = 'setTable|getTable|extension';
        $this->checkNamePattern( 'KREDA\Sphere\Application'.$Schema, '!^(('.$Name.')|('.$Prefix.')[a-zA-Z]+)$!',
            \ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED | \ReflectionMethod::IS_PRIVATE );
    }

    /**
     * @param string $Class
     * @param string $Pattern
     * @param int    $Realm
     */
    private function checkNamePattern( $Class, $Pattern, $Realm = \ReflectionMethod::IS_PUBLIC )
    {

        $Class = new \ReflectionClass( $Class );
        $MethodList = $Class->getMethods( $Realm );
        /** @var \ReflectionMethod $Method */
        foreach ($MethodList as $Method) {
            $this->assertEquals( 1, preg_match( $Pattern, $Method->getShortName(), $Result ),
                $Class->getName().'::'.$Method->getShortName()."\n".' -> '.$Pattern );
        }
    }
}
