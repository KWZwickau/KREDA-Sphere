<?php
namespace KREDA\TestSuite\Tests\Application\Graduation;

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

        $Namespace = '\Graduation\Service';
        $this->checkMethodName( $Namespace.'\Grade\EntityAction' );
        $this->checkMethodName( $Namespace.'\Score\EntityAction' );
        $this->checkMethodName( $Namespace.'\Weight\EntityAction' );
    }
}
