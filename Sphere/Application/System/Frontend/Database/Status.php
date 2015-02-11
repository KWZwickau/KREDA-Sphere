<?php
namespace KREDA\Sphere\Application\System\Frontend\Database;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Database\Connection\Identifier;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableBody;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableCol;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableHead;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableRow;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableDefault;
use KREDA\Sphere\Common\Frontend\Text\Element\TextDanger;
use KREDA\Sphere\Common\Frontend\Text\Element\TextMuted;
use KREDA\Sphere\Common\Frontend\Text\Element\TextPrimary;
use KREDA\Sphere\Common\Frontend\Text\Element\TextSuccess;
use KREDA\Sphere\Common\Frontend\Text\Element\TextWarning;
use KREDA\Sphere\Common\Frontend\Text\Structure\BackgroundDanger;
use KREDA\Sphere\Common\Frontend\Text\Structure\BackgroundSuccess;

/**
 * Class Status
 *
 * @package KREDA\Sphere\Application\System\Frontend\Database
 */
class Status extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageDatabaseStatus()
    {

        $View = new Stage();
        $View->setTitle( 'Datenbank-Cluster' );
        $View->setDescription( 'Status' );
        $View->setMessage( 'Zeigt die aktuelle Konfiguration und den Verbindungsstatus' );
        $View->setContent(
            self::dataDatabaseConfig()
        );
        return $View;
    }

    private static function dataDatabaseConfig()
    {

        $Configuration = array();
        if (false !== ( $Path = realpath( __DIR__.'/../../../../Common/Database/Config' ) )) {
            $Iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator( $Path, \RecursiveDirectoryIterator::SKIP_DOTS ),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            /** @var \SplFileInfo $FileInfo */
            foreach ($Iterator as $FileInfo) {
                if ($FileInfo->getExtension() == 'ini') {
                    $Application = $FileInfo->getBasename( '.'.$FileInfo->getExtension() );
                    $Setting = parse_ini_file( $FileInfo->getPathname(), true );
                    foreach ((array)$Setting as $Service => $Parameter) {
                        $Service = explode( ':', $Service );

                        try {
                            new Handler( new Identifier( $Application, $Service[0], $Service[1] ) );
                            $Status = new BackgroundSuccess( new TextSuccess( 'Verbunden' ) );

                        } catch( \Exception $E ) {
                            $Status = new BackgroundDanger( new TextDanger( 'Fehler' ) );
                        }

                        $Configuration[$Application.$Service[0].$Service[1]] = new GridTableRow( array(
                            new GridTableCol( $Status ),
                            new GridTableCol( new TextWarning( $Application ) ),
                            new GridTableCol( new TextDanger( $Service[0] ) ),
                            new GridTableCol( new TextDanger( $Service[1] ) ),
                            new GridTableCol( new TextWarning( $Parameter['Driver'] ) ),
                            new GridTableCol( new TextDanger( $Parameter['Host'] ) ),
                            new GridTableCol( new TextMuted( ( $Parameter['Port'] ? $Parameter['Port'] : 'Default' ) ) ),
                            new GridTableCol( new TextDanger( $Parameter['Database'].new TextPrimary( $Service[1] ? '_'.$Service[1] : '' ) ) )
                        ) );
                    }
                }
            }
            ksort( $Configuration );
        }
        return new TableDefault(
            new GridTableHead(
                new GridTableRow( array(
                    new GridTableCol( 'Status' ),
                    new GridTableCol( 'Application' ),
                    new GridTableCol( 'Service' ),
                    new GridTableCol( 'Consumer' ),
                    new GridTableCol( 'Driver' ),
                    new GridTableCol( 'Server' ),
                    new GridTableCol( 'Port' ),
                    new GridTableCol( 'Database' )
                ) )
            ),
            new GridTableBody(
                $Configuration
            ), null, true
        );
    }
}
