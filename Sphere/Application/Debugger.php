<?php
namespace KREDA\Sphere\Application;

/**
 * Class Debugger
 *
 * @package KREDA\Sphere\Application
 */
class Debugger
{

    /** @var array $Protocol */
    private static $Protocol = array();
    /** @var int $Timestamp */
    private static $Timestamp = 0;

    /**
     *
     */
    function __construct()
    {

        if (!self::$Timestamp) {
            self::$Timestamp = microtime( true );
        }
    }

    /**
     * @param $__METHOD__
     */
    public static function addConstructorCall( $__METHOD__ )
    {

        self::$Protocol[] = '<div class="text-danger"><span class="glyphicon glyphicon-transfer"></span>&nbsp;<samp>'.$__METHOD__.'</samp> @'.round( microtime( true ) - self::$Timestamp,
                4 ).'</div>';
    }

    /**
     * @param $__METHOD__
     */
    public static function addMethodCall( $__METHOD__ )
    {

        self::$Protocol[] = '<div class="text-warning"><span class="glyphicon glyphicon-transfer"></span>&nbsp;<samp>'.$__METHOD__.'</samp> @'.round( microtime( true ) - self::$Timestamp,
                4 ).'</div>';
    }

    /**
     * @param $__FILE__
     * @param $__LINE__
     */
    public static function addFileLine( $__FILE__, $__LINE__ )
    {

        self::$Protocol[] = '<div class="text-info"><span class="glyphicon glyphicon-transfer"></span>&nbsp;<samp>'.$__FILE__.' : '.$__LINE__.'</samp> @'.round( microtime( true ) - self::$Timestamp,
                4 ).'</div>';
    }

    /**
     * @return string
     */
    public static function getProtocol()
    {

        krsort( self::$Protocol );
        return implode( '', self::$Protocol );
    }
}
