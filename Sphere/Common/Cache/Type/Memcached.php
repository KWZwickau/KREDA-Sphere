<?php
namespace KREDA\Sphere\Common\Cache\Type;

use KREDA\Sphere\Common\Cache\ICacheInterface;

/**
 * Class Memcached
 *
 * @package KREDA\Sphere\Common\Cache\Type
 */
class Memcached implements ICacheInterface
{

    /** @var \Memcached $Status */
    private static $Server = null;
    /** @var string $Host */
    private static $Host = '';
    /** @var string $Port */
    private static $Port = '11211';
    /** @var array $Status */
    private $Status = null;

    /**
     *
     */
    function __construct()
    {

        $Config = __DIR__.'/../Config/Memcached.ini';
        if (false !== ( $Config = realpath( $Config ) )) {
            $Setting = parse_ini_file( $Config, true );
            if (isset( $Setting['Host'] )) {
                self::$Host = $Setting['Host'];
            }
            if (isset( $Setting['Port'] )) {
                self::$Port = $Setting['Port'];
            }
        } else {
            throw new \Exception( 'Missing Cache-Configuration for '.get_class( $this ) );
        }
        if (self::$Host && self::$Port) {
            if (class_exists( '\Memcached', false ) && null === self::$Server) {
                self::$Server = new \Memcached();
                self::$Server->addServer( self::$Host, self::$Port );
                self::$Server->setOption( \Memcached::OPT_TCP_NODELAY, true );
            }
        }
    }

    /**
     * @return void
     */
    public static function clearCache()
    {

        if (null !== self::$Server) {
            self::$Server->flush();
        }
    }

    /**
     * @return \Memcached
     */
    public static function getServer()
    {

        return self::$Server;
    }

    /**
     * @return integer
     */
    public function getCountHits()
    {

        $this->fetchStatus();
        if (!empty( $this->Status )) {
            return $this->Status[self::getConnection()]['get_hits'];
        }
        return -1;
    }

    private function fetchStatus()
    {

        if (null !== self::$Server && empty( $this->Status )) {
            $this->Status = self::$Server->getStats();
        }
    }

    /**
     * @return string
     */
    public static function getConnection()
    {

        return self::$Host.':'.self::$Port;
    }

    /**
     * @return integer
     */
    public function getCountMisses()
    {

        $this->fetchStatus();
        if (!empty( $this->Status )) {
            return $this->Status[self::getConnection()]['get_misses'];
        }
        return -1;
    }

    /**
     * @return integer
     */
    public function getSizeFree()
    {

        return $this->getSizeAvailable() - $this->getSizeUsed();
    }

    /**
     * @return integer
     */
    public function getSizeAvailable()
    {

        $this->fetchStatus();
        if (!empty( $this->Status )) {
            return $this->Status[self::getConnection()]['limit_maxbytes'];
        }
        return -1;
    }

    /**
     * @return integer
     */
    public function getSizeUsed()
    {

        $this->fetchStatus();
        if (!empty( $this->Status )) {
            return $this->Status[self::getConnection()]['bytes'];
        }
        return -1;
    }

    /**
     * @return integer
     */
    public function getSizeWasted()
    {

        return 0;
    }
}
