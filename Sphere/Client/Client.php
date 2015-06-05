<?php
namespace KREDA\Sphere\Client;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\ORMException;
use KREDA\Sphere\Application\Assistance\Assistance;
use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Graduation\Graduation;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\System\Frontend\Database;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Application\Transfer\Transfer;
use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Container;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Screen;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\NameParameter;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\UrlParameter;
use KREDA\Sphere\Common\Extension\Debugger;
use KREDA\Sphere\Common\Signature\Type\GetSignature;
use KREDA\Sphere\Common\Signature\Type\PostSignature;
use MOC\V\Component\Database\Exception\DatabaseException;
use MOC\V\Component\Router\Component\Bridge\Repository\UniversalRouter;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Client
 *
 * @package KREDA\Client
 */
class Client
{

    /** @var null|Configuration */
    private $Configuration = null;
    /** @var null|Screen */
    private $Display = null;

    /**
     *
     */
    final public function __construct()
    {

        $this->Debug = new Debugger();
        $this->Display = new Screen();
        $this->Configuration = new Configuration( new UniversalRouter(), new LevelClient() );
    }

    final public function runPlatform()
    {

        /**
         * Prepare
         */
        $this->prepareErrorHandler();
        if ($this->runSignatureCheck() && $this->runMaintenanceCheck()) {
            /**
             * REST
             */
            if (!$this->runRestApi()) {
                /**
                 * MAIN
                 */
                try {
                    /**
                     * Assistance
                     */
                    if (false !== strpos( HttpKernel::getRequest()->getPathInfo(), '/Sphere/Assistance' )) {
                        $this->runAssistance();
                    }
                    /**
                     * Application
                     */
                    if (false === strpos( HttpKernel::getRequest()->getPathInfo(), '/Sphere/Assistance' )) {
                        $this->runApplication();
                    }
                } catch( \PDOException $Exception ) {
                    /**
                     * PDO Exception
                     */
                    $this->Display->extensionDebugger()->addProtocol( $Exception->getMessage(), 'warning-sign' );
                    $this->Display->addError( $Exception );
                } catch( DBALException $Exception ) {
                    /**
                     * Repair Database
                     */
                    $this->Display->extensionDebugger()->addProtocol( $Exception->getMessage(), 'warning-sign' );
                    $this->Display->addToContent( new Container( Database::stageRepair( $Exception ) ) );
                } catch( ORMException $Exception ) {
                    throw new \ErrorException(
                        $Exception->getMessage(), 0, $Exception->getCode(),
                        $Exception->getFile(), $Exception->getLine(), $Exception
                    );
                } catch( DatabaseException $Exception ) {
                    /**
                     * Error
                     */
                    Assistance::registerApplication( $this->Configuration );
                    $this->Configuration->getClientNavigation()->addLinkToMeta(
                        new LevelClient\Link( new UrlParameter( '/Sphere' ),
                            new NameParameter( 'Zurück zur Anwendung' ) )
                    );
                    /** @var Element $Route */
                    $Route = $this->Configuration->getClientRouter()->getRoute( '/Sphere/Assistance/Support/Application/Start' );
                    $this->Display->extensionDebugger()->addProtocol( $Exception->getMessage(), 'warning-sign' );
                    $this->Display->addToContent( new Container( $Route ) );
                } catch( \ErrorException $Exception ) {
                    /**
                     * Error Exception
                     */
                    $this->Display->extensionDebugger()->addProtocol( $Exception->getMessage(), 'warning-sign' );
                    $this->Display->addError( $Exception );
                } catch( \Exception $Exception ) {
                    /**
                     * Unexpected Exception
                     */
                    $this->Display->extensionDebugger()->addProtocol( $Exception->getMessage(), 'warning-sign' );
                    $this->Display->addException( $Exception, get_class( $Exception ) );
                }

                /**
                 * Output
                 */
                $this->prepareOutput();
                echo $this->Display->getContent();
            }
        } else {
            /**
             * Output
             */
            $this->prepareOutput();
            echo $this->Display->getContent();
        }
    }

