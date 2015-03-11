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

    /** @var string $Cache */
    private $Cache = '';
    /** @var string $Download */
    private $Download = '';

    /**
     * @param null|string $CacheLocation
     *
     * @throws \Exception
     */
    function __construct( $CacheLocation = null )
    {

        parent::__construct();
        if (null === $CacheLocation) {
            $this->Cache = realpath( __DIR__.'/../../../../Update' );
        } else {
            $this->Cache = realpath( $CacheLocation );
        }
    }

    /**
     * @param string $Version Number
     *
     * @return string Archive location
     */
    public function downloadVersion( $Version )
    {

        $Url = $this->getZipArchiveUrl( $Version );

        if (file_exists( $this->getDownload( $Version ).'.log' )) {
            $Log = unserialize( file_get_contents( $this->getDownload( $Version ).'.log' ) );
            if ($Log['SizeTotal'] == $Log['SizeCurrent'] && filesize( $this->getDownload( $Version ) ) == $Log['SizeTotal']) {
                return realpath( $this->getDownload( $Version ) );
            }
        }

        if (!is_writable( dirname( $this->getDownload( $Version ) ) )) {
            return 0;
        }

        $Proxy = new HttpProxy();
        $Option = array(
            CURLOPT_PROXY        => $Proxy->getHost(),
            CURLOPT_PROXYPORT    => $Proxy->getPort(),
            CURLOPT_PROXYUSERPWD => $Proxy->getUsernamePasswort(),
            CURLOPT_FILE         => fopen( $this->getDownload( $Version ), 'wb' )
        );
        $Option = array_filter( $Option );

        $this->getRequest( $Url, $Option );
        return realpath( $this->getDownload( $Version ) );
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

    public function getDownload( $Version )
    {

        if (empty( $this->Download )) {
            $this->Download = $this->getCache().'/'.hash( 'sha256', $Version ).'.zip';
        }
        return $this->Download;
    }

    /**
     * @return string
     */
    public function getCache()
    {

        return $this->Cache;
    }

    /**
     * @param string $UrlRequestList
     * @param array  $CurlOptionList
     *
     * @return array
     */
    private function getRequest( $UrlRequestList, $CurlOptionList = array() )
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
            set_time_limit( 30 );
            curl_multi_exec( $CurlHandler, $IsRunning );
            if (isset( $CurlOptionList[CURLOPT_FILE] )) {
                foreach ((array)$CurlHandleList as $CurlHandle) {
                    $Info = curl_getinfo( $CurlHandle );
                    file_put_contents( $this->Download.'.log', serialize( array(
                        'SizeTotal'     => $Info['download_content_length'],
                        'SizeCurrent'   => $Info['size_download'],
                        'DownloadSpeed' => $Info['speed_download'],
                        'DownloadTime'  => $Info['total_time']
                    ) ) );
                }
            }
        } while ($IsRunning > 0);
        foreach ($CurlHandleList as $Identifier => $CurlHandle) {
            $ResultData[$Identifier] = curl_multi_getcontent( $CurlHandle );
            curl_multi_remove_handle( $CurlHandler, $CurlHandle );
        }
        curl_multi_close( $CurlHandler );
        return $ResultData;
    }
}
