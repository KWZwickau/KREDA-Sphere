<?php
namespace KREDA\Sphere\Common\Signature\Type;

use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class GetSignature
 *
 * @package KREDA\Sphere\Common\Signature\Type
 */
class GetSignature
{

    /** @var string $Secret */
    private $Secret = '>ÄÜ(z=va>@lf.:tö?;wk=u!!zd@lv:idtf\/#i#/mprÜ]sp&iwm$Ö!rxvn>Äi}tö';

    /**
     * @throws \Exception
     */
    function __construct()
    {

        $Config = __DIR__.'/../Config/GetSignature.ini';
        if (false !== ( $Config = realpath( $Config ) )) {
            $Setting = parse_ini_file( $Config, true );
            if (isset( $Setting['Secret'] ) && strlen( $Setting['Secret'] ) >= 48) {
                $this->Secret = $Setting['Secret'];
            }
        } else {
            throw new \Exception( 'Missing Signature-Configuration for '.get_class( $this ) );
        }
    }

    /**
     * @return bool
     */
    public function validateSignature()
    {

        if (!empty( $_GET ) && !isset( $_GET['_Sign'] )) {
            $_GET = array();
            return false;
        } else {
            if (isset( $_GET['_Sign'] )) {
                $Data = $_GET;
                $Signature = $_GET['_Sign'];
                unset( $Data['_Sign'] );
                $Check = $this->createSignature( $Data );
                if ($Check['_Sign'] == $Signature) {
                    unset( $_GET['_Sign'] );
                    return true;
                } else {
                    $_GET = array();
                    return false;
                }
            } else {
                $_GET = array();
                return true;
            }
        }
    }

    /**
     * @param array       $Data
     * @param null|string $Location
     *
     * @return array
     */
    public function createSignature( $Data, $Location = null )
    {

        if (null === $Location) {
            $Location = HttpKernel::getRequest()->getPathInfo();
        }
        $Nonce = date( 'dmYH' );
        array_push( $Data, $Location );
        $Ordered = $this->sortData( (array)$Data );
        $Signature = serialize( $Ordered );
        $Signature = hash_hmac( 'sha256', $Signature, $Nonce.$this->Secret );
        array_pop( $Data );
        $Data['_Sign'] = base64_encode( $Signature );
        return $Data;
    }

    /**
     * @param $Data
     *
     * @return mixed
     */
    private function sortData( $Data )
    {

        krsort( $Data );
        return $Data;
    }
}