    /**
     *
     */
    private function prepareErrorHandler()
    {

        set_error_handler(
            function ( $Code, $Message, $File, $Line ) {

                if (!preg_match( '!apc_store.*?was.*?on.*?gc-list.*?for!is', $Message )) {
                    throw new \ErrorException( $Message, 0, $Code, $File, $Line );
                }
            }, E_ALL
        );
        register_shutdown_function(
            function ( Screen $Screen, Configuration $Configuration ) {

                $Error = error_get_last();
                if (!$Error) {
                    return;
                }
                $Screen->setNavigation(
                    new Container( $Configuration->getClientNavigation() )
                );
                if ($Configuration->hasModuleNavigation()) {
                    $Screen->addToNavigation(
                        new Container( $Configuration->getModuleNavigation() )
                    );
                }
                if ($Configuration->hasApplicationNavigation()) {
                    $Screen->addToNavigation(
                        new Container( $Configuration->getApplicationNavigation() )
                    );
                }
                Assistance::registerApplication( $Configuration );
                $Configuration->getClientNavigation()->addLinkToMeta(
                    new LevelClient\Link( new UrlParameter( '/Sphere' ), new NameParameter( 'Zurück zur Anwendung' ) )
                );
                /** @var Element $Route */
                $Route = $Configuration->getClientRouter()->getRoute( '/Sphere/Assistance/Support/Application/Fatal' );
                $Screen->setContent( new Container( $Route ) );
                print $Screen->getContent();
            }, $this->Display, $this->Configuration
        );
    }

