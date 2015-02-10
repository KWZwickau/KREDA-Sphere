<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Account;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Token;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity\TblToken;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblAccount")
 */
class TblAccount extends AbstractEntity
{

    const ATTR_USERNAME = 'Username';
    const ATTR_PASSWORD = 'Password';
    const ATTR_TBL_ACCOUNT_TYP = 'tblAccountTyp';
    const ATTR_TBL_ACCOUNT_ROLE = 'tblAccountRole';
    const ATTR_SERVICE_GATEKEEPER_TOKEN = 'serviceGatekeeper_Token';
    const ATTR_SERVICE_GATEKEEPER_CONSUMER = 'serviceGatekeeper_Consumer';
    const ATTR_SERVICE_MANAGEMENT_PERSON = 'serviceManagement_Person';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="string")
     */
    protected $Username;
    /**
     * @Column(type="string")
     */
    protected $Password;
    /**
     * @Column(type="bigint")
     */
    protected $tblAccountTyp;
    /**
     * @Column(type="bigint")
     */
    protected $tblAccountRole;
    /**
     * @Column(type="bigint")
     */
    protected $serviceGatekeeper_Token;
    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Person;
    /**
     * @Column(type="bigint")
     */
    protected $serviceGatekeeper_Consumer;

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
     * @return bool|TblPerson
     */
    public function getServiceManagementPerson()
    {

        if (null === $this->serviceManagement_Person) {
            return false;
        } else {
            return Management::servicePerson( $this->getServiceGatekeeperConsumer() )->entityPersonById( $this->serviceManagement_Person );
        }
    }

    /**
     * @param null|TblPerson $tblPerson
     */
    public function setServiceManagementPerson( TblPerson $tblPerson = null )
    {

        $this->serviceManagement_Person = ( null === $tblPerson ? null : $tblPerson->getId() );
    }

    /**
     * @return bool|TblConsumer
     */
    public function getServiceGatekeeperConsumer()
    {

        if (null === $this->serviceGatekeeper_Consumer) {
            return false;
        } else {
            return Gatekeeper::serviceConsumer()->entityConsumerById( $this->serviceGatekeeper_Consumer );
        }
    }

    /**
     * @param null|TblConsumer $tblConsumer
     */
    public function setServiceGatekeeperConsumer( TblConsumer $tblConsumer = null )
    {

        $this->serviceGatekeeper_Consumer = ( null === $tblConsumer ? null : $tblConsumer->getId() );
    }

    /**
     * @return bool|TblToken
     */
    public function getServiceGatekeeperToken()
    {

        if (null === $this->serviceGatekeeper_Token) {
            return false;
        } else {
            return Gatekeeper::serviceToken()->entityTokenById( $this->serviceGatekeeper_Token );
        }
    }

    /**
     * @param null|TblToken $tblToken
     */
    public function setServiceGatekeeperToken( TblToken $tblToken = null )
    {

        $this->serviceGatekeeper_Token = ( null === $tblToken ? null : $tblToken->getId() );
    }


    /**
     * @return bool|TblAccountTyp
     */
    public function getTblAccountTyp()
    {

        if (null === $this->tblAccountTyp) {
            return false;
        } else {
            return Gatekeeper::serviceAccount()->entityAccountTypById( $this->tblAccountTyp );
        }
    }

    /**
     * @param null|TblAccountTyp $tblAccountTyp
     */
    public function setTblAccountTyp( TblAccountTyp $tblAccountTyp = null )
    {

        $this->tblAccountTyp = ( null === $tblAccountTyp ? null : $tblAccountTyp->getId() );
    }


    /**
     * @return bool|TblAccountRole
     */
    public function getTblAccountRole()
    {

        if (null === $this->tblAccountRole) {
            return false;
        } else {
            return Gatekeeper::serviceAccount()->entityAccountRoleById( $this->tblAccountRole );
        }
    }

    /**
     * @param null|TblAccountRole $tblAccountRole
     */
    public function setTblAccountRole( TblAccountRole $tblAccountRole = null )
    {

        $this->tblAccountRole = ( null === $tblAccountRole ? null : $tblAccountRole->getId() );
    }
}
