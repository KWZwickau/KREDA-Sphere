<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Component\KeyValue;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Component\Request;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Exception\ComponentException;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Exception\Repository\BadOTPException;
use KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey\Exception\Repository\ReplayedOTPException;

/**
 * Class YubiKey
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey
 */
class YubiKey
{

    /** @var string $KeyDelimiter */
    private $KeyDelimiter = '[:]';
    /** @var bool $YubiApiSsl */
    private $YubiApiSsl = false;
    /** @var bool $YubiApiVerify */
    private $YubiApiVerify = true;
    /** @var int $YubiApiTimeout */
    private $YubiApiTimeout = 1;

    /** @var int $YubiApiId */
    private $YubiApiId = 0;
    /** @var null|string $YubiApiKey */
    private $YubiApiKey = null;

    /** @var int $YubiApiBalancer */
    private $YubiApiBalancer = 0;
    /** @var array $YubiApiEndpoint */
    private $YubiApiEndpoint = array(
        'api.yubico.com/wsapi/2.0/verify',
        'api2.yubico.com/wsapi/2.0/verify',
        'api3.yubico.com/wsapi/2.0/verify',
        'api4.yubico.com/wsapi/2.0/verify',
        'api5.yubico.com/wsapi/2.0/verify'
    );
    private $IsReplay = false;
    private $IsValid = false;

    /**
     * @param integer $YubiApiId
     * @param null|string $YubiApiKey
     */
    final function __construct( $YubiApiId, $YubiApiKey = null )
    {

        $this->YubiApiId = $YubiApiId;
        if (null !== $YubiApiKey) {
            $this->YubiApiKey = base64_decode( $YubiApiKey );
        }
    }

    /**
     * @param string $Value
     *
     * @throws BadOTPException
     * @return bool|KeyValue
     */
    final public function parseKey( $Value )
    {

        if (!preg_match( "/^((.*)".$this->KeyDelimiter.")?".
            "(([cbdefghijklnrtuvCBDEFGHIJKLNRTUV]{0,16})".
            "([cbdefghijklnrtuvCBDEFGHIJKLNRTUV]{32}))$/",
            $Value, $Part )
        ) {
            throw new BadOTPException();
        }
        return new KeyValue( $Part[2], $Part[3], $Part[4], $Part[5] );
    }

    final public function verifyKey( KeyValue $Key )
    {

        $Parameter = $this->createParameter( $Key );
        $Query = $this->createSignature( $Parameter );
        $Request = $this->createRequest( $Query );
        return $this->executeRequest( $Request, $Key );
    }

    /**
     * @param KeyValue $KeyValue
     *
     * @return string
     */
    private function createParameter( KeyValue $KeyValue )
    {

        $Parameter = array(
            'id'    => $this->YubiApiId,
            'otp'   => $KeyValue->getKeyOTP(),
            'nonce' => $this->createNOnce()
        );
        $KeyValue->setKeyNOnce( $Parameter['nonce'] );
        ksort( $Parameter );
        $Query = '';
        foreach ($Parameter as $Key => $Value) {
            $Query .= "&".$Key."=".$Value;
        }
        return ltrim( $Query, "&" );
    }

    /**
     * @return string
     */
    private function createNOnce()
    {

        return md5( uniqid( rand() ) );
    }

    /**
     * @param string $Parameter
     *
     * @return string
     */
    private function createSignature( $Parameter )
    {

        if (null !== $this->YubiApiKey) {
            $Signature = base64_encode( hash_hmac( 'sha1', $Parameter, $this->YubiApiKey, true ) );
            $Signature = preg_replace( '/\+/', '%2B', $Signature );
            $Parameter .= '&h='.$Signature;
        }
        return $Parameter;
    }

    /**
     * @param string $Query
     *
     * @return Request
     * @throws \Exception
     */
    private function createRequest( $Query )
    {

        $this->resetYubiApiBalancer();
        $Return = new Request();
        while (false !== ($YubiApiEndpoint = $this->fetchYubiApiBalancer())) {
            if ($this->YubiApiSsl) {
                $YubiApiUrl = "https://";
            } else {
                $YubiApiUrl = "http://";
            }
            $YubiApiUrl .= $YubiApiEndpoint."?".$Query;
            $CurlHandler = curl_init( $YubiApiUrl );
            curl_setopt( $CurlHandler, CURLOPT_USERAGENT, "KREDA YubiKey" );
            curl_setopt( $CurlHandler, CURLOPT_RETURNTRANSFER, 1 );

            curl_setopt( $CurlHandler, CURLOPT_PROXY, '192.168.100.254' );
            curl_setopt( $CurlHandler, CURLOPT_PROXYPORT, 3128 );
            curl_setopt( $CurlHandler, CURLOPT_PROXYUSERPWD, 'Kunze:Ny58N' );

            if (!$this->YubiApiVerify) {
                curl_setopt( $CurlHandler, CURLOPT_SSL_VERIFYPEER, 0 );
            }
            curl_setopt( $CurlHandler, CURLOPT_FAILONERROR, true );
            curl_setopt( $CurlHandler, CURLOPT_TIMEOUT, $this->YubiApiTimeout );
            $Return->addCurlHandler( $CurlHandler );
        }
        return $Return;
    }

