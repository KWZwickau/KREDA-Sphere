<?php
namespace KREDA\Sphere\Application\Management;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Management\Frontend\Campus\Campus;
use KREDA\Sphere\Application\Management\Frontend\PersonalData\PersonalData;
use KREDA\Sphere\Application\Management\Frontend\Subject\Subject;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BuildingIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Management
 *
 * @package KREDA\Sphere\Application\Management
 */
class Management extends Module\Account
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

        self::setupApplicationAccess( 'Management' );

        self::$Configuration = $Configuration;

        /**
         * Navigation
         */
        self::registerClientRoute( self::$Configuration, '/Sphere/Management', __CLASS__.'::frontendManagement' );
        self::addClientNavigationMain( self::$Configuration, '/Sphere/Management', 'Verwaltung', new CogWheelsIcon() );

        self::registerApplicationEducation( $Configuration );
        self::registerApplicationPerson( $Configuration );

        /**
         * Campus
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Campus', __CLASS__.'::frontendCampus'
        );

        Module\Common::registerApplication( $Configuration );
        if (Gatekeeper::serviceAccess()->checkIsValidAccess( '/Sphere/Management/Token' )) {
            Module\Token::registerApplication( $Configuration );
        }
        if (Gatekeeper::serviceAccess()->checkIsValidAccess( '/Sphere/Management/Account' )) {
            Module\Account::registerApplication( $Configuration );
        }

        return $Configuration;
    }

    private static function registerApplicationEducation()
    {

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education', __CLASS__.'::guiEducation'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Group', __CLASS__.'::guiEducationGroup'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Subject', __CLASS__.'::frontendEducationSubject'
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Period', __CLASS__.'::guiEducationPeriod'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Management/Education/Mission', __CLASS__.'::guiEducationMission'
        );
    }

    private static function registerApplicationPerson()
    {

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
     * @param TblConsumer $tblConsumer
     *
     * @return Service\Education
     */
    public static function serviceEducation( TblConsumer $tblConsumer = null )
    {

        return Service\Education::getApi( $tblConsumer );
    }

    /**
     * @param TblConsumer $tblConsumer
     *
     * @return Service\Person
     */
    public static function servicePerson( TblConsumer $tblConsumer = null )
    {

        return Service\Person::getApi( $tblConsumer );
    }

    /**
     * @param TblConsumer $tblConsumer
     *
     * @return Service\Address
     */
    public static function serviceAddress( TblConsumer $tblConsumer = null )
    {

        return Service\Address::getApi( $tblConsumer );
    }

    /**
     * @return Stage
     */
    public function frontendEducationSubject()
    {

        $this->setupModuleNavigation();
        return Subject::guiSubject();
    }

    /**
     * @return Stage
     */
    public function frontendManagement()
    {

        $this->setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Verwaltung' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @return Stage
     */
    public function frontendPerson()
    {

        $this->setupModuleNavigation();
        $this->setupPersonNavigation();
        return PersonalData::stagePerson();
    }

    /**
     * @return void
     */
    protected function setupPersonNavigation()
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
            $this->setupPersonNavigation();
            return PersonalData::stagePersonStudentDetail( $Id );
        }
    }

    /**
     * @return Stage
     */
    public function frontendPersonStudentList()
    {

        $this->setupModuleNavigation();
        $this->setupPersonNavigation();
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
            $this->setupPersonNavigation();
            return PersonalData::stagePersonTeacherDetail( $Id );
        }
    }

    /**
     * @return Stage
     */
    public function frontendPersonTeacherList()
    {

        $this->setupModuleNavigation();
        $this->setupPersonNavigation();
        return PersonalData::stagePersonTeacher();
    }

    /**
     * @return Stage
     */
    public function frontendPersonGuardianList()
    {

        $this->setupModuleNavigation();
        $this->setupPersonNavigation();
        return PersonalData::stagePersonGuardian();
    }

    /**
     * @return Stage
     */
    public function frontendPersonStaffList()
    {

        $this->setupModuleNavigation();
        $this->setupPersonNavigation();
        return PersonalData::stagePersonStaff();
    }

    /**
     * @return Stage
     */
    public function frontendPersonOthersList()
    {

        $this->setupModuleNavigation();
        $this->setupPersonNavigation();
        return PersonalData::stagePersonOthers();
    }

    /**
     * @return Stage
     */
    public function frontendPersonStudentCreate()
    {

        $this->setupModuleNavigation();
        $this->setupPersonNavigation();
        return PersonalData::stagePersonStudentCreate();
    }

    /**
     * @return Stage
     */
    public function frontendCampus()
    {

        $this->setupModuleNavigation();
        $this->setupCampusNavigation();
        return Campus::stageCampus();
    }

    /**
     * @return void
     */
    protected function setupCampusNavigation()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Management', 'Gebäude', new BuildingIcon()
        );
    }
}
