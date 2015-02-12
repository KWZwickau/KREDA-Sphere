<?php
namespace KREDA\TestSuite\Tests\Application;

/**
 * Class FrontendTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
class FrontendTest extends \PHPUnit_Framework_TestCase
{

    public function testAbstractFrontend()
    {

        /** @var \KREDA\Sphere\Common\AbstractFrontend $MockFrontend */
        $MockFrontend = $this->getMockForAbstractClass( 'KREDA\Sphere\Common\AbstractFrontend' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractExtension', $MockFrontend );
    }

    public function testFrontendCodeStyle()
    {

        /**
         * Assistance
         */
        $Namespace = 'KREDA\Sphere\Application\Assistance\Frontend';
        $this->checkFrontendMethodName( $Namespace.'\Account' );
        $this->checkFrontendMethodName( $Namespace.'\Application' );
        $this->checkFrontendMethodName( $Namespace.'\Support' );
        /**
         * Gatekeeper
         */
        $Namespace = 'KREDA\Sphere\Application\Gatekeeper\Frontend';
        $this->checkFrontendMethodName( $Namespace.'\Authentication\Authentication' );
        $this->checkFrontendMethodName( $Namespace.'\Authentication\SignIn' );
        $this->checkFrontendMethodName( $Namespace.'\Authentication\SignOut' );
        $this->checkFrontendMethodName( $Namespace.'\MyAccount\MyAccount' );
        /**
         * Billing
         */
        $Namespace = 'KREDA\Sphere\Application\Billing\Frontend';
        $this->checkFrontendMethodName( $Namespace.'\Summary\Summary' );
        /**
         * System
         */
        $Namespace = 'KREDA\Sphere\Application\System\Frontend';
        $this->checkFrontendMethodName( $Namespace.'\Authorization\Authorization' );
        $this->checkFrontendMethodName( $Namespace.'\Token\Token' );
        $this->checkFrontendMethodName( $Namespace.'\Cache' );
        $this->checkFrontendMethodName( $Namespace.'\Consumer' );
        $this->checkFrontendMethodName( $Namespace.'\Database' );
        $this->checkFrontendMethodName( $Namespace.'\Protocol' );
        $this->checkFrontendMethodName( $Namespace.'\Update' );

    }

    /**
     * @param string $Frontend
     */
    private function checkFrontendMethodName( $Frontend )
    {

        $Name = 'getContent|__toString';
        $Prefix = 'stage|extension';
        $this->checkMethodName( $Frontend, '!^(('.$Name.')|('.$Prefix.')[a-zA-Z]+)$!',
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
