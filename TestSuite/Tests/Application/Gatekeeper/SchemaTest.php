<?php
namespace KREDA\TestSuite\Tests\Application\Gatekeeper;

use KREDA\TestSuite\Tests\Application\AbstractSchemaTest;

/**
 * Class SchemaTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
class SchemaTest extends AbstractSchemaTest
{

    public function testCodeStyle()
    {

        $Namespace = '\Gatekeeper\Service';
        $this->checkMethodName( $Namespace.'\Access\EntitySchema' );
        $this->checkMethodName( $Namespace.'\Account\EntitySchema' );
        $this->checkMethodName( $Namespace.'\Consumer\EntitySchema' );
        $this->checkMethodName( $Namespace.'\Token\EntitySchema' );
    }
}
