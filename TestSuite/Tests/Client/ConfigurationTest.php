<?php
namespace KREDA\TestSuite\Tests\Client;

use KREDA\Sphere\Client\Configuration;

/**
 * Class ConfigurationTest
 *
 * @package KREDA\TestSuite\Tests\Client
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{

    public function testConfiguration()
    {

        /** @var \MOC\V\Component\Router\Component\IBridgeInterface $MockRouter */
        $MockRouter = $this->getMockForAbstractClass( 'MOC\V\Component\Router\Component\IBridgeInterface' );
        /** @var \KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient $MockNavigation */
        $MockNavigation = $this->getMockForAbstractClass( 'KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient' );

        $Object = new Configuration( $MockRouter, $MockNavigation );

        $this->assertInstanceOf( 'KREDA\Sphere\Client\Configuration', $Object );
        $this->assertInstanceOf( 'MOC\V\Component\Router\Component\IBridgeInterface', $Object->getClientRouter() );

        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient',
            $Navigation = $Object->getClientNavigation() );
        $this->assertInternalType( 'string', $Navigation->getContent() );

        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelModule',
            $Navigation = $Object->getModuleNavigation() );
        $this->assertInternalType( 'string', $Navigation->getContent() );

        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelApplication',
            $Navigation = $Object->getApplicationNavigation() );
        $this->assertInternalType( 'string', $Navigation->getContent() );

        $this->assertInternalType( 'boolean', $Object->hasApplicationNavigation() );
        $this->assertInternalType( 'boolean', $Object->hasModuleNavigation() );
    }

}
