<?php
namespace KREDA\Sphere\Application\Assistance;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BookIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuestionIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Client
 *
 * @package KREDA\Sphere\Application\Assistance
 */
class Client extends Application
{

    /** @var Configuration $Config */
    private static $Configuration = null;


    /**
     * @param Configuration $Configuration
     *
     * @return Configuration
     */
    public static function setupApi( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
        self::addClientNavigationMeta( self::$Configuration,
            '/Sphere/Assistance', 'Hilfe', new QuestionIcon()
        );

        self::buildRoute( self::$Configuration,
            '/Sphere/Assistance',
            __CLASS__.'::apiMain'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Assistance/Support',
            __CLASS__.'::apiMain'
        );
        /**
         * Youtrack
         */
        self::buildRoute( self::$Configuration,
            '/Sphere/Assistance/Support/Ticket',
            __CLASS__.'::apiSupportTicket'
        );
        /**
         * Account
         */
        self::buildRoute( self::$Configuration,
            '/Sphere/Assistance/Account',
            __CLASS__.'::apiAccount'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Assistance/Account/Password/Forgotten',
            __CLASS__.'::apiAccountPasswordForgotten'
        );
        /**
         * Application
         */
        self::buildRoute( self::$Configuration,
            '/Sphere/Assistance/Support/Application',
            __CLASS__.'::apiSupportApplication'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Assistance/Support/Application/Start',
            __CLASS__.'::apiSupportApplicationStart'
        );
        self::buildRoute( self::$Configuration,
            '/Sphere/Assistance/Support/Application/Missing',
            __CLASS__.'::apiSupportApplicationMissing'
        );
        return $Configuration;
    }

    /**
     * @return Landing
     */
    public function apiMain()
    {

        $this->setupModuleNavigation();
        $View = new Landing();
        $View->setTitle( 'Hilfe' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function setupModuleNavigation()
    {

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Assistance/Account', 'Benutzerkonto', new BookIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Assistance/Support/Application', 'Anwendungsfehler', new BookIcon()
        );
    }

    /**
     * @return Landing
     */
    public function apiAccount()
    {

        $this->setupModuleNavigation();
        $this->setupServiceAccount();
        $View = new Landing();
        $View->setTitle( 'Benutzerkonto' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function setupServiceAccount()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Assistance/Account/Password/Forgotten', 'Passwort vergessen', new BookIcon()
        );

    }

    /**
     * @return Stage
     */
    public function apiAccountPasswordForgotten()
    {

        $this->setupModuleNavigation();
        $this->setupServiceAccount();
        return Service\Account::getApi()->apiPasswordForgotten();
    }

    /**
     * @return Landing
     */
    public function apiSupportApplication()
    {

        $this->setupModuleNavigation();
        $this->setupServiceApplication();
        $View = new Landing();
        $View->setTitle( 'Anwendungsfehler' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function setupServiceApplication()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Assistance/Support/Application/Start', 'Starten der Anwendung', new BookIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Assistance/Support/Application/Missing', 'Nicht gefundene Resource', new BookIcon()
        );

    }

    /**
     * @param null|string $TicketSubject
     * @param null|string $TicketMessage
     *
     * @return Stage
     */
    public function apiSupportTicket( $TicketSubject = null, $TicketMessage = null )
    {

        $this->setupModuleNavigation();
        return Service\Youtrack::getApi()->apiTicket( $TicketSubject, $TicketMessage );
    }


    /**
     * @return Stage
     */
    public function apiSupportApplicationStart()
    {

        $this->setupModuleNavigation();
        $this->setupServiceApplication();
        return Service\Application::getApi()->apiAidStart();
    }

    /**
     * @return Stage
     */
    public function apiSupportApplicationMissing()
    {

        $this->setupModuleNavigation();
        $this->setupServiceApplication();
        return Service\Application::getApi()->apiAidMissingResource();
    }

}
