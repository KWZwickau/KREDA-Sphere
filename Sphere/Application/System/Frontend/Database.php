<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\System\System;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ClusterIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger as DangerMessage;
use KREDA\Sphere\Client\Frontend\Message\Type\Info;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableBody;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableColumn;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableHead;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableRow;
use KREDA\Sphere\Client\Frontend\Table\Type\Table;
use KREDA\Sphere\Client\Frontend\Text\Type\Danger as DangerText;
use KREDA\Sphere\Client\Frontend\Text\Type\Muted as MutedText;
use KREDA\Sphere\Client\Frontend\Text\Type\Primary as PrimaryText;
use KREDA\Sphere\Client\Frontend\Text\Type\Warning as WarningText;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Database\Connection\Identifier;
use KREDA\Sphere\Common\Database\Handler;

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
     * @return \KREDA\Sphere\Client\Frontend\Table\AbstractTable
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
                            $Status = new Success( 'Verbunden', new OkIcon() );

                        } catch( \Exception $E ) {
                            $Status = new DangerMessage( 'Fehler', new WarningIcon() );
                        }

                        if (empty( $Service[1] )) {
                            $ConsumerType = new Warning( 'Systemübergreifend', new ClusterIcon() );
                        } else {
                            $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySuffix( $Service[1] );
                            if ($tblConsumer instanceof TblConsumer) {
                                $ConsumerType = new Info(
                                    'Mandant: '.$tblConsumer->getName().' ('.$tblConsumer->getDatabaseSuffix().')'
                                );
                            } else {
                                $ConsumerType = new DangerMessage(
                                    'Der zugehörige Mandant '.$Service[1].' existiert nicht', new WarningIcon()
                                );
                            }
                        }

                        $Configuration[$Application.$Service[0].$Service[1]] = new TableRow( array(
                            new TableColumn( $Status ),
                            new TableColumn( new WarningText( $Application ) ),
                            new TableColumn( new DangerText( $Service[0] ) ),
                            new TableColumn( new DangerText( $ConsumerType ) ),
                            new TableColumn( new WarningText( $Parameter['Driver'] ) ),
                            new TableColumn( new DangerText( $Parameter['Host'] ) ),
                            new TableColumn( new MutedText( ( $Parameter['Port'] ? $Parameter['Port'] : 'Default' ) ) ),
                            new TableColumn( new DangerText( $Parameter['Database'].new PrimaryText( $Service[1] ? '_'.$Service[1] : '' ) ) )
                        ) );
                    }
                }
            }
            ksort( $Configuration );
        }
        return new Table(
            new TableHead(
                new TableRow( array(
                    new TableColumn( 'Status' ),
                    new TableColumn( 'Application' ),
                    new TableColumn( 'Service' ),
                    new TableColumn( 'Consumer' ),
                    new TableColumn( 'Driver' ),
                    new TableColumn( 'Server' ),
                    new TableColumn( 'Port' ),
                    new TableColumn( 'Database' )
                ) )
            ),
            new TableBody(
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
            new DangerMessage( 'Die Anwendung hat festgestellt, dass manche Datenbanken nicht korrekt arbeiten.' )
            .new Warning( 'Sollte das Problem nach dem automatischen Reparaturversuch nicht behoben sein wenden Sie sich bitte an den Support' )
            .( null === $E ? '' : new Info( $E->getMessage() ) )
        );
        $View->setContent(
            System::serviceUpdate()->setupDatabaseSchema( false )
        );
        return $View;
    }
}
