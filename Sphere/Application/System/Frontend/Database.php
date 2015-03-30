<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ClusterIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Database\Connection\Identifier;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageInfo;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageSuccess;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Table\AbstractTable;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableBody;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableCol;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableHead;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableRow;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableDefault;
use KREDA\Sphere\Common\Frontend\Text\Element\TextDanger;
use KREDA\Sphere\Common\Frontend\Text\Element\TextMuted;
use KREDA\Sphere\Common\Frontend\Text\Element\TextPrimary;
use KREDA\Sphere\Common\Frontend\Text\Element\TextWarning;

/**
 * Class Database
 *
 * @package KREDA\Sphere\Application\System\Frontend
 */
class Database extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageStatus()
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Datenbank' );
        $View->setDescription( 'Status' );
        $View->setContent( self::stageConfig() );
        return $View;
    }

    /**
     * @return AbstractTable
     */
    private static function stageConfig()
    {

        $Configuration = array();
        if (false !== ( $Path = realpath( __DIR__.'/../../../Common/Database/Config' ) )) {
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
                            $Status = new MessageSuccess( 'Verbunden', new OkIcon() );

                        } catch( \Exception $E ) {
                            $Status = new MessageDanger( 'Fehler', new WarningIcon() );
                        }

                        $ConsumerError = false;
                        if ($Service[1]) {
                            $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySuffix( $Service[1] );
                            if (!$tblConsumer) {
                                $ConsumerError = true;
                            }
                        }

                        $Configuration[$Application.$Service[0].$Service[1]] = new GridTableRow( array(
                            new GridTableCol( $Status ),
                            new GridTableCol( new TextWarning( $Application ) ),
                            new GridTableCol( new TextDanger( $Service[0] ) ),
                            new GridTableCol( new TextDanger(
                                isset( $tblConsumer )
                                    ?
                                    ( !$ConsumerError
                                        ? new MessageInfo( 'Mandant: '.$tblConsumer->getName().' ('.$tblConsumer->getDatabaseSuffix().')' )
                                        : new MessageDanger(
                                            'Der zugehörige Mandant '.$Service[1].' existiert nicht', new WarningIcon()
                                        )
                                    )
                                    : new MessageWarning( 'Systemübergreifend', new ClusterIcon() )
                            ) ),
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

    /**
     * @param bool $Simulation
     *
     * @return Stage
     */
    public static function stageCheck( $Simulation = true )
    {

        $View = new Stage();
        $View->setTitle( ( $Simulation ? 'KREDA Systemprüfung' : 'KREDA Systemreparatur' ) );
        $View->setDescription( 'Datenbanken' );
        $View->setContent(
            System::serviceUpdate()->setupDatabaseSchema( $Simulation )
        );
        return $View;
    }

    /**
     * @param \Exception $E
     *
     * @return Stage
     */
    public static function stageRepair( \Exception $E = null )
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Systemreparatur' );
        $View->setDescription( 'Datenbanken' );
        $View->setMessage(
            new MessageDanger( 'Die Anwendung hat festgestellt, dass manche Datenbanken nicht korrekt arbeiten.' )
            .new MessageWarning( 'Sollte das Problem nach dem automatischen Reparaturversuch nicht behoben sein wenden Sie sich bitte an den Support' )
            .( null === $E ? '' : new MessageInfo( $E->getMessage() ) )
        );
        $View->setContent(
            System::serviceUpdate()->setupDatabaseSchema( false )
        );
        return $View;
    }
}
