<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblToken")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblToken extends AbstractEntity
{

    const ATTR_IDENTIFIER = 'Identifier';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="string")
     */
    protected $Identifier;
    /**
     * @Column(type="string")
     */
    protected $Serial;

    /**
     * @param string $Identifier
     */
    function __construct( $Identifier )
    {

        $this->Identifier = $Identifier;
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
    public function getIdentifier()
    {

        return $this->Identifier;
    }

    /**
     * @param string $Identifier
     */
    public function setIdentifier( $Identifier )
    {

        $this->Identifier = $Identifier;
    }

    /**
     * @return string
     */
    public function getSerial()
    {

        return $this->Serial;
    }

    /**
     * @param string $Serial
     */
    public function setSerial( $Serial )
    {

        $this->Serial = $Serial;
    }
}
