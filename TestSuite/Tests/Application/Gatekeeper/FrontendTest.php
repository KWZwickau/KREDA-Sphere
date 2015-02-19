<?php
namespace KREDA\TestSuite\Tests\Application\Gatekeeper;

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

        $Namespace = '\Gatekeeper\Frontend';
        $this->checkMethodName( $Namespace.'\Authentication\Authentication' );
        $this->checkMethodName( $Namespace.'\Authentication\SignIn' );
        $this->checkMethodName( $Namespace.'\Authentication\SignOut' );
        $this->checkMethodName( $Namespace.'\MyAccount\MyAccount' );
    }
}
