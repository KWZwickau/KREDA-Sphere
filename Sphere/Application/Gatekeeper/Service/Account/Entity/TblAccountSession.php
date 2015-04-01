<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblAccountSession")
 */
class TblAccountSession extends AbstractEntity
{

    const ATTR_SESSION = 'Session';
    const ATTR_TIMEOUT = 'Timeout';
    const ATTR_TBL_ACCOUNT = 'tblAccount';

    /**
     * @Column(type="string")
     */
    protected $Session;
    /**
     * @Column(type="integer")
     */
    protected $Timeout;
    /**
     * @Column(type="bigint")
     */
    protected $tblAccount;

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

        if (null === $this->tblAccount) {
            return false;
        } else {
            return Gatekeeper::serviceAccount()->entityAccountById( $this->tblAccount );
        }
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
