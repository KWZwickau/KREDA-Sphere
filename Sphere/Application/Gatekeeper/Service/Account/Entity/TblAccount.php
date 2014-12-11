<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Account;
use KREDA\Sphere\Application\Gatekeeper\Service\Token;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;

/**
 * @Entity
 * @Table(name="tblAccount")
 */
class TblAccount
{

    const ATTR_USERNAME = 'Username';
    const ATTR_PASSWORD = 'Password';
    const ATTR_TBL_ACCOUNT_TYP = 'tblAccountTyp';
    const ATTR_SERVICE_GATEKEEPER_TOKEN = 'serviceGatekeeper_Token';
    const ATTR_SERVICE_GATEKEEPER_CONSUMER = 'serviceGatekeeper_Consumer';
    const ATTR_SERVICE_HUMANRESOURCES_PERSON = 'serviceHumanResources_Person';

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
    private $tblAccountTyp;
    /**
     * @Column(type="bigint")
     */
    private $serviceGatekeeper_Token;
    /**
     * @Column(type="bigint")
     */
    private $serviceHumanResources_Person;
    /**
     * @Column(type="bigint")
     */
    private $serviceGatekeeper_Consumer;

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
    public function getServiceHumanResourcesPerson()
    {

        return $this->serviceHumanResources_Person;
    }

    /**
     * @param null|integer $serviceHumanResources_Person
     */
    public function setServiceHumanResourcesPerson( $serviceHumanResources_Person )
    {

        $this->serviceHumanResources_Person = $serviceHumanResources_Person;
    }

    /**
     * @return null|integer
     */
    public function getServiceGatekeeperConsumer()
    {

        return $this->serviceGatekeeper_Consumer;
    }

    /**
     * @param null|integer $serviceGatekeeper_Consumer
     */
    public function setServiceGatekeeperConsumer( $serviceGatekeeper_Consumer )
    {

        $this->serviceGatekeeper_Consumer = $serviceGatekeeper_Consumer;
    }

    /**
     * @return bool|TblToken
     */
    public function getServiceGatekeeperToken()
    {

        return Gatekeeper::serviceToken()->entityTokenById( $this->serviceGatekeeper_Token );
    }

    /**
     * @param null|TblToken $tblToken
     */
    public function setServiceGatekeeperToken( TblToken $tblToken = null )
    {

        if (null === $tblToken) {
            $this->serviceGatekeeper_Token = null;
        } else {
            $this->serviceGatekeeper_Token = $tblToken->getId();
        }
    }


    /**
     * @return bool|TblAccountTyp
     */
    public function getTblAccountTyp()
    {

        return Gatekeeper::serviceAccount()->entityAccountTypById( $this->tblAccountTyp );
    }

    /**
     * @param null|TblAccountTyp $tblAccountTyp
     */
    public function setTblAccountTyp( TblAccountTyp $tblAccountTyp )
    {

        if (null === $tblAccountTyp) {
            $this->tblAccountTyp = null;
        } else {
            $this->tblAccountTyp = $tblAccountTyp->getId();
        }
    }

}
