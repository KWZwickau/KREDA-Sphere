<?php
namespace KREDA\TestSuite\Tests\Application\System;

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

        $this->checkMethodName( '\System\System' );
        $this->checkMethodName( '\System\Module\Authorization' );
        $this->checkMethodName( '\System\Module\Cache' );
        $this->checkMethodName( '\System\Module\Common' );
        $this->checkMethodName( '\System\Module\Consumer' );
        $this->checkMethodName( '\System\Module\Database' );
        $this->checkMethodName( '\System\Module\Protocol' );
        $this->checkMethodName( '\System\Module\Update' );
    }
}
