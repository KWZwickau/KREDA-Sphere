<?php
namespace KREDA\TestSuite\Tests\Application\Graduation;

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

        $Namespace = '\Graduation\Service';
        $this->checkMethodName( $Namespace.'\Grade\EntitySchema' );
        $this->checkMethodName( $Namespace.'\Score\EntitySchema' );
        $this->checkMethodName( $Namespace.'\Weight\EntitySchema' );
    }
}
