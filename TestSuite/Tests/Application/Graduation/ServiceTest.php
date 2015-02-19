<?php
namespace KREDA\TestSuite\Tests\Application\Graduation;

use KREDA\TestSuite\Tests\Application\AbstractServiceTest;

/**
 * Class ServiceTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
class ServiceTest extends AbstractServiceTest
{

    public function testCodeStyle()
    {

        $Namespace = '\Graduation\Service';
        $this->checkMethodName( $Namespace.'\Grade' );
        $this->checkMethodName( $Namespace.'\Score' );
        $this->checkMethodName( $Namespace.'\Weight' );
    }
}
