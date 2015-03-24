<?php
namespace KREDA\TestSuite\Tests\Application\Management;

use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressCity;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddressState;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonGender;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipList;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonRelationshipType;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonSalutation;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPersonType;

/**
 * Class EntityTest
 *
 * @package KREDA\TestSuite\Tests\Application\Management
 */
class EntityTest extends \PHPUnit_Framework_TestCase
{

    public function testAddressTblAddress()
    {

        $Entity = new TblAddress();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getPostOfficeBox() );
        $this->assertEmpty( $Entity->getStreetName() );
        $this->assertEmpty( $Entity->getStreetNumber() );

        $this->assertEmpty( $Entity->getTblAddressCity() );
        $this->assertFalse( $Entity->getTblAddressCity() );

        $this->assertEmpty( $Entity->getTblAddressState() );
        $this->assertFalse( $Entity->getTblAddressState() );
    }

    public function testAddressTblAddressCity()
    {

        $Entity = new TblAddressCity();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getName() );
        $this->assertEmpty( $Entity->getCode() );
        $this->assertEmpty( $Entity->getDistrict() );
    }

    public function testAddressTblAddressState()
    {

        $Entity = new TblAddressState();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getName() );
    }

    public function testPersonTblPerson()
    {

        $Entity = new TblPerson();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getBirthday() );
        $this->assertEmpty( $Entity->getBirthplace() );
        $this->assertEmpty( $Entity->getTitle() );
        $this->assertEmpty( $Entity->getFirstName() );
        $this->assertEmpty( $Entity->getMiddleName() );
        $this->assertEmpty( $Entity->getLastName() );
        $this->assertEmpty( $Entity->getFullName() );
        $this->assertEmpty( $Entity->getNationality() );

        $this->assertEmpty( $Entity->getTblPersonGender() );
        $this->assertFalse( $Entity->getTblPersonGender() );

        $this->assertEmpty( $Entity->getTblPersonSalutation() );
        $this->assertFalse( $Entity->getTblPersonSalutation() );

        $this->assertEmpty( $Entity->getTblPersonType() );
        $this->assertFalse( $Entity->getTblPersonType() );
    }

    public function testPersonTblPersonRelationshipList()
    {

        $Entity = new TblPersonRelationshipList();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );

        $this->assertEmpty( $Entity->getTblPersonA() );
        $this->assertFalse( $Entity->getTblPersonA() );

        $this->assertEmpty( $Entity->getTblPersonB() );
        $this->assertFalse( $Entity->getTblPersonB() );

        $this->assertEmpty( $Entity->getTblPersonRelationshipType() );
        $this->assertFalse( $Entity->getTblPersonRelationshipType() );
    }

    public function testPersonTblPersonGender()
    {

        $Entity = new TblPersonGender();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getName() );
    }

    public function testPersonTblPersonType()
    {

        $Entity = new TblPersonType();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getName() );
    }

    public function testPersonTblPersonRelationshipType()
    {

        $Entity = new TblPersonRelationshipType();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getName() );
    }

    public function testPersonTblPersonSalutation()
    {

        $Entity = new TblPersonSalutation();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getName() );
    }
}
