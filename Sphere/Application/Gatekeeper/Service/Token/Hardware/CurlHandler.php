<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware;

/**
 * Class CurlHandler
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware
 */
class CurlHandler
{

    /**
     * @param string|array $UrlRequestList
     * @param array        $CurlOptionList
     *
     * @return array
     */
    public static function getRequest( $UrlRequestList, $CurlOptionList = array() )
    {

        $CurlHandleList = array();
        $ResultData = array();
        $CurlHandler = curl_multi_init();
        /**
         * Setup
         */
        foreach ((array)$UrlRequestList as $Identifier => $UrlRequest) {
            $CurlHandleList[$Identifier] = curl_init( $UrlRequest );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_USERAGENT, "KREDA YubiKey" );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_VERBOSE, true );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_HEADER, false );
            curl_setopt( $CurlHandleList[$Identifier], CURLOPT_FAILONERROR, true );
            if (!empty( $CurlOptionList )) {
                curl_setopt_array( $CurlHandleList[$Identifier], $CurlOptionList );
            }
            curl_multi_add_handle( $CurlHandler, $CurlHandleList[$Identifier] );
        }
        /**
         * Execute
         */
        $IsRunning = null;
        do {
            curl_multi_exec( $CurlHandler, $IsRunning );
        } while ($IsRunning > 0);
        /**
         * Collect
         */
        foreach ($CurlHandleList as $Identifier => $CurlHandle) {
            $ResultData[$Identifier] = curl_multi_getcontent( $CurlHandle );
            curl_multi_remove_handle( $CurlHandler, $CurlHandle );
        }
        curl_multi_close( $CurlHandler );

        return $ResultData;
    }
}