    /**
     *
     */
    private function resetYubiApiBalancer()
    {

        $this->YubiApiBalancer = 0;
    }

    /**
     * @return bool|string
     */
    private function fetchYubiApiBalancer()
    {

        if ($this->YubiApiBalancer >= count( $this->YubiApiEndpoint )) {
            return false;
        } else {
            return $this->YubiApiEndpoint[$this->YubiApiBalancer++];
        }
    }

    /**
     * @param Request  $Request
     * @param KeyValue $KeyValue
     *
     * @return bool
     * @throws \Exception
     */
    private function executeRequest( Request $Request, KeyValue $KeyValue )
    {

        $this->IsReplay = false;
        $this->IsValid = false;

        foreach ($Request->getCurlHandler() as $CurlHandler) {
            if (false !== ( $Result = curl_exec( $CurlHandler ) )) {
                if (preg_match( "/status=([a-zA-Z0-9_]+)/", $Result, $Status )) {
                    $Status = $Status[1];
                    if (!preg_match( "/otp=".$KeyValue->getKeyOTP()."/", $Result ) ||
                        !preg_match( "/nonce=".$KeyValue->getKeyNOnce()."/", $Result )
                    ) {
                        /**
                         * Case 1.
                         * OTP or Nonce values doesn't match - ignore response.
                         */
                        switch ($Status) {
                            case 'BAD_OTP':
                                throw new BadOTPException( $Status );
                                break;
                        }
                    } elseif (null !== $this->YubiApiKey) {
                        /**
                         * Case 2.
                         * We have a HMAC key.  If signature is invalid - ignore response.
                         * Return if status=OK or status=REPLAYED_OTP.
                         */
                        $this->checkSignature( $Result, $Status );
                    } else {
                        /** Case 3.
                         * We check the status directly
                         * Return if status=OK or status=REPLAYED_OTP.
                         */
                        switch ($Status) {
                            case 'OK':
                                $this->IsValid = true;
                                break;
                            case 'BAD_OTP':
                                throw new BadOTPException( $Status );
                                break;

                            case 'REPLAYED_OTP':
                                $this->IsReplay = true;
                                break;
                            default: {
                            throw new ComponentException();
                            }
                        }
                    }
                    if ($this->IsValid || $this->IsReplay) {
                        /* We have status=OK or status=REPLAYED_OTP, return. */
                        curl_close( $CurlHandler );
                        if ($this->IsReplay) {
                            throw new ReplayedOTPException();
                        }
                        if ($this->IsValid) {
                            return true;
                        }
                        throw new ComponentException( $Status );
                    }
                }
            }
            curl_close( $CurlHandler );
        }

        if ($this->IsReplay) {
            throw new ReplayedOTPException();
        }
        if ($this->IsValid) {
            return true;
        }
        throw new ComponentException();
    }

    /**
     * @param $Result
     * @param $Status
     *
     * @return void
     */
    private function checkSignature( $Result, $Status )
    {

        $Response = array();
        $ResultLineList = explode( "\r\n", trim( $Result ) );
        foreach ($ResultLineList as $ResultLine) {
            $ResultLine = preg_replace( '/=/', '#', $ResultLine, 1 );
            $PartList = explode( "#", $ResultLine );
            $Response[$PartList[0]] = $PartList[1];
        }

        $ApiParameterList = array(
            'nonce',
            'otp',
            'sessioncounter',
            'sessionuse',
            'sl',
            'status',
            't',
            'timeout',
            'timestamp'
        );
        sort( $ApiParameterList );

        $Query = null;
        foreach ($ApiParameterList as $Parameter) {
            if (array_key_exists( $Parameter, $Response )) {
                if ($Query) {
                    $Query = $Query.'&';
                }
                $Query = $Query.$Parameter.'='.$Response[$Parameter];
            }
        }

        $Signature = base64_encode( hash_hmac( 'sha1', utf8_encode( $Query ), $this->YubiApiKey, true ) );

        if ($Response['h'] == $Signature) {
            if ($Status == 'REPLAYED_OTP') {
                $this->IsReplay = true;
            }
            if ($Status == 'OK') {
                $this->IsValid = true;
            }
        }
    }
}
