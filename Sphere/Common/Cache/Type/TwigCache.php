<?php
namespace KREDA\Sphere\Common\Cache\Type;

use KREDA\Sphere\Common\Cache\ICacheInterface;

/**
 * Class TwigCache
 *
 * @package KREDA\Sphere\Common\Cache\Type
 */
class TwigCache implements ICacheInterface
{

    private static $Cache = '/../../../../Library/MOC-V/Component/Template/Component/Bridge/Repository/TwigTemplate';

    /**
     * @return void
     */
    public static function clearCache()
    {

        $E = new \Twig_Environment( null, array( 'cache' => realpath( __DIR__.self::$Cache ) ) );
        $E->clearCacheFiles();
        $E->clearTemplateCache();
    }

    /**
     * @return integer
     */
    public function getCountHits()
    {

        return -1;
    }

    /**
     * @return integer
     */
    public function getCountMisses()
    {

        return -1;
    }

    /**
     * @return integer
     */
    public function getSizeWasted()
    {

        return $this->getSizeAvailable() - $this->getSizeFree() - $this->getSizeUsed();
    }

    /**
     * @return integer
     */
    public function getSizeAvailable()
    {

        return ( disk_total_space( __DIR__ ) );
    }

    /**
     * @return integer
     */
    public function getSizeFree()
    {

        return ( disk_free_space( __DIR__ ) );
    }

    /**
     * @return integer
     */
    public function getSizeUsed()
    {

        $Total = 0;
        $Path = realpath( __DIR__.self::$Cache );
        if ($Path !== false) {
            foreach (new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( $Path,
                \FilesystemIterator::SKIP_DOTS ) ) as $Object) {
                $Total += $Object->getSize() * 1024;
            }
        }
        return $Total;
    }

}
