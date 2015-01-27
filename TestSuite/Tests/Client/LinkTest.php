<?php
namespace KREDA\TestSuite\Tests\Client;

use KREDA\Sphere\Client\Component\Parameter\Repository\Link\IconParameter;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\NameParameter;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\UrlParameter;

/**
 * Class LinkTest
 *
 * @package KREDA\TestSuite\Tests\Client
 */
class LinkTest extends \PHPUnit_Framework_TestCase
{

    public function testAbstractLink()
    {

        /** @var \KREDA\Sphere\Client\Component\Parameter\Repository\AbstractLink $MockLink */
        $MockLink = $this->getMockForAbstractClass( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractLink' );

        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractLink', $MockLink );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $MockLink );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $MockLink );
        $this->assertInternalType( 'string', $MockLink->getValue() );
    }

    public function testParameter()
    {

        /** @var \KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon $MockIcon */
        $MockIcon = $this->getMockForAbstractClass( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon' );

        $Parameter = new IconParameter( $MockIcon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractLink', $Parameter );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Parameter );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Parameter );
        $this->assertInternalType( 'string', $Parameter->getValue() );

        try {
            new NameParameter( null );
        } catch( \Exception $E ) {
            $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Exception\ComponentException', $E );
        }
        try {
            new NameParameter( 'Invalid Name - !!!' );
        } catch( \Exception $E ) {
            $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Exception\ComponentException', $E );
        }
        $Parameter = new NameParameter( 'Valid Name' );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractLink', $Parameter );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Parameter );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Parameter );
        $this->assertInternalType( 'string', $Parameter->getValue() );

        try {
            new UrlParameter( null );
        } catch( \Exception $E ) {
            $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Exception\ComponentException', $E );
        }
        try {
            new UrlParameter( 'Invalid Name - !!!' );
        } catch( \Exception $E ) {
            $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Exception\ComponentException', $E );
        }
        $Parameter = new UrlParameter( 'Valid/Name' );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractLink', $Parameter );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Parameter );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Parameter );
        $this->assertInternalType( 'string', $Parameter->getValue() );
    }

}
