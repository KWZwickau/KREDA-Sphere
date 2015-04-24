<?php
namespace KREDA\Sphere\Application\Management\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Management\Service\Course\Entity\TblCourse;
use KREDA\Sphere\Application\Management\Service\Course\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Course
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Course extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Management', 'Course', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

        $this->actionCreateCourse( 'Grundschule', '' );
        $this->actionCreateCourse( 'Hauptschule', '' );
        $this->actionCreateCourse( 'Realschule', '' );
        $this->actionCreateCourse( 'Gymnasium', '' );
    }

    /**
     * @return bool|TblCourse[]
     */
    public function entityCourseAll()
    {

        return parent::entityCourseAll();
    }

    /**
     * @param string $Name
     *
     * @return bool|TblCourse
     */
    public function entityCourseByName( $Name )
    {

        return parent::entityCourseByName( $Name );
    }

    /**
     * @param int $Id
     *
     * @return bool|TblCourse
     */
    public function entityCourseById( $Id )
    {

        return parent::entityCourseById( $Id );
    }

    /**
     * @return Table
     */
    public function getTableCourse()
    {

        return parent::getTableCourse();
    }
}
