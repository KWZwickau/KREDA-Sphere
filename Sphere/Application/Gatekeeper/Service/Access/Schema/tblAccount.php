<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\Schema;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="tblAccount")
 */
class tblAccount
{

    const USERNAME = 'Username';

    /** @Id @GeneratedValue @Column(type="bigint") */
    private $Id;
    /** @Column(type="string") */
    private $Username;
    /** @Column(type="string") */
    private $Password;
    /** @Column(type="bigint") */
    private $tblYubiKey;
    /** @Column(type="bigint") */
    private $apiHumanResources_Person;
    /** @Column(type="bigint") */
    private $apiSystem_Consumer;

    /**
     * @param string $Username
     */
    function __construct( $Username )
    {

        $this->Username = $Username;
    }

    /**
     * @param integer $apiHumanResources_Person
     */
    public function setApiHumanResourcesPerson( $apiHumanResources_Person )
    {

        $this->apiHumanResources_Person = $apiHumanResources_Person;
    }

    /**
     * @param integer $apiSystem_Consumer
     */
    public function setApiSystemConsumer( $apiSystem_Consumer )
    {

        $this->apiSystem_Consumer = $apiSystem_Consumer;
    }

    /**
     * @param string $Password
     */
    public function setPassword( $Password )
    {

        $this->Password = $Password;
    }

    /**
     * @param integer $tblYubiKey
     */
    public function setTblYubiKey( $tblYubiKey )
    {

        $this->tblYubiKey = $tblYubiKey;
    }

}
