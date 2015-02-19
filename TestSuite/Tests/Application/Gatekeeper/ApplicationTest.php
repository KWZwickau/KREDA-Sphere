<?php
namespace KREDA\TestSuite\Tests\Application\Gatekeeper;

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

        $this->checkMethodName( '\Gatekeeper\Gatekeeper' );
        $this->checkMethodName( '\Gatekeeper\Module\Authentication' );
        $this->checkMethodName( '\Gatekeeper\Module\MyAccount' );
    }
}
