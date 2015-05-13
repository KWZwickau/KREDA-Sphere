<?php
namespace KREDA\TestSuite\Tests\Application\System;

use KREDA\Sphere\Application\System\Service\Protocol\Entity\TblProtocol;

/**
 * Class EntityTest
 *
 * @package KREDA\TestSuite\Tests\Application\System
 */
class EntityTest extends \PHPUnit_Framework_TestCase
{

    public function testProtocolTblProtocol()
    {

        $Entity = new TblProtocol();
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractEntity', $Entity );

        $this->assertEmpty( $Entity->getId() );
        $this->assertEmpty( $Entity->getAccountUsername() );
        $this->assertEmpty( $Entity->getConsumerName() );
        $this->assertEmpty( $Entity->getConsumerSuffix() );
        $this->assertEmpty( $Entity->getEntityFrom() );
        $this->assertEmpty( $Entity->getEntityTo() );
        $this->assertEmpty( $Entity->getPersonFirstName() );
        $this->assertEmpty( $Entity->getPersonLastName() );
        $this->assertEmpty( $Entity->getProtocolDatabase() );
        $this->assertEmpty( $Entity->getProtocolTimestamp() );

        $this->assertEmpty( $Entity->getServiceGatekeeperAccount() );
        $this->assertFalse( $Entity->getServiceGatekeeperAccount() );

        $this->assertEmpty( $Entity->getServiceGatekeeperConsumer() );
        $this->assertFalse( $Entity->getServiceGatekeeperConsumer() );

        $this->assertEmpty( $Entity->getServiceManagementPerson() );
        $this->assertFalse( $Entity->getServiceManagementPerson() );
    }

}
