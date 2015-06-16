<?php
namespace KREDA\Sphere\Application\Billing\Service\Commodity\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Course\Entity\TblCourse;
use KREDA\Sphere\Application\Management\Service\Student\Entity\TblChildRank;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblItem")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblItem extends AbstractEntity
{
    const ATTR_SERVICE_MANAGEMENT_COURSE = 'serviceManagement_Course';
    const ATTR_SERVICE_MANAGEMENT_STUDENT_CHILD_RANK = 'serviceManagement_Student_ChildRank';
    const ATTR_NAME = 'Name';

    /**
     * @Column(type="string")
     */
    protected $Description;

    /**
     * @Column(type="decimal", precision=14, scale=4)
     */
    protected $Price;

    /**
     * @Column(type="string")
     */
    protected $Name;

    /**
     * @Column(type="string")
     */
    protected $CostUnit;

    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Course;

    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Student_ChildRank;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param string $Description
     */
    public function setDescription( $Description )
    {
        $this->Description = $Description;
    }

    /**
     * @return (type="decimal", precision=14, scale=4)
     */
    public function getPrice()
    {
        return $this->Price;
    }

    /**
     * @param (type="decimal", precision=14, scale=4) $Price
     */
    public function setPrice( $Price )
    {
        $this->Price = $Price;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     */
    public function setName( $Name )
    {
        $this->Name = $Name;
    }

    /**
     * @return string
     */
    public function getCostUnit()
    {
        return $this->CostUnit;
    }

    /**
     * @param string $CostUnit
     */
    public function setCostUnit( $CostUnit )
    {
        $this->CostUnit = $CostUnit;
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

    /**
     * @return bool|TblChildRank
     */
    public function getServiceManagementStudentChildRank()
    {

        if (null === $this->serviceManagement_Student_ChildRank) {
            return false;
        } else {
            return Management::serviceStudent()->entityChildRankById( $this->serviceManagement_Student_ChildRank );
        }
    }

    /**
     * @param null|TblChildRank $tblChildRank
     */
    public function setServiceManagementStudentChildRank( TblChildRank $tblChildRank = null )
    {
        $this->serviceManagement_Student_ChildRank = ( null === $tblChildRank ? null : $tblChildRank->getId() );
    }
}
