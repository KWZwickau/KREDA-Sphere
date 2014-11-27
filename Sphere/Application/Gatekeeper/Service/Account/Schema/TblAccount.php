<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account\Schema;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Gatekeeper\Service\Token;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Schema\TblToken;

/**
 * @Entity
 * @Table(name="tblAccount")
 */
class TblAccount
{

    const ATTR_USERNAME = 'Username';
    const ATTR_PASSWORD = 'Password';
    const ATTR_TBL_TOKEN = 'tblToken';
    const ATTR_API_HUMANRESOURCES_PERSON = 'apiHumanResources_Person';
    const ATTR_API_SYSTEM_CONSUMER = 'apiSystem_Consumer';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    private $Id;
    /**
     * @Column(type="string")
     */
    private $Username;
    /**
     * @Column(type="string")
     */
    private $Password;
    /**
     * @Column(type="bigint")
     */
    private $tblToken;
    /**
     * @Column(type="bigint")
     */
    private $apiHumanResources_Person;
    /**
     * @Column(type="bigint")
     */
    private $apiSystem_Consumer;

    /**
     * @param string $Username
     */
    function __construct( $Username )
    {

        $this->Username = $Username;
    }

    /**
     * @return integer
     */
    public function getId()
    {

        return $this->Id;
    }

    /**
     * @param null|integer $Id
     */
    public function setId( $Id )
    {

        $this->Id = $Id;
    }

    /**
     * @return string
     */
    public function getPassword()
    {

        return $this->Password;
    }

    /**
     * @param string $Password
     */
    public function setPassword( $Password )
    {

        $this->Password = $Password;
    }

    /**
     * @return string
     */
    public function getUsername()
    {

        return $this->Username;
    }

    /**
     * @param string $Username
     */
    public function setUsername( $Username )
    {

        $this->Username = $Username;
    }

    /**
     * @return null|integer
     */
    public function getApiHumanResourcesPerson()
    {

        return $this->apiHumanResources_Person;
    }

    /**
     * @param null|integer $apiHumanResources_Person
     */
    public function setApiHumanResourcesPerson( $apiHumanResources_Person )
    {

        $this->apiHumanResources_Person = $apiHumanResources_Person;
    }

    /**
     * @return null|integer
     */
    public function getApiSystemConsumer()
    {

        return $this->apiSystem_Consumer;
    }

    /**
     * @param null|integer $apiSystem_Consumer
     */
    public function setApiSystemConsumer( $apiSystem_Consumer )
    {

        $this->apiSystem_Consumer = $apiSystem_Consumer;
    }

    /**
     * @return bool|TblToken
     */
    public function getTblToken()
    {

        return Token::getApi()->entityTokenById( $this->tblToken );
    }

    /**
     * @param null|TblToken $tblToken
     */
    public function setTblToken( $tblToken )
    {

        if (null === $tblToken) {
            $this->tblToken = null;
        } else {
            $this->tblToken = $tblToken->getId();
        }
    }

}
