<?php
namespace KREDA\Sphere\Application\Assistance;

use KREDA\Sphere\Application\Assistance\Frontend\Account;
use KREDA\Sphere\Application\Assistance\Frontend\Application;
use KREDA\Sphere\Application\Assistance\Frontend\Support;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
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
            __CLASS__.'::frontendAssistance'
        );
        if (Gatekeeper::serviceAccount()->checkIsValidSession()) {
            self::registerClientRoute( self::$Configuration,
                '/Sphere/Assistance/Support',
                __CLASS__.'::frontendSupport'
            );
            /**
             * Youtrack
             */
            self::registerClientRoute( self::$Configuration,
                '/Sphere/Assistance/Support/Ticket',
                __CLASS__.'::frontendSupportTicket'
            );
        }
        /**
         * Account
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Account',
            __CLASS__.'::frontendAccount'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Account/Password/Forgotten',
            __CLASS__.'::frontendAccountForgottenPassword'
        );
        /**
         * Application
         */
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Support/Application',
            __CLASS__.'::frontendApplication'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Support/Application/Start',
            __CLASS__.'::frontendApplicationLaunch'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Support/Application/Missing',
            __CLASS__.'::frontendApplicationMissing'
        );
        self::registerClientRoute( self::$Configuration,
            '/Sphere/Assistance/Support/Application/Fatal',
            __CLASS__.'::frontendApplicationFatal'
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
     * @return Stage
     */
    public function frontendAssistance()
    {

        $this->setupModuleNavigation();
        $View = new Stage();
        $View->setTitle( 'Hilfe' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @return void
     */
    protected function setupModuleNavigation()
    {

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Assistance/Account', 'Benutzerkonto', new QuestionIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/Assistance/Support/Application', 'Anwendungsfehler', new QuestionIcon()
        );
        if (Gatekeeper::serviceAccount()->checkIsValidSession()) {
            self::addModuleNavigationMain( self::$Configuration,
                '/Sphere/Assistance/Support/Ticket', 'Support', new QuestionIcon()
            );
        }
    }

    /**
     * @return Stage
     */
    public function frontendAccount()
    {

        $this->setupModuleNavigation();
        $this->setupFrontendAccount();
        return Account::stageWelcome();
    }

    /**
     * @return void
     */
    protected function setupFrontendAccount()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/Assistance/Account/Password/Forgotten', 'Passwort vergessen', new BookIcon()
        );

    }

    /**
     * @return Stage
     */
    public function frontendAccountForgottenPassword()
    {

        $this->setupModuleNavigation();
        $this->setupFrontendAccount();
        return Account::stageForgottenPassword();
    }

    /**
     * @return Stage
     */
    public function frontendApplication()
    {

        $this->setupModuleNavigation();
        $this->setupFrontendApplication();
        return Application::stageWelcome();
    }

    /**
     * @return void
     */
    protected function setupFrontendApplication()
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
     * @return Stage
     */
    public function frontendSupport()
    {

        $this->setupModuleNavigation();
        return Support::stageWelcome();
    }

    /**
     * @param null|string $TicketSubject
     * @param null|string $TicketMessage
     *
     * @return Stage
     */
    public function frontendSupportTicket( $TicketSubject = null, $TicketMessage = null )
    {

        $this->setupModuleNavigation();
        return Support::stageTicket( $TicketSubject, $TicketMessage );
    }

    /**
     * @return Stage
     */
    public function frontendApplicationLaunch()
    {

        $this->setupModuleNavigation();
        $this->setupFrontendApplication();
        return Application::stageLaunch();
    }

    /**
     * @return Stage
     */
    public function frontendApplicationMissing()
    {

        $this->setupModuleNavigation();
        $this->setupFrontendApplication();
        return Application::stageMissing();
    }

    /**
     * @return Stage
     */
    public function frontendApplicationFatal()
    {

        $this->setupModuleNavigation();
        $this->setupFrontendApplication();
        return Application::stageFatal();
    }
}
