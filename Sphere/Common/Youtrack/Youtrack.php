<?php
namespace KREDA\Sphere\Common\Youtrack;

use KREDA\Sphere\Common\AbstractExtension;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageInfo;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormTitle;

/**
 * Class Youtrack
 *
 * @package KREDA\Sphere\Common\Youtrack
 */
class Youtrack extends AbstractExtension
{

    /** @var string $YoutrackDomain */
    private $YoutrackDomain = '';
    /** @var string $YoutrackUser */
    private $YoutrackUser = '';
    /** @var string $YoutrackPassword */
    private $YoutrackPassword = '';
    /** @var null|string $Cookie */
    private $Cookie = null;
    /** @var null|array $CookieList */
    private $CookieList = null;

    /**
     * @throws \Exception
     */
    function __construct()
    {

        $Config = __DIR__.'/Config/Youtrack.ini';
        if (false !== ( $Config = realpath( $Config ) )) {
            $Setting = parse_ini_file( $Config, true );
            if (isset( $Setting['Host'] )) {
                $this->YoutrackDomain = $Setting['Host'];
            }
            if (isset( $Setting['Username'] )) {
                $this->YoutrackUser = $Setting['Username'];
            }
            if (isset( $Setting['Password'] )) {
                $this->YoutrackPassword = $Setting['Password'];
            }
        } else {
            throw new \Exception( 'Missing Proxy-Configuration for '.get_class( $this ) );
        }
    }

    /**
     * @return GridFormGroup
     */
    public function ticketCurrent()
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

            $Issues[$Index] = new MessageInfo(
                '<strong>'.$Content[0].'</strong>'
                .'<div class="pull-right label '.$Label.'"><samp>'.$Content[2].'</samp></div>'
                .'<hr/><small>'.nl2br( $Content[1] ).'</small>'
            );
        }
        if (empty( $Issues )) {
            $Issues[0] = new MessageInfo( 'Keine Supportanfragen vorhanden' );
        }
        krsort( $Issues );
        return new GridFormGroup(
            new GridFormRow(
                new GridFormCol(
                    $Issues
                )
            ), new GridFormTitle( 'Tickets', 'Aktuelle Anfragen' )
        );
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
    public function ticketCreate( $Summary, $Description )
    {

        $Markdown = $this->extensionMarkdownify();
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
