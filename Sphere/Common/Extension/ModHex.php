<?php
namespace KREDA\Sphere\Common\Extension;

/**
 * Class ModHex
 *
 * Encapsulates decoding text with the ModHex encoding from Yubico.
 *
 * @package KREDA\Sphere\Common\Extension
 */
class ModHex
{

    /** @var string $Key */
    private static $Key = "cbdefghijklnrtuv"; // translation key used to ModHex a string
    /** @var string $String */
    private $String = '';

    /**
     * Use ModHex::withString()
     *
     * @param string $String
     */
    private function __construct( $String )
    {

        $this->String = $String;
    }

    /**
     * @param $String
     *
     * @return ModHex
     */
    final public static function withString( $String )
    {

        return new ModHex( $String );
    }

    /**
     * @return string
     */
    final public function getSerialNumber()
    {

        $String = $this->getIdentifier();
        $String = ( ( strlen( $String ) % 2 ) == 1 ? 'c'.$String : $String );
        $String = base64_encode( $this->decodeString( $String ) );
        $String = $this->convertBase64ToHex( $String );
        return gmp_strval( gmp_init( $String, 16 ) );
    }

    /**
     * @return string
     */
    final public function getIdentifier()
    {

        return substr( $this->String, 0, 12 );
    }

    /**
     * @param string $String
     *
     * @return bool|string
     */
    final private function decodeString( $String )
    {

        $StringLength = strlen( $String );
        $StringDecoded = "";
        if ($StringLength % 2 != 0) {
            return false;
        }
        for ($Run = 0; $Run < $StringLength; $Run = $Run + 2) {
            $High = strpos( ModHex::$Key, $String[$Run] );
            $Low = strpos( ModHex::$Key, $String[$Run + 1] );
            if ($High === false || $Low === false) {
                return false;
            }
            $StringDecoded .= chr( ( $High << 4 ) | $Low );
        }
        return $StringDecoded;
    }

    /**
     * @param string $String
     *
     * @return string
     */
    final private function convertBase64ToHex( $String )
    {

        $Return = '';
        $Convert = base64_decode( $String );
        $CharList = str_split( $Convert );
        for ($Run = 0; $Run < count( $CharList ); $Run++) {
            $Return .= sprintf( "%02x", ord( $CharList[$Run] ) );
        }
        return $Return;
    }

}
