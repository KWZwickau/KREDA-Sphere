<?php
namespace KREDA\TestSuite\Tests\Application\Billing;

use KREDA\TestSuite\Tests\Application\AbstractApplicationTest;

/**
 * Class ApplicationTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
class ApplicationTest extends AbstractApplicationTest
{

    public function testCodeStyle()
    {

        $this->checkMethodName( '\Billing\Billing' );
    }
}
