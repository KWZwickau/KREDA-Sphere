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
 * @Table(name="tblPersonRelationshipList")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblPersonRelationshipList extends AbstractEntity
{

    const ATTR_TBL_PERSON_RELATIONSHIP_TYPE = 'tblPersonRelationshipType';
    const ATTR_TBL_PERSON_A = 'tblPersonA';
    const ATTR_TBL_PERSON_B = 'tblPersonB';

    /**
     * @OneToOne(targetEntity="TblPersonRelationshipType",fetch="EXTRA_LAZY")
     * @Column(type="bigint")
     */
    protected $tblPersonRelationshipType;
    /**
     * @OneToOne(targetEntity="TblPerson",fetch="EXTRA_LAZY")
     * @Column(type="bigint")
     */
    protected $tblPersonA;
    /**
     * @OneToOne(targetEntity="TblPerson",fetch="EXTRA_LAZY")
     * @Column(type="bigint")
     */
    protected $tblPersonB;

    /**
     * @return bool|TblPerson
     */
    public function getTblPersonA()
    {

        if (null === $this->tblPersonA) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonById( $this->tblPersonA );
        }
    }

    /**
     * @param null|TblPerson $tblPerson
     */
    public function setTblPersonA( TblPerson $tblPerson )
    {

        $this->tblPersonA = ( null === $tblPerson ? null : $tblPerson->getId() );
    }

    /**
     * @return bool|TblPerson
     */
    public function getTblPersonB()
    {

        if (null === $this->tblPersonB) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonById( $this->tblPersonB );
        }

    }

    /**
     * @param null|TblPerson $tblPerson
     */
    public function setTblPersonB( TblPerson $tblPerson )
    {

        $this->tblPersonB = ( null === $tblPerson ? null : $tblPerson->getId() );
    }

    /**
     * @return bool|TblPersonRelationshipType
     */
    public function getTblPersonRelationshipType()
    {

        if (null === $this->tblPersonRelationshipType) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonRelationshipTypeById( $this->tblPersonRelationshipType );
        }
    }

    /**
     * @param null|TblPersonRelationshipType $tblPersonRelationshipType
     */
    public function setTblPersonRelationshipType( TblPersonRelationshipType $tblPersonRelationshipType )
    {

        $this->tblPersonRelationshipType = ( null === $tblPersonRelationshipType ? null : $tblPersonRelationshipType->getId() );
    }
}
