<?php
namespace KREDA\TestSuite\Tests\Common;

/**
 * Class AbstractExtensionTest
 *
 * @package KREDA\TestSuite\Tests\Common
 */
class AbstractExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testAbstractExtension()
    {

        /** @var \KREDA\Sphere\Common\AbstractExtension $MockExtension */
        $MockExtension = $this->getMockForAbstractClass( 'KREDA\Sphere\Common\AbstractExtension' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractExtension', $MockExtension );

        $this->assertInstanceOf( 'KREDA\Sphere\Common\Extension\Debugger', $MockExtension->extensionDebugger() );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\Extension\ModHex', $MockExtension->extensionModHex( 'Dummy' ) );
    }

    public function testExtensionCodeStyle()
    {

        $Namespace = 'KREDA\Sphere\Application\Assistance\Extension';
        $this->checkExtensionMethodName( $Namespace.'\Account' );
    }

    /**
     * @param string $Extension
     */
    private function checkExtensionMethodName( $Extension )
    {

        $Name = '#';
        $Prefix = 'extension';
        $this->checkMethodName( $Extension, '!^(('.$Name.')|('.$Prefix.')[a-zA-Z]+)$!',
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
            $this->assertTrue( $Method->isStatic(),
                $Class->getName().'::'.$Method->getShortName()."\n".' must be static '
            );
            $this->assertTrue( $Method->isFinal(),
                $Class->getName().'::'.$Method->getShortName()."\n".' must be final '
            );
        }
    }
}
