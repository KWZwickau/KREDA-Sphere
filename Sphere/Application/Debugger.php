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
    /** @var int $TimeGap */
    private static $TimeGap = 0;

    /**
     *
     */
    function __construct()
    {

        if (!self::$Timestamp) {
            self::$Timestamp = microtime( true );
        }
        if (!self::$TimeGap) {
            self::$TimeGap = microtime( true );
        }
    }

    /**
     * @param $__METHOD__
     */
    public static function addConstructorCall( $__METHOD__ )
    {

        self::addProtocol( self::splitNamespace( $__METHOD__ ) );
    }

    /**
     * @param string $Message
     * @param string $Icon
     */
    public static function addProtocol( $Message, $Icon = 'time' )
    {

        $TimeGap = self::getTimeGap() - self::$TimeGap;

        $Status = 'muted';
        if ($TimeGap < 0.020 && $TimeGap >= 0.001) {
            $Status = 'success';
        }
        if ($TimeGap >= 0.020) {
            $Status = 'warning';
            $Icon = 'time';
        }
        if ($TimeGap >= 0.070) {
            $Status = 'danger';
            $Icon = 'warning-sign';
        }

        self::$Protocol[] = '<div class="text-'.$Status.' small">'
            .'&nbsp;<span class="glyphicon glyphicon-'.$Icon.'"></span>&nbsp;'.self::getRuntime()
            .'&nbsp;<span class="glyphicon glyphicon-transfer"></span>&nbsp;'
            .'<samp>'.$Message.'</samp>'
            .'</div>';

        self::$TimeGap = self::getTimeGap();
    }

    /**
     * @return float
     */
    public static function getTimeGap()
    {

        return ( microtime( true ) - self::$Timestamp );
    }

    /**
     * @return string
     */
    public static function getRuntime()
    {

        return round( self::getTimeGap() * 1000, 0 ).'ms';
    }

    /**
     * @param string $Value
     *
     * @return string
     */
    private static function splitNamespace( $Value )
    {

        return str_replace( array( '\\', '/' ), array( '\\&shy;', '/&shy;' ), $Value );
    }

    /**
     * @param $__METHOD__
     */
    public static function addMethodCall( $__METHOD__ )
    {

        self::addProtocol( self::splitNamespace( $__METHOD__ ) );
    }

    /**
     * @param $__FILE__
     * @param $__LINE__
     */
    public static function addFileLine( $__FILE__, $__LINE__ )
    {

        self::addProtocol( $__FILE__.' : '.$__LINE__, 'file' );
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