    /**
     * @return bool
     */
    private function runSignatureCheck()
    {

        if (!array_key_exists( 'REST', HttpKernel::getRequest()->getParameterArray() )) {
            $GetSignature = new GetSignature();
            $PostSignature = new PostSignature();
            if (!( $GetSignature->validateSignature() && $PostSignature->validateSignature() )) {
                Assistance::registerApplication( $this->Configuration );
                $this->Display->addToContent( new Container( Assistance::frontendApplicationSignature() ) );
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    private function runMaintenanceCheck()
    {

        if (file_exists( __DIR__.'/../../MAINTENANCE' )) {
            $Restricted = true;
            if (( $tblAccount = Gatekeeper::serviceAccount()->entityAccountBySession() )) {
                if ($tblAccount->getTblAccountRole()->getName() == 'System') {
                    $Restricted = false;
                }
            }
            if ($Restricted) {
                Assistance::registerApplication( $this->Configuration );
                /** @var Element $Route */
                $Route = $this->Configuration->getClientRouter()->getRoute( '/Sphere/Assistance/Support/Application/Update' );
                $this->Display->addToContent( new Container( $Route ) );
                return false;
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    private function runRestApi()
    {

        if (array_key_exists( 'REST', HttpKernel::getRequest()->getParameterArray() )) {
            if (Gatekeeper::serviceAccount()->checkIsValidSession()) {
                /**
                 * Application: Register
                 */
                System::registerApplication( $this->Configuration );
                Management::registerApplication( $this->Configuration );
                /**
                 * Application: Execution
                 */
                try {
                    if (Gatekeeper::serviceAccess()->checkIsValidAccess( HttpKernel::getRequest()->getPathInfo() )) {
                        /**
                         * Execution: Allowed
                         */
                        /** @var Element $Route */
                        print $this->Configuration->getClientRouter()->getRoute();
                    } else {
                        header( 'HTTP/1.1 403 Forbidden' );
                        print '403 Forbidden';
                    }
                } catch( \Exception $E ) {
                    header( 'HTTP/1.1 500 Internal Server Error' );
                    print '500 Internal Server Error -> '.$E->getMessage();
                }
            } else {
                header( 'HTTP/1.1 401 Unauthorized' );
                print '401 Unauthorized';
            }
            return true;
        }
        return false;
    }

    private function runAssistance()
    {

        /**
         * Application: Register
         */
        Assistance::registerApplication( $this->Configuration );
        $this->Configuration->getClientNavigation()->addLinkToMeta(
            new LevelClient\Link( new UrlParameter( '/Sphere' ), new NameParameter( 'Zurück zur Anwendung' ) )
        );
        /**
         * Application: Execution
         */
        try {
            /** @var Element $Route */
            $Route = $this->Configuration->getClientRouter()->getRoute();
            $this->Display->addToContent( new Container( $Route ) );
        } catch( \ErrorException $E ) {
            if (false !== strpos( $E->getMessage(), HttpKernel::getRequest()->getPathInfo() )) {
                /** @var Element $Route */
                $Route = $this->Configuration->getClientRouter()->getRoute( '/Sphere/Assistance/Support/Application/Missing' );
                $this->Debug->addProtocol( $E->getMessage(), 'warning-sign' );
                $this->Display->addToContent( new Container( $Route ) );
            } else {
                throw new \ErrorException(
                    $E->getMessage(), $E->getCode(), $E->getSeverity(), $E->getFile(), $E->getLine(), $E
                );
            }
        }
    }

    private function runApplication()
    {

        /**
         * Application: Register
         */
        if (Gatekeeper::serviceAccount()->checkIsValidSession()) {
            /**
             * Authenticated YES
             */
            System::registerApplication( $this->Configuration );
            Management::registerApplication( $this->Configuration );
            Graduation::registerApplication( $this->Configuration );
            Billing::registerApplication( $this->Configuration );
            Transfer::registerApplication( $this->Configuration );
        } else {
            /**
             * Authenticated NO
             */
        }
        /**
         * Authenticated EQUAL
         */
        Gatekeeper::registerApplication( $this->Configuration );
        Assistance::registerApplication( $this->Configuration );
        /**
         * Application: Execution
         */
        try {
            if (Gatekeeper::serviceAccess()->checkIsValidAccess( HttpKernel::getRequest()->getPathInfo() )) {
                /**
                 * Execution: Allowed
                 */
                /** @var Element $Route */
                $Route = $this->Configuration->getClientRouter()->getRoute();
            } else {
                /**
                 * Execution: Forbidden
                 */
                if (Gatekeeper::serviceAccount()->checkIsValidSession()) {
                    /** @var Element $Route */
                    $Route = $this->Configuration->getClientRouter()->getRoute( '/Sphere/Assistance/Support/Application/Missing' );
                } else {
                    /** @var Element $Route */
                    $Route = $this->Configuration->getClientRouter()->getRoute( '/Sphere/Gatekeeper/SignIn' );
                }
            }
            $this->Display->addToContent( new Container( $Route ) );
        } catch( \ErrorException $E ) {
            if (false !== strpos( $E->getMessage(), HttpKernel::getRequest()->getPathInfo() )) {
                /**
                 * Execution: Forbidden
                 */
                if (Gatekeeper::serviceAccount()->checkIsValidSession()) {
                    /** @var Element $Route */
                    $Route = $this->Configuration->getClientRouter()->getRoute( '/Sphere/Assistance/Support/Application/Missing' );
                } else {
                    /** @var Element $Route */
                    $Route = $this->Configuration->getClientRouter()->getRoute( '/Sphere/Gatekeeper/SignIn' );
                }
                $this->Display->extensionDebugger()->addProtocol( $E->getMessage(), 'warning-sign' );
                $this->Display->addToContent( new Container( $Route ) );
            } else {
                throw new \ErrorException(
                    $E->getMessage(), $E->getCode(), $E->getSeverity(), $E->getFile(), $E->getLine(), $E
                );
            }
        }
    }

    private function prepareOutput()
    {

        /**
         * Define Navigation Client
         */
        $this->Display->addToNavigation(
            new Container( $this->Configuration->getClientNavigation() )
        );
        /**
         * Define Navigation Module
         */
        if ($this->Configuration->hasModuleNavigation()) {
            $this->Display->addToNavigation(
                new Container( $this->Configuration->getModuleNavigation() )
            );
        }
        /**
         * Define Navigation Application
         */
        if ($this->Configuration->hasApplicationNavigation()) {
            $this->Display->addToNavigation(
                new Container( $this->Configuration->getApplicationNavigation() )
            );
        }
    }
}
