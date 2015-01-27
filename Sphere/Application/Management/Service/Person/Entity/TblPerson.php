<?php
namespace KREDA\Sphere\Application\Management\Service\Person\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblPerson")
 * @Cache(usage="READ_ONLY")
 */
class TblPerson extends AbstractEntity
{

    const ATTR_PERSON_TYPE = 'tblPersonType';

    const ATTR_SALUTATION = 'Salutation';
    const ATTR_FIRST_NAME = 'FirstName';
    const ATTR_MIDDLE_NAME = 'MiddleName';
    const ATTR_LAST_NAME = 'LastName';
    const ATTR_GENDER = 'Gender';
    const ATTR_BIRTHDAY = 'Birthday';

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="bigint")
     */
    protected $Id;
    /**
     * @Column(type="string")
     */
    protected $Salutation;
    /**
     * @Column(type="string")
     */
    protected $FirstName;
    /**
     * @Column(type="string")
     */
    protected $MiddleName;
    /**
     * @Column(type="string")
     */
    protected $LastName;
    /**
     * @Column(type="string")
     */
    protected $Gender;
    /**
     * @Column(type="string")
     */
    protected $Birthday;
//    /**
//     * @Column(type="bigint")
//     */
//    protected $tblPersonType;

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
