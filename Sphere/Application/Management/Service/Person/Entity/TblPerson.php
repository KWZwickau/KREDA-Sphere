<?php
namespace KREDA\Sphere\Application\Management\Service\Person\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblPerson")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblPerson extends AbstractEntity
{

    const ATTR_TBL_PERSON_TYPE = 'tblPersonType';
    const ATTR_TBL_PERSON_GENDER = 'tblPersonGender';
    const ATTR_TBL_PERSON_SALUTATION = 'tblPersonSalutation';

    const ATTR_TITLE = 'Title';
    const ATTR_FIRST_NAME = 'FirstName';
    const ATTR_MIDDLE_NAME = 'MiddleName';
    const ATTR_LAST_NAME = 'LastName';
    const ATTR_BIRTHDAY = 'Birthday';
    const ATTR_BIRTHPLACE = 'Birthplace';
    /**
     * @Column(type="date")
     */
    public $Birthday;
    /**
     * @Column(type="string")
     */
    protected $Title;
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
    protected $Birthplace;
    /**
     * @Column(type="string")
     */
    protected $Nationality;
    /**
     * @Column(type="string")
     */
    protected $Denomination;
    /**
     * @OneToOne(targetEntity="TblPersonType",fetch="EXTRA_LAZY")
     * @Column(type="bigint")
     */
    protected $tblPersonType;
    /**
     * @OneToOne(targetEntity="TblPersonSalutation",fetch="EXTRA_LAZY")
     * @Column(type="bigint")
     */
    protected $tblPersonSalutation;
    /**
     * @OneToOne(targetEntity="TblPersonGender",fetch="EXTRA_LAZY")
     * @Column(type="bigint")
     */
    protected $tblPersonGender;
    /**
     * @Column(type="text")
     */
    protected $Remark;

    /**
     * @return string
     */
    public function getBirthplace()
    {

        return $this->Birthplace;
    }

    /**
     * @param string $Birthplace
     */
    public function setBirthplace( $Birthplace )
    {

        $this->Birthplace = $Birthplace;
    }

    /**
     * @return string
     */
    public function getNationality()
    {

        return $this->Nationality;
    }

    /**
     * @param string $Nationality
     */
    public function setNationality( $Nationality )
    {

        $this->Nationality = $Nationality;
    }

    /**
     * @return string
     */
    public function getDenomination()
    {

        return $this->Denomination;
    }

    /**
     * @param string $Denomination
     */
    public function setDenomination( $Denomination )
    {

        $this->Denomination = $Denomination;
    }

    /**
     * @return bool|TblPersonType
     */
    public function getTblPersonType()
    {

        if (null === $this->tblPersonType) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonTypeById( $this->tblPersonType );
        }
    }

    /**
     * @param null|TblPersonType $tblPersonType
     */
    public function setTblPersonType( TblPersonType $tblPersonType = null )
    {

        $this->tblPersonType = ( null === $tblPersonType ? null : $tblPersonType->getId() );
    }

    /**
     * @return bool|TblPersonSalutation
     */
    public function getTblPersonSalutation()
    {

        if (null === $this->tblPersonSalutation) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonSalutationById( $this->tblPersonSalutation );
        }
    }

    /**
     * @param null|TblPersonSalutation $tblPersonSalutation
     */
    public function setTblPersonSalutation( TblPersonSalutation $tblPersonSalutation = null )
    {

        $this->tblPersonSalutation = ( null === $tblPersonSalutation ? null : $tblPersonSalutation->getId() );
    }

    /**
     * @return bool|TblPersonGender
     */
    public function getTblPersonGender()
    {

        if (null === $this->tblPersonGender) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonGenderById( $this->tblPersonGender );
        }
    }

    /**
     * @param null|TblPersonGender $tblPersonGender
     */
    public function setTblPersonGender( TblPersonGender $tblPersonGender = null )
    {

        $this->tblPersonGender = ( null === $tblPersonGender ? null : $tblPersonGender->getId() );
    }

    /**
     * @return string
     */
    public function getFullName()
    {

        return trim( $this->getFirstName().( $this->getMiddleName() ? ' '.$this->getMiddleName() : '' ).' '.$this->getLastName() );
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
    public function getBirthday()
    {

        if (null === $this->Birthday) {
            return false;
        }
        /** @var \DateTime $Birthday */
        $Birthday = $this->Birthday;
        if ($Birthday instanceof \DateTime) {
            return $Birthday->format( 'd.m.Y' );
        } else {
            return (string)$Birthday;
        }
    }

    /**
     * @param \DateTime $Birthday
     */
    public function setBirthday( \DateTime $Birthday = null )
    {

        $this->Birthday = $Birthday;
    }

    /**
     * @return string
     */
    public function getTitle()
    {

        return $this->Title;
    }

    /**
     * @param string $Title
     */
    public function setTitle( $Title )
    {

        $this->Title = $Title;
    }

    /**
     * @return string
     */
    public function getRemark()
    {

        return $this->Remark;
    }

    /**
     * @param string $Remark
     */
    public function setRemark( $Remark )
    {

        if (empty( $Remark )) {
            $this->Remark = null;
        } else {
            $this->Remark = $Remark;
        }
    }
}
