<?php
namespace KREDA\Sphere\Client;

use Doctrine\DBAL\DBALException;
use KREDA\Sphere\Application\Assistance\Assistance;
use KREDA\Sphere\Application\Demo\Demo;
use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Graduation\Graduation;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\System\Frontend\Database;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Container;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Screen;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\NameParameter;
use KREDA\Sphere\Client\Component\Parameter\Repository\Link\UrlParameter;
use KREDA\Sphere\Common\Extension\Debugger;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageInfo;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageSuccess;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
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
            $this->runRestApi();
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
            } catch( \PDOException $E ) {
                /**
                 * PDO Exception
                 */
                $this->Display->extensionDebugger()->addProtocol( $E->getMessage(), 'warning-sign' );
                $this->Display->addError( $E );
            } catch( DBALException $E ) {
                /**
                 * Repair Database
                 */
                $this->Display->extensionDebugger()->addProtocol( $E->getMessage(), 'warning-sign' );
                $this->Display->addToContent( new Container( Database::stageRepair( $E ) ) );
            } catch( DatabaseException $E ) {
                /**
                 * Error
                 */
                Assistance::registerApplication( $this->Configuration );
                $this->Configuration->getClientNavigation()->addLinkToMeta(
                    new LevelClient\Link( new UrlParameter( '/Sphere' ), new NameParameter( 'Zurück zur Anwendung' ) )
                );
                /** @var Element $Route */
                $Route = $this->Configuration->getClientRouter()->getRoute( '/Sphere/Assistance/Support/Application/Start' );
                $this->Display->extensionDebugger()->addProtocol( $E->getMessage(), 'warning-sign' );
                $this->Display->addToContent( new Container( $Route ) );
            } catch( \ErrorException $E ) {
                /**
                 * Error Exception
                 */
                $this->Display->extensionDebugger()->addProtocol( $E->getMessage(), 'warning-sign' );
                $this->Display->addError( $E );
            } catch( \Exception $E ) {
                /**
                 * Unexpected Exception
                 */
                $this->Display->extensionDebugger()->addProtocol( $E->getMessage(), 'warning-sign' );
                $this->Display->addException( $E, get_class( $E ) );
            }
        }
        /**
         * Output
         */
        $this->prepareOutput();
        echo $this->Display->getContent();
    }

    /**
     *
     */
    private function prepareErrorHandler()
    {

        set_error_handler(
            function ( $N, $S, $F, $L ) {

                if (!preg_match( '!apc_store.*?was.*?on.*?gc-list.*?for!is', $S )) {
                    throw new \ErrorException( $S, 0, $N, $F, $L );
                }
            }, E_ALL
        );
        register_shutdown_function(
            function ( Screen $S, Configuration $C ) {

                $Error = error_get_last();
                if (!$Error) {
                    return;
                }
                $S->setNavigation(
                    new Container( $C->getClientNavigation() )
                );
                if ($C->hasModuleNavigation()) {
                    $S->addToNavigation(
                        new Container( $C->getModuleNavigation() )
                    );
                }
                if ($C->hasApplicationNavigation()) {
                    $S->addToNavigation(
                        new Container( $C->getApplicationNavigation() )
                    );
                }
                Assistance::registerApplication( $C );
                $C->getClientNavigation()->addLinkToMeta(
                    new LevelClient\Link( new UrlParameter( '/Sphere' ), new NameParameter( 'Zurück zur Anwendung' ) )
                );
                /** @var Element $R */
                $R = $C->getClientRouter()->getRoute( '/Sphere/Assistance/Support/Application/Fatal' );
                $S->setContent( new Container( $R ) );
                print $S->getContent();
                exit( 0 );
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
                $Stage = new Stage();
                $Stage->setTitle( 'KREDA Sicherheit' );
                $Stage->setDescription( 'Parameter' );
                $Stage->setContent(
                    new MessageDanger( 'Das System hat fehlerhafte oder mutwillig veränderte Eingabedaten erkannt',
                        new WarningIcon()
                    )
                    .new MessageWarning( 'Bitte ändern Sie keine Daten in der Url',
                        new WarningIcon()
                    )
                    .new MessageInfo( 'Bitte führen Sie Anfragen an das System nicht über Tagesgrenzen hinweg aus',
                        new WarningIcon()
                    )
                    .new MessageSuccess( 'Alle Parameter wurden entfernt',
                        new WarningIcon()
                    )
                );
                Assistance::registerApplication( $this->Configuration );
                $this->Display->addToContent( new Container( $Stage ) );
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

        if (file_exists( __DIR__.'/MAINTENANCE' )) {
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
     *
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
            exit( 0 );
        }

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
            Demo::registerApplication( $this->Configuration );
            Graduation::registerApplication( $this->Configuration );
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
