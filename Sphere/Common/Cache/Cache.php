<?php
namespace KREDA\Sphere\Common\Cache;

use KREDA\Sphere\Common\Cache\Type\ApcSma;
use KREDA\Sphere\Common\Cache\Type\Apcu;
use KREDA\Sphere\Common\Cache\Type\ApcUser;
use KREDA\Sphere\Common\Cache\Type\OpCache;
use KREDA\Sphere\Common\Cache\Type\TwigCache;

/**
 * Class Cache
 *
 * @package KREDA\Sphere\Common\Cache
 */
class Cache
{

    /**
     *
     */
    private function __construct()
    {

        $Config = __DIR__.'/Config/Cache.ini';
        if (false !== ( $Config = realpath( $Config ) )) {
            $Setting = parse_ini_file( $Config, true );
            if (isset( $Setting['Clear'] )) {
                if ($Setting['Clear']) {
                    self::clearCache();
                }
            }
        } else {
            throw new \Exception( 'Missing Cache-Configuration for '.get_class( $this ) );
        }
    }

    /**
     *
     */
    final public function clearCache()
    {

        Apcu::clearCache();
        ApcSma::clearCache();
        ApcUser::clearCache();
        OpCache::clearCache();
        TwigCache::clearCache();
    }

    /**
     * @return Cache
     */
    final public static function getApi()
    {

        return new Cache();
    }
}
