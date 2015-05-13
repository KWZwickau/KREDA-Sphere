<?php
namespace KREDA\Sphere\Application\Management\Service\Education\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblSubjectGroupTeacher")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblSubjectGroupTeacher extends AbstractEntity
{

    const ATTR_TBL_SUBJECT_GROUP = 'tblSubjectGroup';
    const ATTR_SERVICE_MANAGEMENT_PERSON = 'serviceManagement_Person';

    /**
     * @Column(type="bigint")
     */
    protected $tblSubjectGroup;
    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Person;

    /**
     * @return bool|TblSubjectGroup
     */
    public function getTblSubjectGroup()
    {

        if (null === $this->tblSubjectGroup) {
            return false;
        } else {
            return Management::serviceEducation()->entitySubjectGroupById( $this->tblSubjectGroup );
        }
    }

    /**
     * @param null|TblSubjectGroup $tblSubjectGroup
     */
    public function setTblSubjectGroup( TblSubjectGroup $tblSubjectGroup = null )
    {

        $this->tblSubjectGroup = ( null === $tblSubjectGroup ? null : $tblSubjectGroup->getId() );
    }

    /**
     * @return bool|TblPerson
     */
    public function getServiceManagementPerson()
    {

        if (null === $this->serviceManagement_Person) {
            return false;
        } else {
            return Management::servicePerson()->entityPersonById( $this->serviceManagement_Person );
        }
    }

    /**
     * @param null|TblPerson $tblPerson
     */
    public function setServiceManagementPerson( TblPerson $tblPerson = null )
    {

        $this->serviceManagement_Person = ( null === $tblPerson ? null : $tblPerson->getId() );
    }
}
