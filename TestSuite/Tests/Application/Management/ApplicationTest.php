<?php
namespace KREDA\TestSuite\Tests\Application\Management;

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

        $this->checkMethodName( '\Management\Management' );
        $this->checkMethodName( '\Management\Module\Account' );
        $this->checkMethodName( '\Management\Module\Campus' );
        $this->checkMethodName( '\Management\Module\Common' );
        $this->checkMethodName( '\Management\Module\Education' );
        $this->checkMethodName( '\Management\Module\Person' );
        $this->checkMethodName( '\Management\Module\Relationship' );
        $this->checkMethodName( '\Management\Module\Token' );
    }
}
