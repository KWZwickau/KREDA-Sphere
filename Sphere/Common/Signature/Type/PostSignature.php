<?php
namespace KREDA\Sphere\Common\Signature\Type;

use KREDA\Sphere\Common\AbstractExtension;

/**
 * Class PostSignature
 *
 * @package KREDA\Sphere\Common\Signature\Type
 */
class PostSignature extends AbstractExtension
{

    /** @var string $Secret */
    private $Secret = '>ÄÜ(z=va>@lf.:tö?;wk=u!!zd@lv:idtf\/#i#/mprÜ]sp&iwm$Ö!rxvn>Äi}tö';

    /**
     * @throws \Exception
     */
    function __construct()
    {

        $Config = __DIR__.'/../Config/PostSignature.ini';
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

        $Global = self::extensionSuperGlobal();
        array_walk_recursive( $Global->POST, array( $this, 'preventXSS' ) );
        $Global->saveGet();

        return true;
    }

    /**
     * @param $Value
     */
    private function preventXSS( &$Value )
    {

        $Value = strip_tags( $Value );
    }
}
