<?php
namespace Console;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Common\Extension\Debugger;
use KREDA\Sphere\Common\Updater\Type\GitHub;
use MOC\V\Core\AutoLoader\AutoLoader;
/**
 * Setup: Php
 */
header( 'Content-type: text/html; charset=utf-8' );
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );
set_time_limit( 60 * 10 );
session_start();
session_write_close();
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

$View = null;
/**
 * Fake PersonList
 */
for( $R = 10000; $R > 0; $R-- ) {
    Management::servicePerson()->executeCreatePerson(
        $View,
        array(
            'Salutation' => rand( 1, 2 ),
            'First'      => Management::extensionFaker()->getFirstName(),
            'Middle'     => 'FAKER',
            'Last'       => Management::extensionFaker()->getLastName()
        ),
        array(
            'Nationality' => 'FAKER',
            'Type'        => rand( 1, 3 )
        ),
        array(
            'Gender' => rand( 1, 2 ),
            'Date'   => Management::extensionFaker()->getDate(),
            'Place'  => Management::extensionFaker()->getCityName()
        ),
        array(
            'Submit' => array()
        )
    );
    print '.';
}

print 'OK';
