<?php
namespace KREDA\TestSuite\Tests\Application\Management;

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

        $Namespace = '\Management\Service';
        $this->checkMethodName( $Namespace.'\Address\EntitySchema' );
        $this->checkMethodName( $Namespace.'\Education\EntitySchema' );
        $this->checkMethodName( $Namespace.'\Person\EntitySchema' );
    }
}
