<?php
namespace Console;
use KREDA\Sphere\Common\Extension\Debugger;
use KREDA\Sphere\Common\Updater\Type\GitHub;
use MOC\V\Core\AutoLoader\AutoLoader;
/**
 * Setup: Php
 */
header( 'Content-type: text/html; charset=utf-8' );
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );
set_time_limit( 60 * 5 );
session_start();
date_default_timezone_set( 'Europe/Berlin' );
/**
 * Setup: Loader
 */
require_once( __DIR__.'/../../Library/MOC-V/Core/AutoLoader/AutoLoader.php' );
AutoLoader::getNamespaceAutoLoader( 'MOC\V', __DIR__.'/../../Library/MOC-V' );
AutoLoader::getNamespaceAutoLoader( 'KREDA\Sphere', __DIR__.'/../../', 'KREDA' );
AutoLoader::getNamespaceAutoLoader( 'Markdownify', __DIR__.'/../../Library/Markdownify/2.1.6/src' );
/**
 * Setup: Debugger
 */
new Debugger();

print '<pre>';

$Updater = new GitHub();
////var_dump( $Updater );
//$Tags = $Updater->fetchTags();
////var_dump( $Tags );
//array_unshift( $Tags, array( 'name' => '0.0.0-Install' ) );
//shuffle( $Tags );
//foreach( $Tags as $Last ) {
//    foreach( $Tags as $Tag ) {
//        $Mark = (int)$Updater->compareVersion( $Tag["name"], $Last["name"] );
//        print '<div style="padding: 1px; color:'.($Mark?'green':'darkred').';">'.$Last["name"].' > '.$Tag["name"].' = '.$Mark.'</div>';
//    }
//}
$CurrentVersion = $Updater->getCurrentVersion();
var_dump( 'Current '.$CurrentVersion );
$NextVersion = $Updater->getNextVersion();
var_dump( 'Next '.$NextVersion );
$LatestVersion = $Updater->getLatestVersion();
var_dump( 'Latest '.$LatestVersion );

var_dump( $Cache = $Updater->downloadVersion( $NextVersion ) );

var_dump( $Updater->extractArchive( $Cache ) );
