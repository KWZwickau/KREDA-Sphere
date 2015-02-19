<?php
namespace KREDA\TestSuite\Tests\Application;

/**
 * Class AbstractApplicationTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
abstract class AbstractApplicationTest extends \PHPUnit_Framework_TestCase
{

    public function testAbstractApplication()
    {

        /** @var \KREDA\Sphere\Common\AbstractApplication $MockApplication */
        $MockApplication = $this->getMockForAbstractClass( 'KREDA\Sphere\Common\AbstractApplication' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractExtension', $MockApplication );
        $this->assertInstanceOf( 'KREDA\Sphere\IApplicationInterface', $MockApplication );
    }

    /**
     * $this->checkMethodName( '\ApplicationDirectory\ClassFile' );
     *
     * @return void
     */
    abstract public function testCodeStyle();

    /**
     * @param string $Application
     */
    protected function checkMethodName( $Application )
    {

        $Name = 'registerApplication';
        $Prefix = 'registerApplication|setup|frontend|service|extension';
        $this->checkNamePattern( 'KREDA\Sphere\Application'.$Application, '!^(('.$Name.')|('.$Prefix.')[a-zA-Z]+)$!',
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
