<?php
namespace KREDA\TestSuite\Tests\Application;

use KREDA\Sphere\Application\Gatekeeper\Service\Access;

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

    public function testCodeStyle()
    {

        $this->checkCodeStyle( new Access() );
    }

    /**
     * @param \KREDA\Sphere\Common\AbstractService $Service
     */
    private function checkCodeStyle( $Service )
    {

        $Pattern = '!^(getApi|(set|get)DatabaseHandler|getConsumerSuffix|(setup|schema|execute|entity|check|__|extension)[a-zA-Z]+)$!';
        $MethodList = get_class_methods( $Service );
        foreach ($MethodList as $Method) {
            $this->assertEquals( 1, preg_match( $Pattern, $Method ),
                get_class( $Service ).'::'.$Method."\n".' -> '.$Pattern );
        }
    }
}
