<?php
namespace KREDA\TestSuite\Tests\Application\System;

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

        $Namespace = '\System\Service';
        $this->checkMethodName( $Namespace.'\Protocol' );
    }
}
