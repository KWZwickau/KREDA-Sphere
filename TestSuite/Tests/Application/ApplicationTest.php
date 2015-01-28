<?php
namespace KREDA\TestSuite\Tests\Application;

use KREDA\Sphere\Application\Assistance\Assistance;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Graduation\Graduation;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Statistic\Statistic;
use KREDA\Sphere\Application\System\System;

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

        $this->checkCodeStyle( new Assistance() );
        $this->checkCodeStyle( new Billing() );
        $this->checkCodeStyle( new Gatekeeper() );
        $this->checkCodeStyle( new Graduation() );
        $this->checkCodeStyle( new Management() );
        $this->checkCodeStyle( new Statistic() );
        $this->checkCodeStyle( new System() );
    }

    /**
     * @param \KREDA\Sphere\Common\AbstractApplication $Application
     */
    private function checkCodeStyle( $Application )
    {

        $Pattern = '!^(register|setup|frontend|service|extension)[a-zA-Z]+$!';
        $MethodList = get_class_methods( $Application );
        foreach ($MethodList as $Method) {
            $this->assertEquals( 1, preg_match( $Pattern, $Method ),
                get_class( $Application ).'::'.$Method."\n".' -> '.$Pattern );
        }
    }
}
