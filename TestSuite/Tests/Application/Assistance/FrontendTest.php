<?php
namespace KREDA\TestSuite\Tests\Application\Assistance;

use KREDA\TestSuite\Tests\Application\AbstractFrontendTest;

/**
 * Class FrontendTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
class FrontendTest extends AbstractFrontendTest
{

    public function testCodeStyle()
    {

        $Namespace = '\Assistance\Frontend';
        $this->checkMethodName( $Namespace.'\Account' );
        $this->checkMethodName( $Namespace.'\Application' );
        $this->checkMethodName( $Namespace.'\Support' );
    }
}
