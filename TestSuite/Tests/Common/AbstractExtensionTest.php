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
        $this->assertInstanceOf(
            'KREDA\Sphere\Common\AbstractExtension',
            $MockExtension
        );

        $this->assertInstanceOf(
            'KREDA\Sphere\Common\Extension\Debugger',
            $MockExtension->extensionDebugger()
        );

        $this->assertInstanceOf(
            'Markdownify\Converter',
            $MockExtension->extensionMarkdownify()
        );

        $this->assertInstanceOf(
            'KREDA\Sphere\Common\Extension\ModHex',
            $Extension = $MockExtension->extensionModHex( 'ccccccdilkui' )
        );
        $this->assertEquals( 'ccccccdilkui', $Extension->getIdentifier() );
        $this->assertEquals( '2599399', $Extension->getSerialNumber() );

        $this->assertInstanceOf(
            'KREDA\Sphere\Common\Extension\ModHex',
            $Extension = $MockExtension->extensionModHex( 'Error' )
        );
        $this->assertEquals( 'Error', $Extension->getIdentifier() );
        $this->assertEquals( '0', $Extension->getSerialNumber() );

        $this->assertInstanceOf( 'Github\Client', $MockExtension->extensionGitHub() );
    }

    public function testExtensionCodeStyle()
    {

        $this->checkMethodName( '\AbstractExtension' );
    }

    /**
     * @param string $Extension
     */
    private function checkMethodName( $Extension )
    {

        $Name = '#';
        $Prefix = 'extension';
        $this->checkNamePattern( 'KREDA\Sphere\Common'.$Extension, '!^(('.$Name.')|('.$Prefix.')[a-zA-Z]+)$!',
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
            $this->assertTrue( $Method->isStatic(),
                $Class->getName().'::'.$Method->getShortName()."\n".' must be static '
            );
            $this->assertTrue( $Method->isFinal(),
                $Class->getName().'::'.$Method->getShortName()."\n".' must be final '
            );
        }
    }
}
