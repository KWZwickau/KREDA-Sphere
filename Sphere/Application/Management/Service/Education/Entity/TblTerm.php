<?php
namespace KREDA\Sphere\Application\Management\Service\Education\Entity;

use Doctrine\ORM\Mapping\Cache;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Course\Entity\TblCourse;
use KREDA\Sphere\Application\Management\Service\Education;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * @Entity
 * @Table(name="tblTerm")
 * @Cache(usage="NONSTRICT_READ_WRITE")
 */
class TblTerm extends AbstractEntity
{

    const ATTR_NAME = 'Name';
    const ATTR_SERVICE_MANAGEMENT_COURSE = 'serviceManagement_Course';

    /**
     * @Column(type="string")
     */
    protected $Name;
    /**
     * @Column(type="date")
     */
    protected $FirstDateFrom;
    /**
     * @Column(type="date")
     */
    protected $FirstDateTo;
    /**
     * @Column(type="date")
     */
    protected $SecondDateFrom;
    /**
     * @Column(type="date")
     */
    protected $SecondDateTo;
    /**
     * @Column(type="bigint")
     */
    protected $serviceManagement_Course;

    /**
     * @param string $Name
     */
    function __construct( $Name )
    {

        $this->Name = $Name;
    }

    /**
     * @return string
     */
    public function getFirstDateFrom()
    {

        if (null === $this->FirstDateFrom) {
            return false;
        }
        /** @var \DateTime $DateFrom */
        $DateFrom = $this->FirstDateFrom;
        return $DateFrom->format( 'd.m.Y' );
    }

    /**
     * @param \DateTime $DateFrom
     */
    public function setFirstDateFrom( \DateTime $DateFrom )
    {

        $this->FirstDateFrom = $DateFrom;
    }

    /**
     * @return string
     */
    public function getFirstDateTo()
    {

        if (null === $this->FirstDateTo) {
            return false;
        }
        /** @var \DateTime $DateTo */
        $DateTo = $this->FirstDateTo;
        return $DateTo->format( 'd.m.Y' );
    }

    /**
     * @param \DateTime $DateTo
     */
    public function setFirstDateTo( \DateTime $DateTo )
    {

        $this->FirstDateTo = $DateTo;
    }

    /**
     * @return string
     */
    public function getSecondDateFrom()
    {

        if (null === $this->SecondDateFrom) {
            return false;
        }
        /** @var \DateTime $DateFrom */
        $DateFrom = $this->SecondDateFrom;
        return $DateFrom->format( 'd.m.Y' );
    }

    /**
     * @param \DateTime $DateFrom
     */
    public function setSecondDateFrom( \DateTime $DateFrom )
    {

        $this->SecondDateFrom = $DateFrom;
    }

    /**
     * @return string
     */
    public function getSecondDateTo()
    {

        if (null === $this->SecondDateTo) {
            return false;
        }
        /** @var \DateTime $DateTo */
        $DateTo = $this->SecondDateTo;
        return $DateTo->format( 'd.m.Y' );
    }

    /**
     * @param \DateTime $DateTo
     */
    public function setSecondDateTo( \DateTime $DateTo )
    {

        $this->SecondDateTo = $DateTo;
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
