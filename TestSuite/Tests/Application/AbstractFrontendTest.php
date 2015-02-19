<?php
namespace KREDA\TestSuite\Tests\Application;

/**
 * Class AbstractFrontendTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
abstract class AbstractFrontendTest extends \PHPUnit_Framework_TestCase
{

    public function testAbstractFrontend()
    {

        /** @var \KREDA\Sphere\Common\AbstractFrontend $MockFrontend */
        $MockFrontend = $this->getMockForAbstractClass( 'KREDA\Sphere\Common\AbstractFrontend' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractExtension', $MockFrontend );
    }

    /**
     * $this->checkMethodName( '\ApplicationDirectory\ClassFile' );
     *
     * @return void
     */
    abstract public function testCodeStyle();

    /**
     * @param string $Frontend
     */
    protected function checkMethodName( $Frontend )
    {

        $Name = 'getContent|__toString';
        $Prefix = 'stage|extension';
        $this->checkNamePattern( 'KREDA\Sphere\Application'.$Frontend, '!^(('.$Name.')|('.$Prefix.')[a-zA-Z]+)$!',
            \ReflectionMethod::IS_PUBLIC );
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
