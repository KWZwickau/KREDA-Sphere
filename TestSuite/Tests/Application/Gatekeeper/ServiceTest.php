<?php
namespace KREDA\TestSuite\Tests\Application\Gatekeeper;

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

        $Namespace = '\Gatekeeper\Service';
        $this->checkMethodName( $Namespace.'\Access' );
        $this->checkMethodName( $Namespace.'\Account' );
        $this->checkMethodName( $Namespace.'\Consumer' );
        $this->checkMethodName( $Namespace.'\Token' );
    }
}
