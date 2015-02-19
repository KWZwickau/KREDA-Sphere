<?php
namespace KREDA\TestSuite\Tests\Application\Statistic;

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

        $this->checkMethodName( '\Statistic\Statistic' );
    }
}
