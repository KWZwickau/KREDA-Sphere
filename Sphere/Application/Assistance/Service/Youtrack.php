<?php
namespace KREDA\Sphere\Application\Assistance\Service;

use KREDA\Sphere\Application\Assistance\Client\Aid\Cause\Danger;
use KREDA\Sphere\Application\Assistance\Client\Aid\Solution\Support;
use KREDA\Sphere\Application\Service;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;

/**
 * Class Youtrack
 *
 * @package KREDA\Sphere\Application\Assistance\Service
 */
class Youtrack extends Service
{

    /** @var string $YoutrackDomain */
    //private $YoutrackDomain = 'http://ticket.swe.haus-der-edv.de';
    private $YoutrackDomain = 'http://192.168.33.150:8080';
    /** @var null|string $Cookie */
    private $Cookie = null;
    /** @var null|array $CookieList */
    private $CookieList = null;

    /**
     * @param string $TicketSubject
     * @param string $TicketMessage
     *
     * @return Stage
     */
    public function apiTicket( $TicketSubject, $TicketMessage )
    {

        $View = new Stage();
        $View->setTitle( 'Support' );
        $View->setDescription( 'Ticket erstellen' );
        $View->setMessage( '' );

        $Ticket = new Youtrack\Youtrack( $TicketSubject, $TicketMessage );
        $Error = false;
        if (empty( $TicketSubject ) && null !== $TicketSubject) {
            $Ticket->setErrorEmptySubject();
            $Error = true;
        } elseif (null === $TicketSubject) {
            $Error = true;
        }
        if (empty( $TicketMessage ) && null !== $TicketMessage) {
            $Ticket->setErrorEmptyMessage();
            $Error = true;
        } elseif (null === $TicketMessage) {
            $Error = true;
        }

        if ($Error) {
            /**
             * Nothing to do
             */
            $View->setContent( $Ticket.$this->ticketCurrent() );
        } else {
            /**
             * Submit Ticket
             */
            $View = new Stage();
            $View->setTitle( 'Support' );
            $View->setDescription( 'Ticket erstellen' );
            $View->setMessage( '' );
            try {
                $this->ticketCreate( $TicketSubject, $TicketMessage );
                $View->setContent( new Support( 'Das Problem wurde erfolgreich dem Support mitgeteilt' )
                    .$this->ticketCurrent()
                );
            } catch( \Exception $E ) {
                $View->setContent( new Danger( 'Das Ticket konnte nicht übertragen werden' )
                    .$this->ticketCurrent()
                );
            }
        }
        return $View;
    }

    /**
     * @return string
     */
    private function ticketCurrent()
    {

        $Issues = $this->ticketList();

        foreach ((array)$Issues as $Index => $Content) {
            if (!isset( $Content[1] )) {
                $Content[1] = '';
            }
            $Issues[$Index] = '<div class="alert alert-info"><strong>'.$Content[0].'</strong><hr/><small>'.nl2br( $Content[1] ).'</small></div>';
        }
        if (empty( $Issues )) {
            $Issues[0] = '<div>Keine Supportanfragen vorhanden</div>';
        }
        rsort( $Issues );
        return '<br/><h3>Tickets<small> Aktuelle Anfragen</small></h3>'.implode( '', $Issues );
    }

