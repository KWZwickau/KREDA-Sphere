<?php
namespace KREDA\TestSuite\Tests\Application\System;

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

        $Namespace = '\System\Service';
        $this->checkMethodName( $Namespace.'\Protocol\EntityAction' );
    }
}
