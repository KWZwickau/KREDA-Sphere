<?php
namespace KREDA\TestSuite\Tests\Application\Management;

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

        $Namespace = '\Management\Service';
        $this->checkMethodName( $Namespace.'\Address' );
        $this->checkMethodName( $Namespace.'\Course' );
        $this->checkMethodName( $Namespace.'\Education' );
        $this->checkMethodName( $Namespace.'\Person' );
        $this->checkMethodName( $Namespace.'\Student' );
    }
}
