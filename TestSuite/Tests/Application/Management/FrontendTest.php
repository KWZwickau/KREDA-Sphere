<?php
namespace KREDA\TestSuite\Tests\Application\Management;

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

        $Namespace = '\Management\Frontend';
        $this->checkMethodName( $Namespace.'\Campus' );
        $this->checkMethodName( $Namespace.'\Person' );
        $this->checkMethodName( $Namespace.'\Subject' );
    }
}
