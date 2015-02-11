<?php
namespace KREDA\Sphere\Application\System\Frontend\Database;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\FlashIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonDangerLink;
use KREDA\Sphere\Common\Frontend\Table\Structure\GridTableTitle;
use KREDA\Sphere\Common\Frontend\Table\Structure\TableData;
use KREDA\Sphere\Common\Frontend\Text\Element\TextDanger;
use KREDA\Sphere\Common\Frontend\Text\Element\TextMuted;
use KREDA\Sphere\Common\Frontend\Text\Element\TextPrimary;
use KREDA\Sphere\Common\Frontend\Text\Element\TextSuccess;

class Cache extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageDatabaseCache()
    {

        $View = new Stage();
        $View->setTitle( 'Datenbank-Cluster' );
        $View->setDescription( 'Cache' );

        if (isset( $_REQUEST['Clear'] )) {
            apc_clear_cache();
        }

        $ApcSMA = array( 0 => apc_sma_info( true ) );
        $ApcSMA[0]['used_mem'] = $ApcSMA[0]['seg_size'] - $ApcSMA[0]['avail_mem'];

        $ApcSMA[0]['num_seg'] = new TextMuted( $ApcSMA[0]['num_seg'] );
        $ApcSMA[0]['seg_size'] = new TextPrimary( number_format( $ApcSMA[0]['seg_size'] / 1024, 0, ',',
                '.' ) ).new TextMuted( 'KB' );
        $ApcSMA[0]['used_mem'] = new TextDanger( number_format( $ApcSMA[0]['used_mem'] / 1024, 0, ',',
                '.' ) ).new TextMuted( 'KB' );
        $ApcSMA[0]['avail_mem'] = new TextSuccess( number_format( $ApcSMA[0]['avail_mem'] / 1024, 0, ',',
                '.' ) ).new TextMuted( 'KB' );

        $ApcInfo = array( 0 => apc_cache_info( "user", true ) );

        $ApcInfo[0]['mem_size'] = new TextPrimary( number_format( $ApcInfo[0]['mem_size'] / 1024, 0, ',',
                '.' ) ).new TextMuted( 'KB' );
        $ApcInfo[0]['nhits'] = new TextSuccess( $ApcInfo[0]['nhits'] );
        $ApcInfo[0]['nmisses'] = new TextDanger( $ApcInfo[0]['nmisses'] );

        $View->setContent(
            new TableData( $ApcSMA, new GridTableTitle( 'APC (SMA)' ), array(
                'num_seg'   => 'Segmente',
                'seg_size'  => 'Größe',
                'used_mem'  => 'Benutzt',
                'avail_mem' => 'Verfügbar'
            ), false )
            .new TableData( $ApcInfo, new GridTableTitle( 'APC (Info-User)' ), array(
                'nhits'       => 'Hit',
                'nmisses'     => 'Miss',
                'ninserts'    => 'Write',
                'nentries'    => 'Size',
                'mem_size'    => 'Memory',
                'nslots'      => 'Slots',
                'ttl'         => 'TTL',
                'memory_type' => 'Typ'
            ), false )
            .new ButtonDangerLink( 'Clear', 'System/Database/Cache?Clear', new FlashIcon() )
        );
//        print '<pre>';
//        $ApcInfo = apc_cache_info();
//        var_dump( $ApcInfo['cache_list'] );
//        print '</pre>';

        return $View;
    }
}
