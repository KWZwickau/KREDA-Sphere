<?php
namespace KREDA\Sphere\Common\Updater\Component;

use KREDA\Sphere\Common\Proxy\Type\HttpProxy;

/**
 * Class CurlHandler
 *
 * @package KREDA\Sphere\Common\Updater\Component
 */
class CurlHandler extends GitApi
{

    private static $DownloadSizeTotal = 0;
    private static $DownloadSizeCurrent = 0;
    private static $DownloadSpeed = 0;
    private static $DownloadTime = 0;

    /** @var string $Cache */
    private $Cache = '';

    /**
     * @throws \Exception
     */
    function __construct()
    {

        parent::__construct();
        $this->Cache = realpath( __DIR__.'/../../../../Update' );
    }

    /**
     * @return string
     */
    public function getCache()
    {

        return $this->Cache;
    }

    /**
     * @param $Version
     *
     * @return string
     */
    public function downloadVersion( $Version )
    {

        $Url = $this->getZipArchiveUrl( $Version );
        $Filename = $this->Cache.'/'.hash( 'sha256', $Version ).'.zip';

        if (file_exists( $Filename ) && 0 != filesize( $Filename )) {
            return realpath( $Filename );
        } else {
            $Proxy = new HttpProxy();
            $Option = array(
                CURLOPT_PROXY        => $Proxy->getHost(),
                CURLOPT_PROXYPORT    => $Proxy->getPort(),
                CURLOPT_PROXYUSERPWD => $Proxy->getUsernamePasswort(),
                CURLOPT_FILE         => fopen( $Filename, 'wb' )
            );
            $Option = array_filter( $Option );

            self::getRequest( $Url, $Option );
            return realpath( $Filename );
        }
    }

    /**
     * @param string $Version
     *
     * @return string mixed
     */
    private function getZipArchiveUrl( $Version )
    {

        $Tags = $this->fetchTags();
        /** @noinspection PhpUnusedParameterInspection */
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
     * @param string $UrlRequestList
     * @param array  $CurlOptionList
     *
     * @return array
     */
    private static function getRequest( $UrlRequestList, $CurlOptionList = array() )
    {

        $CurlHandleList = array();
        $ResultData = array();
        $CurlHandler = curl_multi_init();
        foreach ((array)$UrlRequestList as $Identifier => $UrlRequest) {
            $CurlHandleList[$Identifier] = curl_init( $UrlRequest );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_USERAGENT, "KREDA Curl Handler" );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_VERBOSE, false );
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
            foreach ((array)$CurlHandleList as $CurlHandle) {
                $Info = curl_getinfo( $CurlHandle );

                if (self::$DownloadSizeTotal != $Info['download_content_length']) {
                    self::$DownloadSizeTotal = $Info['download_content_length'];
                }
                if (self::$DownloadSizeCurrent != $Info['size_download']) {
                    self::$DownloadSizeCurrent = $Info['size_download'];
                }
                if (self::$DownloadSpeed != $Info['speed_download']) {
                    self::$DownloadSpeed = $Info['speed_download'];
                }
                if (self::$DownloadTime != $Info['total_time']) {
                    self::$DownloadTime = $Info['total_time'];
                }
            }
            sleep( 2 );
        } while ($IsRunning > 0);
        foreach ($CurlHandleList as $Identifier => $CurlHandle) {
            $ResultData[$Identifier] = curl_multi_getcontent( $CurlHandle );
            curl_multi_remove_handle( $CurlHandler, $CurlHandle );
        }
        curl_multi_close( $CurlHandler );

        return $ResultData;
    }
}
