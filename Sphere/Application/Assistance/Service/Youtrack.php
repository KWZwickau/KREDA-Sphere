<?php
namespace KREDA\Sphere\Application\Assistance\Service;

use KREDA\Sphere\Application\Assistance\Client\Aid\Cause\Danger;
use KREDA\Sphere\Application\Assistance\Client\Aid\Solution\Support;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractService;
use Markdownify\Converter;

/**
 * Class Youtrack
 *
 * @package KREDA\Sphere\Application\Assistance\Service
 */
class Youtrack extends AbstractService
{

    /** @var string $YoutrackDomain */
//    private $YoutrackDomain = 'http://ticket.swe.haus-der-edv.de';
    private $YoutrackDomain = 'http://192.168.33.150:8080';
    /** @var string $YoutrackUser */
    private $YoutrackUser = 'KREDA-Support';
    /** @var string $YoutrackPassword */
    private $YoutrackPassword = 'Professional';
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
            try {
                $View->setContent( $Ticket.$this->ticketCurrent() );
            } catch( \Exception $E ) {
                $View->setContent( new Danger( 'Das Support-System konnten nicht geladen werden' ) );
            }
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
                $View->setContent( new Danger( 'Das Problem konnte nicht übertragen werden' ) );
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
            if (!isset( $Content[1] )) {
                $Content[2] = '';
            }
            switch (strtoupper( $Content[2] )) {
                case 'ERFASST': {
                    $Label = 'label-primary';
                    break;
                }
                case 'ZU BESPRECHEN': {
                    $Label = 'label-warning';
                    break;
                }
                case 'OFFEN': {
                    $Label = 'label-danger';
                    break;
                }
                case 'IN BEARBEITUNG': {
                    $Label = 'label-success';
                    break;
                }
                default:
                    $Label = 'label-default';

            }

            $Issues[$Index] = '<div class="alert alert-info">'
                .'<strong>'.$Content[0].'</strong>'
                .'<div class="pull-right label '.$Label.'"><samp>'.$Content[2].'</samp></div>'
                .'<hr/><small>'.nl2br( $Content[1] ).'</small>'
                .'</div>';
        }
        if (empty( $Issues )) {
            $Issues[0] = '<div>Keine Supportanfragen vorhanden</div>';
        }
        krsort( $Issues );
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
        $Status = $Response->xpath( '//issues/issue/field[@name="State"]' );

        $Issues = array();
        /**
         * [0] - Title
         */
        $Run = 0;
        foreach ($Summary as $Title) {
            foreach ($Title->children() as $Value) {
                $Issues[$Run] = array( (string)$Value );
            }
            $Run++;
        }
        /**
         * [1] - Description
         */
        $Run = 0;
        foreach ($Description as $Message) {
            foreach ($Message->children() as $Value) {
                array_push( $Issues[$Run], (string)$Value );
            }
            $Run++;
        }
        /**
         * [2] - Status
         */
        $Run = 0;
        foreach ($Status as $Message) {
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
        curl_setopt( $CurlHandler, CURLOPT_POSTFIELDS,
            'login='.$this->YoutrackUser.'&password='.$this->YoutrackPassword );
        curl_setopt( $CurlHandler, CURLOPT_HEADER, false );
        curl_setopt( $CurlHandler, CURLOPT_HEADERFUNCTION, array( $this, 'ticketHeader' ) );
        curl_setopt( $CurlHandler, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $CurlHandler, CURLOPT_TIMEOUT, 2 );

        $Response = curl_exec( $CurlHandler );
        $Response = simplexml_load_string( $Response );

        if (false === $Response || $Response != 'ok') {
            throw new \Exception();
        }

        curl_close( $CurlHandler );
    }

    /**
     * @param string $Summary
     * @param string $Description
     *
     * @throws \Exception
     * @return array
     */
    private function ticketCreate( $Summary, $Description )
    {

        $Markdown = new Converter();
        $Markdown->setKeepHTML( false );
        $Summary = $Markdown->parseString( $Summary );
        $Description = $Markdown->parseString( $Description );

        $this->ticketLogin();
        $CurlHandler = curl_init();
        curl_setopt( $CurlHandler, CURLOPT_URL,
            $this->YoutrackDomain.'/rest/issue?project=KREDA&summary='.urlencode( $Summary ).'&description='.urlencode( $Description )
        );
        curl_setopt( $CurlHandler, CURLOPT_HEADER, false );
        curl_setopt( $CurlHandler, CURLOPT_VERBOSE, false );
        curl_setopt( $CurlHandler, CURLOPT_PUT, true );
        curl_setopt( $CurlHandler, CURLOPT_COOKIE, $this->Cookie );
        curl_setopt( $CurlHandler, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $CurlHandler, CURLOPT_TIMEOUT, 2 );

        $Response = curl_exec( $CurlHandler );
        $Response = simplexml_load_string( $Response );

        if (false === $Response) {
            throw new \Exception();
        }

        curl_close( $CurlHandler );
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
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
