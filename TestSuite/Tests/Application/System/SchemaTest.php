<?php
namespace KREDA\TestSuite\Tests\Application\System;

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

        $Namespace = '\System\Service';
        $this->checkMethodName( $Namespace.'\Protocol\EntitySchema' );
    }
}
