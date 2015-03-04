<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Application\System\Frontend\Update\Progress;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ClusterIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ShareIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageInfo;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageSuccess;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Form\Element\InputText;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutTitle;
use KREDA\Sphere\Common\Updater\Type\GitHub;

/**
 * Class Update
 *
 * @package KREDA\Sphere\Application\System\Frontend
 */
class Update extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageSearch()
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Update' );
        $View->setDescription( 'Status' );

        $Updater = new GitHub();
        $Current = $Updater->getCurrentVersion();
        $Next = $Updater->getLatestVersion();

        $Version = new InputText( 'Version' );
        $Version->setDefaultValue( $Next, true );

        $View->setContent(
            new MessageInfo( 'Installierte Version: '.$Current, new ClusterIcon() )
            .( $Current == $Next ? new MessageSuccess( 'Das System ist auf dem aktuellen Stand' ) :
                new MessageWarning(
                    'Es ist ein Update verfügbar: '.$Next.'<hr/>'.$Updater->fetchMessage( $Next ),
                    new ShareIcon()
                )
                .new FormDefault(
                    new GridFormGroup( new GridFormRow( new GridFormCol( $Version ) ) ),
                    new ButtonSubmitPrimary( 'Installieren' ),
                    self::getUrlBase().'/Sphere/System/Update/Install'
                )
            )
        );

        return $View;
    }

    /**
     * @param null|string $Version
     *
     * @return Stage
     */
    public static function stageInstall( $Version )
    {

        $View = new Stage();
        $View->setTitle( 'KREDA Update' );
        $View->setDescription( 'Installation' );

        $Updater = new GitHub();

        $View->setContent(
            new MessageInfo( 'Update von Version '.$Updater->getCurrentVersion().' auf '.$Version, new ShareIcon() )
            .new GridLayoutTitle( 'Download', $Version )
            .new Progress( 'StatusDownload' )
            .new GridLayoutTitle( 'Entpacken', $Version )
            .new Progress( 'StatusExtract' )
            .new GridLayoutTitle( 'Installieren', $Version )
            .new Progress( 'StatusInstall' )
            .'<script>Client.Use("ModProgress",function(){
                function Install( Location ) {
                    jQuery("div#StatusInstall").ModProgress({
                        "Total": 100,
                        "Size": 99.99,
                        "Speed": 0,
                        "Time": 0
                    });
                    jQuery.ajax({
                        url: "'.self::getUrlBase().'/Sphere/System/Update/Write",
                        data: { "REST":true, "Location": Location, "_": jQuery.now() },
                        success: function( Response ) {
                            jQuery("div#StatusInstall").ModProgress({
                                "Total": 100,
                                "Size": 100,
                                "Speed": 0,
                                "Time": 0
                            });
                            console.log( Response );
                        },
                        async: true
                    });
                }
                function Extract( Archive ) {
                    jQuery("div#StatusExtract").ModProgress({
                        "Total": 100,
                        "Size": 99.99,
                        "Speed": 0,
                        "Time": 0
                    });
                    jQuery.ajax({
                        url: "'.self::getUrlBase().'/Sphere/System/Update/Extract",
                        data: { "REST":true, "Archive": Archive, "_": jQuery.now() },
                        success: function( Response ) {
                            jQuery("div#StatusExtract").ModProgress({
                                "Total": 100,
                                "Size": 100,
                                "Speed": 0,
                                "Time": 0
                            });
                            Install( Response )
                        },
                        async: true
                    });
                }
                function Status() {
                    jQuery.ajax({
                        url: "'.self::getUrlBase().'/Sphere/System/Update/Log",
                        data: { "REST":true, "Version":"'.$Version.'", "_": jQuery.now() },
                        success: function( Response ) {
                            if( Response ) {
                                Response = JSON.parse( Response );
                                jQuery("div#StatusDownload").ModProgress({
                                    "Total": Response.SizeTotal,
                                    "Size": Response.SizeCurrent,
                                    "Speed": Response.DownloadSpeed,
                                    "Time": Response.DownloadTime
                                });
                            }
                        },
                        async: true
                    }).done(function( Response ) {
                        if( Response ) {
                            Response = JSON.parse( Response );
                            if( Response.SizeCurrent <= 0 || Response.SizeCurrent != Response.SizeTotal ) {
                                window.setTimeout( function(){ Status(); }, 1000 );
                            } else {
                                /* Download Complete */
                            }
                        } else {
                            window.setTimeout( function(){ Status(); }, 5000 );
                        }
                    })
                };
                Status();
                jQuery.ajax({
                    url: "'.self::getUrlBase().'/Sphere/System/Update/Run",
                    data: { "REST":true, "Version":"'.$Version.'", "_": jQuery.now() },
                    success: function( Response ) {
                        Extract( Response );
                    },
                    async: true
                });
             })</script>'
        );
        return $View;
    }

//    /**
//     * @return Stage
//     */
//    public static function stageSimulation()
//    {
//
//        $View = new Stage();
//        $View->setTitle( 'KREDA Update' );
//        $View->setDescription( 'Simulation' );
//        $View->setContent( System::serviceUpdate()->setupDatabaseSchema( true ) );
//        return $View;
//    }
//
//    /**
//     * @return Stage
//     */
//    public static function stageInstall()
//    {
//
//        $View = new Stage();
//        $View->setTitle( 'KREDA Update' );
//        $View->setDescription( 'Installation' );
//        $View->setContent( System::serviceUpdate()->setupDatabaseSchema( false ) );
//        return $View;
//    }
}
