<?php
namespace KREDA\TestSuite\Tests\Application;

/**
 * Class AbstractActionTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
abstract class AbstractActionTest extends \PHPUnit_Framework_TestCase implements IAbstractTest
{

    /**
     * @param string $Action
     */
    protected function checkMethodName( $Action )
    {

        $Name = 'get(ClientServiceRoute|EntityManager)|schema(Migration|Table(Create|AddForeignKey))';
        $Prefix = 'getTable|action(Create|Destroy|Add|Remove|Change)|entity';
        $this->checkNamePattern( 'KREDA\Sphere\Application'.$Action, '!^(('.$Name.')|('.$Prefix.')[a-zA-Z]+)$!',
            \ReflectionMethod::IS_PROTECTED );
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
