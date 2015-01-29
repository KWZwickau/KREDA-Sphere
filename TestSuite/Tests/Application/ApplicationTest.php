<?php
namespace KREDA\TestSuite\Tests\Application;

/**
 * Class ApplicationTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{

    public function testAbstractApplication()
    {

        /** @var \KREDA\Sphere\Common\AbstractApplication $MockApplication */
        $MockApplication = $this->getMockForAbstractClass( 'KREDA\Sphere\Common\AbstractApplication' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractExtension', $MockApplication );
        $this->assertInstanceOf( 'KREDA\Sphere\IApplicationInterface', $MockApplication );
    }

    public function testCodeStyle()
    {

        $Namespace = 'KREDA\Sphere\Application';
        $this->checkApplicationMethodName( $Namespace.'\Assistance\Assistance' );
        $this->checkApplicationMethodName( $Namespace.'\Billing\Billing' );
        $this->checkApplicationMethodName( $Namespace.'\Gatekeeper\Gatekeeper' );
        $this->checkApplicationMethodName( $Namespace.'\Graduation\Graduation' );
        $this->checkApplicationMethodName( $Namespace.'\Management\Management' );
        $this->checkApplicationMethodName( $Namespace.'\Statistic\Statistic' );
        $this->checkApplicationMethodName( $Namespace.'\System\System' );
    }

    /**
     * @param string $Application
     */
    private function checkApplicationMethodName( $Application )
    {

        $Name = 'registerApplication';
        $Prefix = 'registerApplication|setup|frontend|service|extension';
        $this->checkMethodName( $Application, '!^(('.$Name.')|('.$Prefix.')[a-zA-Z]+)$!',
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
        }
    }
}
