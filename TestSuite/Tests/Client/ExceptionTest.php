<?php
namespace KREDA\TestSuite\Tests\Client;

use KREDA\Sphere\Client\Component\Exception\ComponentException;
use KREDA\Sphere\Client\Exception\ClientException;

/**
 * Class ExceptionTest
 *
 * @package KREDA\TestSuite\Tests\Client
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase
{

    public function testException()
    {

        try {
            throw new ClientException();
        } catch( \Exception $E ) {
            $this->assertInstanceOf( 'KREDA\Sphere\Client\Exception\ClientException', $E );
            $this->assertInstanceOf( '\Exception', $E );
        }

        try {
            throw new ComponentException();
        } catch( \Exception $E ) {
            $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Exception\ComponentException', $E );
            $this->assertInstanceOf( 'KREDA\Sphere\Client\Exception\ClientException', $E );
        }
    }

}
