<?php
namespace KREDA\Sphere\Application\Gatekeeper;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Application\Gatekeeper\Client\Entrance\SignInStudent;
use KREDA\Sphere\Application\Gatekeeper\Client\Entrance\SignInTeacher;
use KREDA\Sphere\Application\Gatekeeper\Client\Main;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelApplication;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelClient;
use KREDA\Sphere\Client\Component\Element\Repository\Navigation\LevelModule;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Configuration;

class Api extends Application
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
        self::buildNavigationMeta( self::$Configuration,
            '/Sphere/Gatekeeper/SignIn', 'Anmeldung', new LockIcon()
        );
        self::buildRoute( self::$Configuration, '/', __CLASS__.'::apiMain' );
        self::buildRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn', __CLASS__.'::apiSignIn' );

        $Route = self::buildRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Teacher',
            __CLASS__.'::apiSignInTeacher' );
        $Route->setParameterDefault( 'CredentialName', null );
        $Route->setParameterDefault( 'CredentialLock', null );
        $Route->setParameterDefault( 'CredentialKey', null );

        $Route = self::buildRoute( self::$Configuration, '/Sphere/Gatekeeper/SignIn/Student',
            __CLASS__.'::apiSignInStudent' );
        $Route->setParameterDefault( 'CredentialName', null );
        $Route->setParameterDefault( 'CredentialLock', null );

        return $Configuration;
    }

    public function setupModule()
    {

        self::buildModuleMain( self::$Configuration,
            '/Sphere/Gatekeeper/SignIn/Teacher', 'Lehrer', new LockIcon()
        );
        self::buildModuleMain( self::$Configuration,
            '/Sphere/Gatekeeper/SignIn/Student', 'Schüler', new LockIcon()
        );
    }

    public function apiMain()
    {

        $this->setupModule();
        return new Main();
    }

    public function apiSignIn()
    {



        return $this->apiMain();
    }

    public function apiSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey )
    {

        $this->setupModule();

        $View = new SignInTeacher();



        return $View;
    }

    public function apiSignInStudent( $CredentialName, $CredentialLock )
    {

        $this->setupModule();

        return new SignInStudent();
    }
}
