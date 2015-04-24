<?php
namespace KREDA\Sphere\Application\Management\Service\Student\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Course\Entity\TblCourse;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblStudent")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblStudent extends AbstractEntity
{

    const ATTR_TBL_CHILD_RANK = 'tblChildRank';
    const ATTR_SERVICE_MANAGEMENT_PERSON = 'serviceManagement_Person';
    const ATTR_SERVICE_MANAGEMENT_COURSE = 'serviceManagement_Course';

    /**
     * @Column(type="bigint")
     */
    protected $tblChildRank;
    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Person;
    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Course;

    /**
     * @return bool|TblChildRank
     */
    public function getTblChildRank()
    {

        if (null === $this->tblChildRank) {
            return false;
        } else {
            return Management::serviceStudent()->entityChildRankById( $this->tblChildRank );
        }
    }

    /**
     * @param null|TblChildRank $tblChildRank
     */
    public function setTblChildRank( TblChildRank $tblChildRank = null )
    {

        $this->tblChildRank = ( null === $tblChildRank ? null : $tblChildRank->getId() );
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

    /**
     * @return bool|TblCourse
     */
    public function getServiceManagementCourse()
    {

        if (null === $this->serviceManagement_Course) {
            return false;
        } else {
            return Management::serviceCourse()->entityCourseById( $this->serviceManagement_Course );
        }
    }

    /**
     * @param null|TblCourse $tblCourse
     */
    public function setServiceManagementCourse( TblCourse $tblCourse = null )
    {

        $this->serviceManagement_Course = ( null === $tblCourse ? null : $tblCourse->getId() );
    }
}
