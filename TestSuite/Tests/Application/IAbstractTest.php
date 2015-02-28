<?php
namespace KREDA\TestSuite\Tests\Application;

/**
 * Interface IAbstractTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
interface IAbstractTest
{

    /**
     * $this->checkMethodName( '\ApplicationDirectory\ClassFile' );
     *
     * @return void
     */
    public function testCodeStyle();
}
