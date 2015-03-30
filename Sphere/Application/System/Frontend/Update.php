<?php
namespace KREDA\Sphere\Application\System\Frontend;

use KREDA\Sphere\Application\System\Frontend\Update\Progress;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ClusterIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ShareIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageInfo;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageSuccess;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitPrimary;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonSubmitSuccess;
use KREDA\Sphere\Common\Frontend\Form\Element\InputHidden;
use KREDA\Sphere\Common\Frontend\Form\Structure\FormDefault;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayout;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutCol;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutGroup;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutRight;
use KREDA\Sphere\Common\Frontend\Layout\Structure\GridLayoutRow;
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
        $Available = $Updater->getAvailableVersions();
        if (!is_array( $Available ) || count( $Available ) == 1) {
            $UpdateList = new MessageSuccess( '&nbsp;Keine vorherigen Updates verfügbar', new OkIcon() );
        } else {
            krsort( $Available );
            array_pop( $Available );
            $UpdateList = '';
            foreach ((array)$Available as $Update) {
                $Version = new InputHidden( 'Version' );
                $Version->setDefaultValue( $Update['name'], true );
                $UpdateList .=
                    new MessageInfo(
                        new GridLayout( new GridLayoutGroup(
                            new GridLayoutRow( array(
                                new GridLayoutCol(
                                    'Version: '.$Update['name'].'<hr/>'.$Update['message']
                                ),
                                new GridLayoutCol(
                                    new GridLayoutRight( new FormDefault(
                                        new GridFormGroup( new GridFormRow( new GridFormCol( array(
                                            $Version,
                                            new ButtonSubmitPrimary( 'Installieren' )
                                        ) ) ) ),
                                        null, '/Sphere/System/Update/Install'
                                    ) )
                                )
                            ) )
                        ) )
                    );
            }
        }

        $Version = new InputHidden( 'Version' );
        $Version->setDefaultValue( $Next, true );

        $View->setContent(
            new MessageInfo( '&nbsp;Installierte Version: '.$Current, new ClusterIcon() )
            .( $Current == $Next ? new MessageSuccess( '&nbsp;Das System ist auf dem aktuellsten Stand',
                new OkIcon() ) :
                new MessageWarning(
                    '&nbsp;Neuere Version verfügbar: '.$Next,
                    new ShareIcon()
                )
                .new GridLayoutTitle( 'Historie', 'Verfügbare Updates' )
                .$UpdateList
                .new GridLayoutTitle( 'Aktuelle Version' )
                .new MessageSuccess(
                    new GridLayout( new GridLayoutGroup(
                        new GridLayoutRow( array(
                            new GridLayoutCol(
                                'Version: '.$Next.'<hr/>'.$Updater->fetchMessage( $Next )
                            ),
                            new GridLayoutCol(
                                new GridLayoutRight( new FormDefault(
                                    new GridFormGroup( new GridFormRow( new GridFormCol( array(
                                        $Version,
                                        new ButtonSubmitSuccess( 'Installieren' )
                                    ) ) ) ),
                                    null, '/Sphere/System/Update/Install'
                                ) )
                            )
                        ) )
                    ) )
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
            .new GridLayoutTitle( 'Schritt 1', 'Das Update wird heruntergeladen' )
            .new Progress( 'StatusDownload' )
            .new GridLayoutTitle( 'Schritt 2', 'Das Update wird überprüft' )
            .new Progress( 'StatusExtract' )
            .new GridLayoutTitle( 'Schritt 3', 'Dateien werden aktualisiert' )
            .new Progress( 'StatusInstall' )
            .new GridLayoutTitle( 'Schritt 4', 'Datenbanken werden aktualisiert' )
            .new Progress( 'StatusUpdate' )
            .'<script>Client.Use("ModProgress",function(){
                var Run = true;
                function Update() {
                    jQuery("div#StatusUpdate").ModProgress({
                        "Total": 100,
                        "Size": 99.99,
                        "Speed": 0,
                        "Time": 0
                    });
                    jQuery.ajax({
                        url: "'.self::getUrlBase().'/Sphere/System/Update/Clean",
                        data: { "REST":true, "_": jQuery.now() },
                        success: function( Response ) {
                            jQuery("div#StatusUpdate").ModProgress({
                                "Total": 100,
                                "Size": 100,
                                "Speed": 0,
                                "Time": 0
                            });
                            jQuery("div#StatusUpdate").replaceWith( Response );
                        },
                        async: true,
                        timeout: 0,
                        error: function(){
                            Run = false;
                            jQuery("div#StatusUpdate").ModProgress({
                                "Total": 1,
                                "Size": 1,
                                "Speed": 0,
                                "Time": 0,
                                "Class": "progress-bar-danger"
                            });
                        }
                    });
                }
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
                            if( Response != 1 ) {
                                jQuery("div#StatusInstall").ModProgress({
                                    "Total": 1,
                                    "Size": 1,
                                    "Speed": 0,
                                    "Time": 0,
                                    "Class": "progress-bar-danger"
                                });
                            } else {
                                jQuery("div#StatusInstall").ModProgress({
                                    "Total": 100,
                                    "Size": 100,
                                    "Speed": 0,
                                    "Time": 0
                                });
                                Update( Response );
                            }
                            console.log( Response );
                        },
                        async: true,
                        timeout: 0,
                        error: function(){
                            Run = false;
                            jQuery("div#StatusInstall").ModProgress({
                                "Total": 1,
                                "Size": 1,
                                "Speed": 0,
                                "Time": 0,
                                "Class": "progress-bar-danger"
                            });
                        }
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
                        async: true,
                        timeout: 0,
                        error: function(){
                            Run = false;
                            jQuery("div#StatusExtract").ModProgress({
                                "Total": 1,
                                "Size": 1,
                                "Speed": 0,
                                "Time": 0,
                                "Class": "progress-bar-danger"
                            });
                        }
                    });
                }
                function Download() {
                    jQuery.ajax({
                        url: "'.self::getUrlBase().'/Sphere/System/Update/Log",
                        data: { "REST":true, "Version":"'.$Version.'", "_": jQuery.now() },
                        success: function( Response ) {
                            if( Run ) {
                                if( Response ) {
                                    Response = JSON.parse( Response );
                                    jQuery("div#StatusDownload").ModProgress({
                                        "Total": Response.SizeTotal,
                                        "Size": Response.SizeCurrent,
                                        "Speed": Response.DownloadSpeed,
                                        "Time": Response.DownloadTime,
                                        "Message": Number(Response.SizeCurrent / 1024 / 1024 ).toFixed(2) +" / "+ Number(Response.SizeTotal / 1024 / 1024 ).toFixed(2) + "MB (" + Number(Response.DownloadSpeed / 1024).toFixed(2) + "KB/s)"
                                    });
                                }
                            }
                        },
                        async: true,
                        timeout: 0
                    }).done(function( Response ) {
                        if( Response ) {
                            Response = JSON.parse( Response );
                            if( Response.SizeCurrent <= 0 || Response.SizeCurrent != Response.SizeTotal ) {
                                if( Run ) {
                                    window.setTimeout( function(){ Download(); }, 1000 );
                                }
                            } else {
                                /* Download Complete */
                            }
                        } else {
                            if( Run ) {
                                window.setTimeout( function(){ Download(); }, 5000 );
                            }
                        }
                    });
                };
                Download();
                jQuery.ajax({
                    url: "'.self::getUrlBase().'/Sphere/System/Update/Run",
                    data: { "REST":true, "Version":"'.$Version.'", "_": jQuery.now() },
                    success: function( Response ) {
                        if( Response == 0 ) {
                            Run = false;
                            jQuery("div#StatusDownload").ModProgress({
                                "Total": 1,
                                "Size": 1,
                                "Speed": 0,
                                "Time": 0,
                                "Class": "progress-bar-danger"
                            });
                        } else {
                            Run = false;
                            jQuery("div#StatusDownload").ModProgress({
                                "Total": 100,
                                "Size": 100,
                                "Speed": 0,
                                "Time": 0
                            });
                            Extract( Response );
                        }
                    },
                    async: true,
                    timeout: 0,
                    error: function(){
                        Run = false;
                        jQuery("div#StatusDownload").ModProgress({
                            "Total": 1,
                            "Size": 1,
                            "Speed": 0,
                            "Time": 0,
                            "Class": "progress-bar-danger"
                        });
                    }
                });
             })</script>'
        );
        return $View;
    }
}
