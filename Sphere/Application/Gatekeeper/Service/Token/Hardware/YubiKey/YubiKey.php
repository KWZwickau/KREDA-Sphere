<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\YubiKey;

use KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\CurlHandler;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\YubiKey\Component\KeyValue;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\YubiKey\Exception\ComponentException;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\YubiKey\Exception\Repository\BadOTPException;
use KREDA\Sphere\Application\Gatekeeper\Service\Token\Hardware\YubiKey\Exception\Repository\ReplayedOTPException;
use KREDA\Sphere\Common\Debugger;

/**
 * Class YubiKey
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service\Access\YubiKey
 */
class YubiKey
{

    /** @var string $KeyDelimiter */
    private $KeyDelimiter = '[:]';
    /** @var int $YubiApiTimeout */
    private $YubiApiTimeout = 3;

    /** @var int $YubiApiId */
    private $YubiApiId = 0;
    /** @var null|string $YubiApiKey */
    private $YubiApiKey = null;

    /** @var array $YubiApiEndpoint */
    private $YubiApiEndpoint = array(
        'api.yubico.com/wsapi/2.0/verify',
        'api2.yubico.com/wsapi/2.0/verify',
        'api3.yubico.com/wsapi/2.0/verify',
        'api4.yubico.com/wsapi/2.0/verify',
        'api5.yubico.com/wsapi/2.0/verify'
    );

    /**
     * @param integer $YubiApiId
     * @param null|string $YubiApiKey
     */
    final function __construct( $YubiApiId, $YubiApiKey = null )
    {

        Debugger::addMethodCall( __METHOD__ );

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

        Debugger::addMethodCall( __METHOD__ );

        if (!preg_match( "/^((.*)".$this->KeyDelimiter.")?".
            "(([cbdefghijklnrtuvCBDEFGHIJKLNRTUV]{0,16})".
            "([cbdefghijklnrtuvCBDEFGHIJKLNRTUV]{32}))$/",
            $Value, $Part )
        ) {
            throw new BadOTPException();
        }
        return new KeyValue( $Part[3] );
    }

    /**
     * @param KeyValue $Key
     *
     * @return bool
     * @throws BadOTPException
     * @throws ComponentException
     * @throws ReplayedOTPException
     */
    final public function verifyKey( KeyValue $Key )
    {

        Debugger::addMethodCall( __METHOD__ );

        $Parameter = $this->createParameter( $Key );
        $Query = $this->createSignature( $Parameter );

        $QueryList = array();
        foreach ((array)$this->YubiApiEndpoint as $YubiApiEndpoint) {
            $QueryList[] = 'http://'.$YubiApiEndpoint."?".$Query;
        }

        $Result = CurlHandler::getRequest( $QueryList, array(
            CURLOPT_PROXY        => '192.168.100.254',
            CURLOPT_PROXYPORT    => 3128,
            CURLOPT_PROXYUSERPWD => 'Kunze:Ny58N',
            CURLOPT_TIMEOUT      => $this->YubiApiTimeout
        ) );

        $Decision = array();
        foreach ((array)$Result as $Response) {
            if (preg_match( "/status=([a-zA-Z0-9_]+)/", $Response, $Status )) {
                Debugger::addProtocol( $Response );
                /**
                 * Case 1.
                 * OTP or Nonce values doesn't match - ignore response.
                 */
                if (!preg_match( "/otp=".$Key->getKeyOTP()."/", $Response ) ||
                    !preg_match( "/nonce=".$Key->getKeyNOnce()."/", $Response )
                ) {
                    Debugger::addFileLine( __FILE__, __LINE__ );
                    continue;
                } /**
                 * Case 2.
                 * We have a HMAC key.  If signature is invalid - ignore response.
                 * Return if status=OK or status=REPLAYED_OTP.
                 */
                elseif (null !== $this->YubiApiKey) {
                    Debugger::addFileLine( __FILE__, __LINE__ );
                    if ($this->checkSignature( $Response, $Status[1] )) {
                        $Decision[] = 1;
                    } else {
                        $Decision[] = 0;
                    }
                } /** Case 3.
                 * We check the status directly
                 * Return if status=OK or status=REPLAYED_OTP.
                 */
                else {
                    Debugger::addFileLine( __FILE__, __LINE__ );
                    switch ($Status[1]) {
                        case 'OK':
                            $Decision[] = 1;
                            break;
                        case 'BAD_OTP':
                            throw new BadOTPException( $Status[1] );
                            break;
                        case 'REPLAYED_OTP':
                            $Decision[] = 0;
                            break;
                        default:
                            throw new ComponentException( $Status[1] );
                    }
                }
            }
        }
        /**
         *
         */
        Debugger::addProtocol( print_r( $Decision, true ) );
        $Decision = array_sum( $Decision ) / ( count( $Decision ) > 0 ? count( $Decision ) : 1 );

        if ($Decision > 0.5) {
            return true;
        } elseif ($Decision == 0) {
            throw new ReplayedOTPException();
        } else {
            return false;
        }
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
     * @param $Result
     * @param $Status
     *
     * @return bool
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
                return false;
            }
            if ($Status == 'OK') {
                return true;
            }
        }
        return false;
    }
}
