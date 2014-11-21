<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token\Schema;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="tblToken")
 */
class TblToken
{

    const ATTR_IDENTIFIER = 'Identifier';

    /** @Id @GeneratedValue @Column(type="bigint") */
    private $Id;
    /** @Column(type="string") */
    private $Identifier;

    /**
     * @param string $Token
     */
    function __construct( $Token )
    {

        $this->Identifier = $Token;
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
}
