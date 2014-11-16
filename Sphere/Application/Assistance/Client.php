<?php
namespace KREDA\Sphere\Application\Assistance;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BookIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuestionIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Client
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
    public static function setupApi(Configuration $Configuration)
    {

        self::$Configuration = $Configuration;
        self::addClientNavigationMeta(self::$Configuration,
            '/Sphere/Assistance', 'Hilfe', new QuestionIcon()
        );
        self::buildRoute(self::$Configuration, '/Sphere/Assistance', __CLASS__ . '::apiMain');
        self::buildRoute(self::$Configuration, '/Sphere/Assistance/Support', __CLASS__ . '::apiMain');
        self::buildRoute(self::$Configuration, '/Sphere/Assistance/Support/Application',
            __CLASS__ . '::apiSupportApplication');
        self::buildRoute(self::$Configuration, '/Sphere/Assistance/Support/Application/StartUp',
            __CLASS__ . '::apiSupportApplicationStartUp');
        self::buildRoute(self::$Configuration, '/Sphere/Assistance/Support/Account', __CLASS__ . '::apiMain');
        return $Configuration;
    }

    /**
     * @return Landing
     */
    public function apiMain()
    {

        $this->setupModuleNavigation();
        $View = new Landing();
        $View->setTitle('Hilfe');
        $View->setMessage('Bitte wählen Sie ein Thema');
        return $View;
    }

    public function setupModuleNavigation()
    {
        self::addModuleNavigationMain(self::$Configuration,
            '/Sphere/Assistance/Support/Application', 'Anwendungsfehler', new BookIcon()
        );
        self::addApplicationNavigationMain(self::$Configuration,
            '/Sphere/Assistance/Support/Application/StartUp', 'Starten der Anwendung', new BookIcon()
        );
    }

    /**
     * @return Landing
     */
    public function apiSupportApplication()
    {

        $this->setupModuleNavigation();
        $View = new Landing();
        $View->setTitle('Anwendungsfehler');
        $View->setMessage('Bitte wählen Sie ein Thema');
        return $View;
    }

    /**
     * @return Landing
     */
    public function apiSupportApplicationStartUp()
    {

        $this->setupModuleNavigation();
        $View = new Landing();
        $View->setTitle('Hilfe');
        $View->setDescription('Starten der Anwendung');
        $View->setMessage('Nach Aufruf der Anwendung arbeitet diese nicht wie erwartet');
        $View->setContent(
            '<h2 class="text-left"><small>Mögliche Ursachen</small></h2>'
            . '<div class="alert alert-info text-left"><span class="glyphicon glyphicon-info-sign"></span> Dieser Bereich der Anwendung wird eventuell gerade gewartet</div>'
            . '<div class="alert alert-warning text-left"><span class="glyphicon glyphicon-info-sign"></span> Die Anwendung kann wegen Kapazitätsproblemen im Moment nicht verwendet werden</div>'
            . '<div class="alert alert-danger text-left"><span class="glyphicon glyphicon-exclamation-sign"></span> Die interne Kommunikation der Anwendung mit weiteren, notwendigen Resourcen zum Beispiel Datenbanken kann gestört sein</div>'
            . '<h2 class="text-left"><small>Mögliche Lösungen</small></h2>'
            . '<div class="alert alert-info text-left"><span class="glyphicon glyphicon-info-sign"></span> Versuchen Sie die Anwendung zu einem späteren Zeitpunkt erneut aufzurufen</div>'
            . '<div class="alert alert-success text-left"><span class="glyphicon glyphicon-exclamation-sign"></span> Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann</div>'
        );
        return $View;
    }
}
