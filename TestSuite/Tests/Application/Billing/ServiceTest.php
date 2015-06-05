<?php
namespace KREDA\TestSuite\Tests\Application\Billing;

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

        $Namespace = '\Billing\Service';
        $this->checkMethodName( $Namespace.'\Account' );
        $this->checkMethodName( $Namespace.'\Commodity' );
        $this->checkMethodName( $Namespace.'\Invoice' );
    }
}
