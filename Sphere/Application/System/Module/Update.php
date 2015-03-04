<?php
namespace KREDA\Sphere\Application\System\Module;

use KREDA\Sphere\Application\System\Frontend\Update as Frontend;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\Updater\Type\GitHub;

/**
 * Class Update
 *
 * @package KREDA\Sphere\Application\System\Module
 */
class Update extends Database
{

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::registerClientRoute( $Configuration,
            '/Sphere/System/Update', __CLASS__.'::frontendSearch'
        )->setParameterDefault( 'Version', null );
        self::registerClientRoute( $Configuration,
            '/Sphere/System/Update/Install', __CLASS__.'::frontendInstall'
        )->setParameterDefault( 'Version', null );
        self::registerClientRoute( $Configuration,
            '/Sphere/System/Update/Run', __CLASS__.'::frontendRun'
        )->setParameterDefault( 'Version', null );
        self::registerClientRoute( $Configuration,
            '/Sphere/System/Update/Log', __CLASS__.'::frontendLog'
        )->setParameterDefault( 'Version', null );
        self::registerClientRoute( $Configuration,
            '/Sphere/System/Update/Extract', __CLASS__.'::frontendExtract'
        )->setParameterDefault( 'Archive', null );
        self::registerClientRoute( $Configuration,
            '/Sphere/System/Update/Write', __CLASS__.'::frontendWrite'
        )->setParameterDefault( 'Location', null );
    }

    /**
     * @param null|string $Version
     *
     * @return Stage
     */
    public static function frontendSearch( $Version )
    {

        self::setupModuleNavigation();
        return Frontend::stageSearch( $Version );
    }

    /**
     * @param null|string $Version
     *
     * @return Stage
     */
    public static function frontendInstall( $Version )
    {

        self::setupModuleNavigation();
        return Frontend::stageInstall( $Version );
    }

    /**
     * @param string $Version
     *
     * @return string
     */
    public static function frontendRun( $Version )
    {

        file_put_contents( __DIR__.'/../../../../MAINTENANCE', date( 'd.m.Y H:i:s' ) );

        $Updater = new GitHub( __DIR__.'/../../../../Update' );
        return $Updater->downloadVersion( $Version );
    }

    /**
     * @param string $Version
     *
     * @return string
     */
    public static function frontendLog( $Version )
    {

        $Updater = new GitHub( __DIR__.'/../../../../Update' );
        $Log = $Updater->getDownload( $Version ).'.log';
        if (file_exists( $Log )) {
            $Log = file_get_contents( $Log );
            if (empty( $Log ) || false === ( $Log = unserialize( $Log ) )) {
                return json_encode( array(
                    'SizeTotal'     => -1,
                    'SizeCurrent'   => 0,
                    'DownloadSpeed' => -1,
                    'DownloadTime'  => -1
                ) );
            } else {
                return json_encode( $Log );
            }
        }
        return json_encode( array(
            'SizeTotal'     => -1,
            'SizeCurrent'   => 0,
            'DownloadSpeed' => -1,
            'DownloadTime'  => -1
        ) );
    }

    /**
     * @param string $Archive
     *
     * @return string
     */
    public static function frontendExtract( $Archive )
    {

        set_time_limit( 60 * 60 );
        $Updater = new GitHub( __DIR__.'/../../../../Update' );
        return $Updater->extractArchive( $Archive );
    }

    /**
     * @param string $Location
     *
     * @return string
     */
    public static function frontendWrite( $Location )
    {

        set_time_limit( 60 * 60 );
        $Updater = new GitHub( __DIR__.'/../../../../Update' );
        $Source = realpath( $Updater->getCache().'/'.$Location );
        $Target = realpath( __DIR__.'/../../../../' );
        if ($Source && $Target) {
            $Return = $Updater->moveFilesRecursive( $Source, $Target );
            if ($Return) {
                unlink( __DIR__.'/../../../../MAINTENANCE' );
            }
            return $Return;
        } else {
            return null;
        }
    }
}
