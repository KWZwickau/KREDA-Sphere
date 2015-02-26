<?php
namespace KREDA\Sphere\Application\Management\Module;

use KREDA\Sphere\Application\Management\Frontend\PersonalData\PersonalData;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Person
 *
 * @package KREDA\Sphere\Application\Management\Module
 */
class Person extends Account
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     *
     * @return Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;

        /**
         * Person
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person', __CLASS__.'::frontendPerson'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Student', __CLASS__.'::frontendPersonStudentList'
        );
        $Route = self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Student/Detail', __CLASS__.'::frontendPersonStudentDetail'
        );
        $Route->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Student/Create', __CLASS__.'::frontendPersonStudentCreate'
        );
        /**
         * Guardian
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Guardian', __CLASS__.'::frontendPersonGuardianList'
        );
        $Route = self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Guardian/Detail', __CLASS__.'::frontendPersonGuardianDetail'
        );
        $Route->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Guardian/Create', __CLASS__.'::frontendPersonGuardianCreate'
        );
        /**
         * Teacher
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Teacher', __CLASS__.'::frontendPersonTeacherList'
        );
        $Route = self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Teacher/Detail', __CLASS__.'::frontendPersonTeacherDetail'
        );
        $Route->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Teacher/Create', __CLASS__.'::frontendPersonTeacherCreate'
        );
        /**
         * Staff
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Staff', __CLASS__.'::frontendPersonStaffList'
        );
        $Route = self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Staff/Detail', __CLASS__.'::frontendPersonStaffDetail'
        );
        $Route->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Staff/Create', __CLASS__.'::frontendPersonStaffCreate'
        );
        /**
         * Others
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Others', __CLASS__.'::frontendPersonOthersList'
        );
        $Route = self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Others/Detail', __CLASS__.'::frontendPersonOthersDetail'
        );
        $Route->setParameterDefault( 'Id', null );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Person/Others/Create', __CLASS__.'::frontendPersonOthersCreate'
        );
    }

    /**
     * @return Stage
     */
    public function frontendPerson()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigation();
        return PersonalData::stagePerson();
    }

    /**
     * @return void
     */
    protected function setupApplicationNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Person/Student', 'Schüler', new PersonIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Person/Guardian', 'Sorgeberechtigte', new PersonIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Person/Teacher', 'Lehrer', new PersonIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Person/Staff', 'Verwaltung', new PersonIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management/Person/Others', 'Sonstige', new PersonIcon()
        );

    }

    /**
     * @param int $Id
     *
     * @return Stage
     */
    public function frontendPersonStudentDetail( $Id )
    {

        if (null === $Id) {
            return $this->frontendPersonStudentList();
        } else {
            $this->setupModuleNavigation();
            $this->setupApplicationNavigation();
            return PersonalData::stagePersonStudentDetail( $Id );
        }
    }

    /**
     * @return Stage
     */
    public function frontendPersonStudentList()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigation();
        return PersonalData::stagePersonStudent();
    }

    /**
     * @param int $Id
     *
     * @return Stage
     */
    public function frontendPersonTeacherDetail( $Id )
    {

        if (null === $Id) {
            return $this->frontendPersonTeacherList();
        } else {
            $this->setupModuleNavigation();
            $this->setupApplicationNavigation();
            return PersonalData::stagePersonTeacherDetail( $Id );
        }
    }

    /**
     * @return Stage
     */
    public function frontendPersonTeacherList()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigation();
        return PersonalData::stagePersonTeacher();
    }

    /**
     * @return Stage
     */
    public function frontendPersonGuardianList()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigation();
        return PersonalData::stagePersonGuardian();
    }

    /**
     * @return Stage
     */
    public function frontendPersonStaffList()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigation();
        return PersonalData::stagePersonStaff();
    }

    /**
     * @return Stage
     */
    public function frontendPersonOthersList()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigation();
        return PersonalData::stagePersonOthers();
    }

    /**
     * @return Stage
     */
    public function frontendPersonStudentCreate()
    {

        $this->setupModuleNavigation();
        $this->setupApplicationNavigation();
        return PersonalData::stagePersonStudentCreate();
    }

}
