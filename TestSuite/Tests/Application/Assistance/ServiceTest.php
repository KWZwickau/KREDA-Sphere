<?php
namespace KREDA\TestSuite\Tests\Application\Assistance;

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

        $Namespace = '\Assistance\Service';
        $this->checkMethodName( $Namespace.'\Youtrack' );
    }
}