    /**
     * @return array
     */
    private function ticketList()
    {

        $this->ticketLogin();
        $CurlHandler = curl_init();
        curl_setopt( $CurlHandler, CURLOPT_URL,
            $this->YoutrackDomain.'/rest/issue/byproject/KREDA?filter='.urlencode( 'Status: -Gelöst Ersteller: KREDA-Support' )
        );
        curl_setopt( $CurlHandler, CURLOPT_HEADER, false );
        curl_setopt( $CurlHandler, CURLOPT_VERBOSE, false );
        curl_setopt( $CurlHandler, CURLOPT_COOKIE, $this->Cookie );
        curl_setopt( $CurlHandler, CURLOPT_RETURNTRANSFER, 1 );

        $Response = curl_exec( $CurlHandler );
        curl_close( $CurlHandler );

        $Response = simplexml_load_string( $Response );

        $Summary = $Response->xpath( '//issues/issue/field[@name="summary"]' );
        $Description = $Response->xpath( '//issues/issue/field[@name="description"]' );

        $Issues = array();
        $Run = 0;
        foreach ($Summary as $Title) {
            foreach ($Title->children() as $Value) {
                $Issues[$Run] = array( (string)$Value );
            }
            $Run++;
        }
        $Run = 0;
        foreach ($Description as $Message) {
            foreach ($Message->children() as $Value) {
                array_push( $Issues[$Run], (string)$Value );
            }
            $Run++;
        }
        return $Issues;
    }

    /**
     * @throws \Exception
     * @return null
     */
    private function ticketLogin()
    {

        $CurlHandler = curl_init();
        curl_setopt( $CurlHandler, CURLOPT_URL, $this->YoutrackDomain.'/rest/user/login' );
        curl_setopt( $CurlHandler, CURLOPT_POST, true );
        curl_setopt( $CurlHandler, CURLOPT_POSTFIELDS, 'login=KREDA-Support&password=Professional' );
        curl_setopt( $CurlHandler, CURLOPT_HEADER, false );
        curl_setopt( $CurlHandler, CURLOPT_HEADERFUNCTION, array( $this, 'ticketHeader' ) );
        curl_setopt( $CurlHandler, CURLOPT_RETURNTRANSFER, true );

        $Response = curl_exec( $CurlHandler );
        $Response = simplexml_load_string( $Response );

        if ($Response != 'ok') {
            throw new \Exception();
        }

        curl_close( $CurlHandler );
    }

    /**
     * @param string $Summary
     * @param string $Description
     *
     * @return array
     */
    private function ticketCreate( $Summary, $Description )
    {

        $this->ticketLogin();
        $CurlHandler = curl_init();
        curl_setopt( $CurlHandler, CURLOPT_URL,
            $this->YoutrackDomain.'/rest/issue?project=KREDA&summary='.urlencode( $Summary ).'&description='.urlencode( $Description )
        );
        curl_setopt( $CurlHandler, CURLOPT_HEADER, false );
        curl_setopt( $CurlHandler, CURLOPT_VERBOSE, false );
        curl_setopt( $CurlHandler, CURLOPT_PUT, true );
        curl_setopt( $CurlHandler, CURLOPT_COOKIE, $this->Cookie );
        curl_setopt( $CurlHandler, CURLOPT_RETURNTRANSFER, 1 );

        var_dump( curl_exec( $CurlHandler ) );
        curl_close( $CurlHandler );
    }

    /**
     * @return string
     */
    public function setupDataStructure()
    {

        $this->addInstallProtocol( __CLASS__ );

        return $this->getInstallProtocol();
    }

    /**
     * @param \resource $CurlHandler
     * @param string    $String
     *
     * @return int
     */
    private function ticketHeader(
        /** @noinspection PhpUnusedParameterInspection */
        $CurlHandler,
        $String
    ) {

        $Length = strlen( $String );
        if (!strncmp( $String, "Set-Cookie:", 11 )) {
            $CookieValue = trim( substr( $String, 11, -1 ) );
            $this->Cookie = explode( "\n", $CookieValue );
            $this->Cookie = explode( '=', $this->Cookie[0] );
            $CookieName = trim( array_shift( $this->Cookie ) );
            $this->CookieList[$CookieName] = trim( implode( '=', $this->Cookie ) );
        }
        $this->Cookie = "";
        if (trim( $String ) == "") {
            foreach ($this->CookieList as $Key => $Value) {
                $this->Cookie .= "$Key=$Value; ";
            }
        }
        return $Length;
    }
}
