<?php
namespace KREDA\Sphere\Common\Signature\Type;

use KREDA\Sphere\Common\AbstractExtension;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class GetSignature
 *
 * @package KREDA\Sphere\Common\Signature\Type
 */
class GetSignature extends AbstractExtension
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
     * @return bool|null
     */
    public function validateSignature()
    {

        $Global = self::extensionSuperGlobal();

        array_walk_recursive( $Global->GET, array( $this, 'preventXSS' ) );

        if (!empty( $Global->GET ) && !isset( $Global->GET['_Sign'] )) {
            $Global->GET = array();
            $Global->saveGet();
            return null;
        } else {
            if (isset( $Global->GET['_Sign'] )) {
                $Data = $Global->GET;
                $Signature = $Global->GET['_Sign'];
                unset( $Data['_Sign'] );
                $Check = $this->createSignature( $Data );
                if ($Check['_Sign'] == $Signature) {
                    unset( $Global->GET['_Sign'] );
                    $Global->saveGet();
                    return true;
                } else {
                    $Global->GET = array();
                    $Global->saveGet();
                    return false;
                }
            } else {
                $Global->GET = array();
                $Global->saveGet();
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
        $Nonce = date( 'Ymd' );
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

        array_walk( $Data, function ( &$V ) {

            if (!is_string( $V ) && !is_array( $V )) {
                $V = (string)$V;
            }
        } );
        krsort( $Data );
        return $Data;
    }

    /**
     * @param $Value
     */
    private function preventXSS( &$Value )
    {

        $Value = strip_tags( $Value );
    }
}
