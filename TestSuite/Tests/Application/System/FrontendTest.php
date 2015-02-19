<?php
namespace KREDA\TestSuite\Tests\Application\System;

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

        $Namespace = '\System\Frontend';
        $this->checkMethodName( $Namespace.'\Authorization\Authorization' );
        $this->checkMethodName( $Namespace.'\Token\Token' );
        $this->checkMethodName( $Namespace.'\Cache' );
        $this->checkMethodName( $Namespace.'\Consumer' );
        $this->checkMethodName( $Namespace.'\Database' );
        $this->checkMethodName( $Namespace.'\Protocol' );
        $this->checkMethodName( $Namespace.'\Update' );
    }
}
