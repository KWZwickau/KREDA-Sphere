<?php
namespace KREDA\TestSuite\Tests\Application\Gatekeeper;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilegeList;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRight;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRightList;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountAccessList;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountSession;
use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountType;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumerType;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumerTypeList;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;

/**
 * Class EntityTest
 *
 * @package KREDA\TestSuite\Tests\Application\Gatekeeper
 */
class EntityTest extends \PHPUnit_Framework_TestCase
{

    public function testAccessTblAccess()
    {

        $Entity = new TblAccess( '' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getName() );
    }

    public function testAccessTblAccessPrivilege()
    {

        $Entity = new TblAccessPrivilege( '' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getName() );
    }

    public function testAccessTblAccessRight()
    {

        $Entity = new TblAccessRight( '' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getRoute() );
    }

    public function testAccessTblAccessPrivilegeList()
    {

        $Entity = new TblAccessPrivilegeList();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );

        $this->assertEmpty( $Entity->getTblAccess() );
        $this->assertFalse( $Entity->getTblAccess() );

        $this->assertEmpty( $Entity->getTblAccessPrivilege() );
        $this->assertFalse( $Entity->getTblAccessPrivilege() );
    }

    public function testAccessTblAccessRightList()
    {

        $Entity = new TblAccessRightList();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );

        $this->assertEmpty( $Entity->getTblAccessRight() );
        $this->assertFalse( $Entity->getTblAccessRight() );

        $this->assertEmpty( $Entity->getTblAccessPrivilege() );
        $this->assertFalse( $Entity->getTblAccessPrivilege() );
    }

    public function testAccountTblAccount()
    {

        $Entity = new TblAccount( '' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getUsername() );
        $this->assertEmpty( $Entity->getPassword() );

        $this->assertEmpty( $Entity->getTblAccountType() );
        $this->assertFalse( $Entity->getTblAccountType() );

        $this->assertEmpty( $Entity->getTblAccountRole() );
        $this->assertFalse( $Entity->getTblAccountRole() );

        $this->assertEmpty( $Entity->getServiceGatekeeperConsumer() );
        $this->assertFalse( $Entity->getServiceGatekeeperConsumer() );

        $this->assertEmpty( $Entity->getServiceGatekeeperToken() );
        $this->assertFalse( $Entity->getServiceGatekeeperToken() );

        $this->assertEmpty( $Entity->getServiceManagementPerson() );
        $this->assertFalse( $Entity->getServiceManagementPerson() );
    }

    public function testAccountTblAccountAccessList()
    {

        $Entity = new TblAccountAccessList();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );

        $this->assertEmpty( $Entity->getTblAccountRole() );
        $this->assertFalse( $Entity->getTblAccountRole() );

        $this->assertEmpty( $Entity->getServiceGatekeeperAccess() );
        $this->assertFalse( $Entity->getServiceGatekeeperAccess() );
    }

    public function testAccountTblAccountRole()
    {

        $Entity = new TblAccountRole();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getName() );
    }

    public function testAccountTblAccountType()
    {

        $Entity = new TblAccountType();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getName() );
    }

    public function testAccountTblAccountSession()
    {

        $Entity = new TblAccountSession( '' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getSession() );
        $this->assertEmpty( $Entity->getTimeout() );

        $this->assertEmpty( $Entity->getTblAccount() );
        $this->assertFalse( $Entity->getTblAccount() );
    }

    public function testConsumerTblConsumer()
    {

        $Entity = new TblConsumer( '' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getName() );
        $this->assertEmpty( $Entity->getDatabaseSuffix() );
        $this->assertEmpty( $Entity->getTableSuffix() );

        $this->assertEmpty( $Entity->getServiceManagementAddress() );
        $this->assertFalse( $Entity->getServiceManagementAddress() );
    }

    public function testConsumerTblConsumerType()
    {

        $Entity = new TblConsumerType();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getName() );
    }

    public function testConsumerTblConsumerTypeList()
    {

        $Entity = new TblConsumerTypeList();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );

        $this->assertEmpty( $Entity->getTblConsumer() );
        $this->assertFalse( $Entity->getTblConsumer() );

        $this->assertEmpty( $Entity->getTblConsumerType() );
        $this->assertFalse( $Entity->getTblConsumerType() );
    }

    public function testTokenTblToken()
    {

        $Entity = new TblToken( '' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getIdentifier() );
        $this->assertEmpty( $Entity->getSerial() );

        $this->assertEmpty( $Entity->getServiceGatekeeperConsumer() );
        $this->assertFalse( $Entity->getServiceGatekeeperConsumer() );
    }
}
