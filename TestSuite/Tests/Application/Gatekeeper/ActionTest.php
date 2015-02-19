<?php
namespace KREDA\TestSuite\Tests\Application\Gatekeeper;

use KREDA\TestSuite\Tests\Application\AbstractActionTest;

/**
 * Class ActionTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
class ActionTest extends AbstractActionTest
{

    public function testCodeStyle()
    {

        $Namespace = '\Gatekeeper\Service';
        $this->checkMethodName( $Namespace.'\Access\EntityAction' );
        $this->checkMethodName( $Namespace.'\Account\EntityAction' );
        $this->checkMethodName( $Namespace.'\Consumer\EntityAction' );
        $this->checkMethodName( $Namespace.'\Token\EntityAction' );
    }
}
