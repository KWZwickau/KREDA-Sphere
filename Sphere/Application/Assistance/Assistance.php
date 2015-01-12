<?php
namespace KREDA\Sphere\Application\Assistance;

use KREDA\Sphere\Application\Assistance\Frontend\Account\Account;
use KREDA\Sphere\Application\Assistance\Frontend\Application\Application;
use KREDA\Sphere\Application\Assistance\Frontend\Support\Support;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BookIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuestionIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Assistance
 *
 * @package KREDA\Sphere\Application\Assistance
 */
class Assistance extends AbstractApplication
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

        self::addClientNavigationMeta( self::$Configuration,
            '/Sphere/Assistance', 'Hilfe', new QuestionIcon()
        );

        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance',
            __CLASS__.'::apiMain'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Support',
            __CLASS__.'::apiMain'
        );
        /**
         * Youtrack
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Support/Ticket',
            __CLASS__.'::frontendSupport_Ticket'
        );
        /**
         * Account
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Account',
            __CLASS__.'::apiAccount'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Account/Password/Forgotten',
            __CLASS__.'::frontendAccount_ForgottenPassword'
        );
        /**
         * Application
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Support/Application',
            __CLASS__.'::apiSupportApplication'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Support/Application/Start',
            __CLASS__.'::frontendApplication_Launch'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Support/Application/Missing',
            __CLASS__.'::frontendApplication_Missing'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Support/Application/Fatal',
            __CLASS__.'::frontendApplication_Fatal'
        );
        return $Configuration;
    }

    /**
     * @return Service\Youtrack
     */
    public static function serviceYoutrack()
    {

        return Service\Youtrack::getApi();
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
            '/Sphere/Assistance/Account', 'Benutzerkonto', new QuestionIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Assistance/Support/Application', 'Anwendungsfehler', new QuestionIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Assistance/Support/Ticket', 'Support', new QuestionIcon()
        );
    }

    /**
     * @return Landing
     */
    public function apiAccount()
    {

        $this->setupModuleNavigation();
        $this->setupFrontendAccount();
        $View = new Landing();
        $View->setTitle( 'Benutzerkonto' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function setupFrontendAccount()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Assistance/Account/Password/Forgotten', 'Passwort vergessen', new BookIcon()
        );

    }

    /**
     * @return Stage
     */
    public function frontendAccount_ForgottenPassword()
    {

        $this->setupModuleNavigation();
        $this->setupFrontendAccount();
        return Account::stageForgottenPassword();
    }

    /**
     * @return Landing
     */
    public function apiSupportApplication()
    {

        $this->setupModuleNavigation();
        $this->setupFrontendApplication();
        $View = new Landing();
        $View->setTitle( 'Anwendungsfehler' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function setupFrontendApplication()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Assistance/Support/Application/Start', 'Starten der Anwendung', new BookIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Assistance/Support/Application/Fatal', 'Fehler in der Anwendung', new BookIcon()
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
    public function frontendSupport_Ticket( $TicketSubject = null, $TicketMessage = null )
    {

        $this->setupModuleNavigation();
        return Support::stageTicket( $TicketSubject, $TicketMessage );
    }

    /**
     * @return Stage
     */
    public function frontendApplication_Launch()
    {

        $this->setupModuleNavigation();
        $this->setupFrontendApplication();
        return Application::stageLaunch();
    }

    /**
     * @return Stage
     */
    public function frontendApplication_Missing()
    {

        $this->setupModuleNavigation();
        $this->setupFrontendApplication();
        return Application::stageMissing();
    }

    /**
     * @return Stage
     */
    public function frontendApplication_Fatal()
    {

        $this->setupModuleNavigation();
        $this->setupFrontendApplication();
        return Application::stageFatal();
    }
}
