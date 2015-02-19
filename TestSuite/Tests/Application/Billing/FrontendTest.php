<?php
namespace KREDA\TestSuite\Tests\Application\Billing;

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

        $Namespace = '\Billing\Frontend';
        $this->checkMethodName( $Namespace.'\Summary\Summary' );
    }
}
