<?php
namespace KREDA\Sphere\Application\Assistance;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Application\Assistance\Client\Aid\Cause\Danger;
use KREDA\Sphere\Application\Assistance\Client\Aid\Cause\Time;
use KREDA\Sphere\Application\Assistance\Client\Aid\Cause\Warning;
use KREDA\Sphere\Application\Assistance\Client\Aid\Solution\Support;
use KREDA\Sphere\Application\Assistance\Client\Aid\Solution\User;
use KREDA\Sphere\Application\Assistance\Client\Ticket\Youtrack;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
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
    private $Cookie = null;
    private $CookieList = null;

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
        self::buildRoute(self::$Configuration, '/Sphere/Assistance/Support/Ticket', __CLASS__ . '::apiSupportTicket');
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
     * @param string $TicketSubject
     * @param string $TicketMessage
     * @return Stage
     */
    public function apiSupportTicket($TicketSubject = null, $TicketMessage = null)
    {
        $this->setupModuleNavigation();

        $View = new Stage();
        $View->setTitle('Support');
        $View->setDescription('Ticket erstellen');
        $View->setMessage('<div class="text-danger">Bitte teilen Sie uns so genau wie möglich mit wie es zu diesem Problem kam</div>');
        $Ticket = new Youtrack($TicketSubject, $TicketMessage);
        $Error = false;
        if (empty($TicketSubject) && null !== $TicketSubject) {
            $Ticket->setErrorEmptySubject();
            $Error = true;
        } elseif (null === $TicketSubject) {
            $Error = true;
        }
        if (empty($TicketMessage) && null !== $TicketMessage) {
            $Ticket->setErrorEmptyMessage();
            $Error = true;
        } elseif (null === $TicketMessage) {
            $Error = true;
        }
        if ($Error) {
            $Issues = $this->ticketList();

            foreach ((array)$Issues as $Index => $Content) {
                if (!isset($Content[1])) {
                    $Content[1] = '';
                }
                $Issues[$Index] = '<div class="alert alert-info"><strong>' . $Content[0] . '</strong><hr/><small>' . nl2br($Content[1]) . '</small></div>';
            }
            if (empty($Issues)) {
                $Issues[0] = '<div>Keine Supportanfragen vorhanden</div>';
            }

            $View->setContent($Ticket . '<h2><small>Aktuelle Supportanfragen</small></h2>' . implode('', $Issues));
        } else {

            $this->ticketLogin();
            $this->ticketCreate($TicketSubject, $TicketMessage);
            $View = new Stage();
            $View->setTitle('Support');
            $View->setDescription('Ticket erstellen');
            $View->setMessage('');
            $View->setContent(new Support('Das Problem wurde erfolgreich dem Support mitgeteilt'));
        }
        return $View;
    }

    /**
     * @return array
     */
    private function ticketList()
    {
        $this->ticketLogin();
        $CurlHandler = curl_init();
        curl_setopt($CurlHandler, CURLOPT_URL,
            'http://ticket.swe.haus-der-edv.de/rest/issue/byproject/KREDA?filter=' . urlencode('Status: -Gelöst Ersteller: KREDA-Support')
        );
        curl_setopt($CurlHandler, CURLOPT_HEADER, false);
        curl_setopt($CurlHandler, CURLOPT_VERBOSE, false);
        curl_setopt($CurlHandler, CURLOPT_COOKIE, $this->Cookie);
        curl_setopt($CurlHandler, CURLOPT_RETURNTRANSFER, 1);

        $Response = curl_exec($CurlHandler);
        curl_close($CurlHandler);

        $Response = simplexml_load_string($Response);

        $Summary = $Response->xpath('//issues/issue/field[@name="summary"]');
        $Description = $Response->xpath('//issues/issue/field[@name="description"]');

        $Issues = array();
        $Run = 0;
        foreach ($Summary as $Title) {
            foreach ($Title->children() as $Value) {
                $Issues[$Run] = array((string)$Value);
            }
            $Run++;
        }
        $Run = 0;
        foreach ($Description as $Message) {
            foreach ($Message->children() as $Value) {
                array_push($Issues[$Run], (string)$Value);
            }
            $Run++;
        }
        return $Issues;
    }

    /**
     * @return null
     */
    private function ticketLogin()
    {
        $LoginAddress = 'http://ticket.swe.haus-der-edv.de/rest/user/login';
        $LoginData = 'login=KREDA-Support&password=Professional';

        $CurlHandler = curl_init();
        curl_setopt($CurlHandler, CURLOPT_URL, $LoginAddress);
        curl_setopt($CurlHandler, CURLOPT_POST, true);
        curl_setopt($CurlHandler, CURLOPT_POSTFIELDS, $LoginData);
        curl_setopt($CurlHandler, CURLOPT_HEADER, false);
        curl_setopt($CurlHandler, CURLOPT_HEADERFUNCTION, array($this, 'ticketHeader'));
        curl_setopt($CurlHandler, CURLOPT_RETURNTRANSFER, true);
        curl_exec($CurlHandler);
        curl_close($CurlHandler);
    }

    /**
     * @param string $Summary
     * @param string $Description
     * @return array
     */
    private function ticketCreate($Summary, $Description)
    {
        $this->ticketLogin();
        $CurlHandler = curl_init();
        curl_setopt($CurlHandler, CURLOPT_URL,
            'http://ticket.swe.haus-der-edv.de/rest/issue?project=KREDA&summary=' . urlencode($Summary) . '&description=' . urlencode($Description)
        );
        curl_setopt($CurlHandler, CURLOPT_HEADER, false);
        curl_setopt($CurlHandler, CURLOPT_VERBOSE, false);
        curl_setopt($CurlHandler, CURLOPT_PUT, true);
        curl_setopt($CurlHandler, CURLOPT_COOKIE, $this->Cookie);
        curl_setopt($CurlHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($CurlHandler);
        curl_close($CurlHandler);
    }

    /**
     * @return Stage
     */
    public function apiSupportApplicationStartUp()
    {

        $this->setupModuleNavigation();
        $View = new Stage();
        $View->setTitle('Hilfe');
        $View->setDescription('Starten der Anwendung');
        $View->setMessage('<strong>Problem:</strong> Nach Aufruf der Anwendung arbeitet diese nicht wie erwartet');
        $View->setContent(
            '<h2 class="text-left"><small>Mögliche Ursachen</small></h2>'
            . new Time('Dieser Bereich der Anwendung wird eventuell gerade gewartet')
            . new Warning('Die Anwendung kann wegen Kapazitätsproblemen im Moment nicht verwendet werden')
            . new Danger('Die interne Kommunikation der Anwendung mit weiteren, notwendigen Resourcen zum Beispiel Datenbanken kann gestört sein')
            . '<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            . new User('Versuchen Sie die Anwendung zu einem späteren Zeitpunkt erneut aufzurufen')
            . new Support('Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann')
        );
        $View->addButton('/Sphere/Assistance/Support/Ticket?TicketSubject=Starten der Anwendung', 'Support-Ticket');
        return $View;
    }

    /**
     * @param \resource $CurlHandler
     * @param string $String
     * @return int
     */
    private function ticketHeader($CurlHandler, $String)
    {
        $Length = strlen($String);
        if (!strncmp($String, "Set-Cookie:", 11)) {
            $CookieValue = trim(substr($String, 11, -1));
            $this->Cookie = explode("\n", $CookieValue);
            $this->Cookie = explode('=', $this->Cookie[0]);
            $CookieName = trim(array_shift($this->Cookie));
            $this->CookieList[$CookieName] = trim(implode('=', $this->Cookie));
        }
        $this->Cookie = "";
        if (trim($String) == "") {
            foreach ($this->CookieList as $Key => $Value) {
                $this->Cookie .= "$Key=$Value; ";
            }
        }
        return $Length;
    }
}
