<?php
namespace Console;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Common\Extension\Debugger;
use KREDA\Sphere\Common\Updater\Type\GitHub;
use MOC\V\Component\Document\Component\Bridge\Repository\PhpExcel;
use MOC\V\Component\Document\Document;
use MOC\V\Core\AutoLoader\AutoLoader;
/**
 * Setup: Php
 */
header( 'Content-type: text/html; charset=utf-8' );
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );
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

/** @var PhpExcel $Csv */
$Csv = Document::getExcelDocument( __DIR__.'/../Docs/DE.csv' );

for( $R = 0; $R <500; $R++ ) {
    if( !(int)$Csv->getValue( $Csv->getCell( 15, $R ) ) ) {
        var_dump(
            Management::serviceAddress()->executeCreateAddressCity(
                new Form( new FormGroup( new FormRow( new FormColumn( array( new TextField( 'Code' ), new TextField( 'Name' ) ) ) ) ) ),
                $Csv->getValue( $Csv->getCell( 7, $R ) ),
                $Csv->getValue( $Csv->getCell( 3, $R ) )
            )->__toString()
//        $Csv->getValue( $Csv->getCell( 3, $R ) ),
//        $Csv->getValue( $Csv->getCell( 7, $R ) )
        );
    }
}


//
//$View = null;
///**
// * Fake PersonList
// */
//for( $R = 10000; $R > 0; $R-- ) {
//    $Faker = Management::extensionFaker();
//    Management::servicePerson()->executeCreatePerson(
//        $View,
//        array(
//            'Salutation' => rand( 1, 2 ),
//            'First'      => $Faker->getFirstName(),
//            'Middle'     => (!rand( 0, 3 )?$Faker->getFirstName():''),
//            'Last'       => $Faker->getLastName()
//        ),
//        array(
//            'Nationality' => 'FAKER',
//            'Type'        => rand( 1, 3 )
//        ),
//        array(
//            'Gender' => rand( 1, 2 ),
//            'Date'   => $Faker->getDate(),
//            'Place'  => $Faker->getCityName()
//        ),
//        array(
//            'Submit' => array()
//        )
//    );
//    print '.';
//}

print 'OK';
