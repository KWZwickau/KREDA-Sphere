<?php
namespace KREDA\Sphere\Common\Updater\Type;

use Github\Api\Repo;
use KREDA\Sphere\Common\AbstractExtension;
use KREDA\Sphere\Common\Proxy\Type\HttpProxy;
use KREDA\Sphere\Common\Updater\IUpdaterInterface;

/**
 * Class GitHub
 *
 * @package KREDA\Sphere\Common\Updater\Type
 */
class GitHub extends AbstractExtension implements IUpdaterInterface
{

    /** @var null|string $User */
    private $User = null;
    /** @var null|string $Repository */
    private $Repository = null;
    /** @var null|string $Version */
    private $Version = '0.0.0-Install';

    /**
     * @throws \Exception
     */
    function __construct()
    {

        $Config = __DIR__.'/../Config/GitHub.ini';
        if (false !== ( $Config = realpath( $Config ) )) {
            $Setting = parse_ini_file( $Config, true );
            if (isset( $Setting['User'] )) {
                $this->User = $Setting['User'];
            }
            if (isset( $Setting['Repository'] )) {
                $this->Repository = $Setting['Repository'];
            }
            if (isset( $Setting['Version'] )) {
                $this->Version = $Setting['Version'];
            }
        } else {
            throw new \Exception( 'Missing Updater-Configuration for '.get_class( $this ) );
        }
    }

    public function downloadVersion( $Version )
    {

        $Url = $this->getZipArchiveUrl( $Version );
        $Filename = __DIR__.'/../Cache/'.hash( 'sha256', $Url ).'.zip';

        $Proxy = new HttpProxy();
        $Option = array(
            CURLOPT_PROXY        => $Proxy->getHost(),
            CURLOPT_PROXYPORT    => $Proxy->getPort(),
            CURLOPT_PROXYUSERPWD => $Proxy->getUsernamePasswort(),
            CURLOPT_FILE         => fopen( $Filename, 'wb' )
        );
        $Option = array_filter( $Option );

        $Result = self::getRequest( $Url, $Option );

        return $Result;
    }

    /**
     * @param string $Version
     *
     * @return string mixed
     */
    private function getZipArchiveUrl( $Version )
    {

        $Tags = $this->fetchTags();
        array_walk( $Tags, function ( &$Tag, $I, $Version ) {

            if ($Tag['name'] != $Version) {
                $Tag = false;
            }
        }, $Version );
        $Tags = array_filter( $Tags );
        $Tags = current( $Tags );
        return $Tags['zipball_url'];
    }

    /**
     * @return array
     */
    public function fetchTags()
    {

        /** @var Repo $Api */
        $Api = $this->extensionGitHub()->api( 'repo' );
        return $Api->tags( $this->User, $this->Repository );
    }

    private static function getRequest( $UrlRequestList, $CurlOptionList = array() )
    {

        $CurlHandleList = array();
        $ResultData = array();
        $CurlHandler = curl_multi_init();
        foreach ((array)$UrlRequestList as $Identifier => $UrlRequest) {
            $CurlHandleList[$Identifier] = curl_init( $UrlRequest );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_USERAGENT, "KREDA Curl Handler" );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_VERBOSE, true );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_HEADER, false );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_FAILONERROR, true );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_FOLLOWLOCATION, true );
            if (!empty( $CurlOptionList )) {
                curl_setopt_array( $CurlHandleList[$Identifier], $CurlOptionList );
            }
            curl_multi_add_handle( $CurlHandler, $CurlHandleList[$Identifier] );
        }
        $IsRunning = null;
        do {
            curl_multi_exec( $CurlHandler, $IsRunning );
        } while ($IsRunning > 0);
        foreach ($CurlHandleList as $Identifier => $CurlHandle) {
            $ResultData[$Identifier] = curl_multi_getcontent( $CurlHandle );
            curl_multi_remove_handle( $CurlHandler, $CurlHandle );
        }
        curl_multi_close( $CurlHandler );

        return $ResultData;
    }

    /**
     * @return array
     */
    public function fetchReleases()
    {

        /** @var Repo $Api */
        $Api = $this->extensionGitHub()->api( 'repo' );
        return $Api->releases()->all( $this->User, $this->Repository );
    }

    /**
     * @return string
     */
    public function getNextVersion()
    {

        $Tags = $this->fetchTags();
        $NextVersion = $this->getCurrentVersion();
        foreach ((array)$Tags as $Tag) {
            if ($this->compareVersion( $Tag['name'], $this->getCurrentVersion() )) {
                $NextVersion = $Tag['name'];
            } else {
                print '<div>'.$this->getCurrentVersion().' > '.$Tag['name'].' Not allowed</div>';
            }
        }
        return $NextVersion;
    }

    /**
     * @return string
     */
    public function getCurrentVersion()
    {

        return $this->Version;
    }

    /**
     * @param string $Current
     * @param string $New
     *
     * @return bool
     */
    public function compareVersion( $New, $Current )
    {

        return version_compare( $New, $Current, '>' );
    }

    /**
     * @return string
     */
    public function getLatestVersion()
    {

        $Tags = $this->fetchTags();
        foreach ((array)$Tags as $Tag) {
            if ($this->compareVersion( $Tag['name'], $this->getCurrentVersion() )) {
                return $Tag['name'];
            } else {
                print '<div>'.$this->getCurrentVersion().' > '.$Tag['name'].' Not allowed</div>';
            }
        }
        return $this->getCurrentVersion();
    }
}
