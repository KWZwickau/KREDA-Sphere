<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;

/**
 * @Entity
 * @Table(name="tblAccountSession")
 */
class TblAccountSession
{

    const ATTR_SESSION = 'Session';
    const ATTR_TIMEOUT = 'Timeout';
    const ATTR_TBL_ACCOUNT = 'tblAccount';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    private $Id;
    /**
     * @Column(type="string")
     */
    private $Session;
    /**
     * @Column(type="integer")
     */
    private $Timeout;
    /**
     * @Column(type="bigint")
     */
    private $tblAccount;

    /**
     * @param string $Session
     */
    function __construct( $Session )
    {

        $this->Session = $Session;
    }

    /**
     * @return bool|TblAccount
     */
    public function getTblAccount()
    {

        return Gatekeeper::serviceAccount()->entityAccountById( $this->tblAccount );
    }

    /**
     * @param null|TblAccount $tblAccount
     */
    public function setTblAccount( TblAccount $tblAccount = null )
    {

        $this->tblAccount = ( null === $tblAccount ? null : $tblAccount->getId() );
    }

    /**
     * @return integer
     */
    public function getTimeout()
    {

        return $this->Timeout;
    }

    /**
     * @param integer $Timeout
     */
    public function setTimeout( $Timeout )
    {

        $this->Timeout = $Timeout;
    }

    /**
     * @return integer
     */
    public function getId()
    {

        return $this->Id;
    }

    /**
     * @param integer $Id
     */
    public function setId( $Id )
    {

        $this->Id = $Id;
    }

    /**
     * @return string
     */
    public function getSession()
    {

        return $this->Session;
    }

    /**
     * @param string $Session
     */
    public function setSession( $Session )
    {

        $this->Session = $Session;
    }
}
