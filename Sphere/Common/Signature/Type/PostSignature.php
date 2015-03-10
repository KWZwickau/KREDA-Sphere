<?php
namespace KREDA\Sphere\Common\Signature\Type;

/**
 * Class PostSignature
 *
 * @package KREDA\Sphere\Common\Signature\Type
 */
class PostSignature
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

        array_walk_recursive( $_POST, array( $this, 'preventXSS' ) );

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
