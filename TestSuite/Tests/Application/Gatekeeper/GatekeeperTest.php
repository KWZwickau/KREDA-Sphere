<?php
namespace KREDA\TestSuite\Tests\Application\Gatekeeper;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;

/**
 * Class GatekeeperTest
 *
 * @package KREDA\TestSuite\Tests\Application\Gatekeeper
 */
class GatekeeperTest extends \PHPUnit_Framework_TestCase
{

    public function testCodeStyle()
    {

        $Application = new Gatekeeper();
        $MethodList = get_class_methods( $Application );
        foreach ($MethodList as $Method) {
            $this->assertEquals( 1, preg_match( '!^(register|setup|frontend|service|extension)[a-zA-Z_]+$!', $Method ),
                $Method );
        }
    }
}
