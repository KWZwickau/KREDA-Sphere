<?php
namespace KREDA\Sphere\Application\Management\Service\Person\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="tblPerson")
 * @Cache(usage="READ_ONLY")
 */
class TblPerson
{

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    private $Id;
    /**
     * @Column(type="string")
     */
    private $Salutation;
    /**
     * @Column(type="string")
     */
    private $FirstName;
    /**
     * @Column(type="string")
     */
    private $MiddleName;
    /**
     * @Column(type="string")
     */
    private $LastName;
    /**
     * @Column(type="string")
     */
    private $Gender;
    /**
     * @Column(type="string")
     */
    private $Birthday;

    /**
     * @return string
     */
    public function getSalutation()
    {

        return $this->Salutation;
    }

    /**
     * @param string $Salutation
     */
    public function setSalutation( $Salutation )
    {

        $this->Salutation = $Salutation;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {

        return $this->FirstName;
    }

    /**
     * @param string $FirstName
     */
    public function setFirstName( $FirstName )
    {

        $this->FirstName = $FirstName;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {

        return $this->MiddleName;
    }

    /**
     * @param string $MiddleName
     */
    public function setMiddleName( $MiddleName )
    {

        $this->MiddleName = $MiddleName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {

        return $this->LastName;
    }

    /**
     * @param string $LastName
     */
    public function setLastName( $LastName )
    {

        $this->LastName = $LastName;
    }

    /**
     * @return string
     */
    public function getGender()
    {

        return $this->Gender;
    }

    /**
     * @param string $Gender
     */
    public function setGender( $Gender )
    {

        $this->Gender = $Gender;
    }

    /**
     * @return string
     */
    public function getBirthday()
    {

        return $this->Birthday;
    }

    /**
     * @param string $Birthday
     */
    public function setBirthday( $Birthday )
    {

        $this->Birthday = $Birthday;
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
}
