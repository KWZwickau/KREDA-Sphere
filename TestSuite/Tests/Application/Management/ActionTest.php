<?php
namespace KREDA\TestSuite\Tests\Application\Management;

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

        $Namespace = '\Management\Service';
        $this->checkMethodName( $Namespace.'\Address\EntityAction' );
        $this->checkMethodName( $Namespace.'\Education\EntityAction' );
        $this->checkMethodName( $Namespace.'\Person\EntityAction' );
    }
}
