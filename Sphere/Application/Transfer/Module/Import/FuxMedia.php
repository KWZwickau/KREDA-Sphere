<?php
namespace KREDA\Sphere\Application\Transfer\Module\Import;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\FileUpload;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Common\AbstractApplication;
use MOC\V\Component\Document\Component\Bridge\Repository\PhpExcel;
use MOC\V\Component\Document\Document;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FuxMedia
 *
 * @package KREDA\Sphere\Application\Transfer\Module\Import
 */
class FuxMedia extends AbstractApplication
{

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        self::registerClientRoute( $Configuration, '/Sphere/Transfer/Import/FuxMedia/FuxSchool/Student',
            __CLASS__.'::frontendImport' )
            ->setParameterDefault( 'File', null );
    }

    /**
     * @param $File
     *
     * @return Stage
     * @throws \MOC\V\Component\Document\Exception\DocumentTypeException
     */
    public static function frontendImport( $File )
    {

        $View = new Stage();
        $View->setTitle( 'Import' );
        $View->setDescription( 'FuxMedia' );
        $View->setContent(
            new Layout(
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn( array(
                            $Form = new Form(
                                new FormGroup(
                                    new FormRow(
                                        new FormColumn(
                                            new FileUpload( 'File', 'Datei auswählen', 'Datei auswählen', null,
                                                array( 'showPreview' => false ) )
                                        )
                                    )
                                )
                                , new SubmitPrimary( 'Hochladen' ) )
                        ,
                            new Warning( 'Erlaubte Dateitypen: Excel (XLS,XLSX)' )
                        ) )
                    )
                )
            )
        );

        /** @var UploadedFile $File */
        if (null !== $File) {
            if ($File->getError()) {
                $Form->setError( 'File', 'Fehler' );
            } else {
                /**
                 * Prepare
                 */
                $File = $File->move( $File->getPath(), $File->getFilename().'.'.$File->getClientOriginalExtension() );
                /**
                 * Read
                 */
                /** @var PhpExcel $Document */
                $Document = Document::getDocument( $File->getPathname() );

                $X = $Document->getSheetColumnCount();
                $Y = $Document->getSheetRowCount();

                /**
                 * Header -> Location
                 */
                $Location = array(
                    'Schüler_Schülernummer'          => null,
                    'Schüler_Name'                   => null,
                    'Schüler_Vorname'                => null,
                    'Schüler_Geschlecht'             => null,
                    'Schüler_Staatsangehörigkeit'    => null,
                    'Schüler_Straße'                 => null,
                    'Schüler_Plz'                    => null,
                    'Schüler_Wohnort'                => null,
                    'Schüler_Ortsteil'               => null,
                    'Schüler_Bundesland'             => null,
                    'Schüler_Geburtsdatum'           => null,
                    'Schüler_Geburtsort'             => null,
                    'Schüler_Geschwister'            => null,
                    'Schüler_Konfession'             => null,
                    'Schüler_Aufnahme_am'            => null,
                    'Schüler_Abgang_am'              => null,
                    'Schüler_allgemeine_Bemerkungen' => null,
                    'Schüler_Krankenkasse'           => null,
                    'Sorgeberechtigter1_Name'        => null,
                    'Sorgeberechtigter1_Vorname'     => null,
                    'Sorgeberechtigter1_Straße'      => null,
                    'Sorgeberechtigter1_Plz'         => null,
                    'Sorgeberechtigter1_Wohnort'     => null,
                    'Sorgeberechtigter1_Ortsteil'    => null,
                    'Sorgeberechtigter2_Name'        => null,
                    'Sorgeberechtigter2_Vorname'     => null,
                    'Sorgeberechtigter2_Straße'      => null,
                    'Sorgeberechtigter2_Plz'         => null,
                    'Sorgeberechtigter2_Wohnort'     => null,
                    'Sorgeberechtigter2_Ortsteil'    => null,
                    'Sorgeberechtigter3_Name'        => null,
                    'Sorgeberechtigter3_Vorname'     => null,
                    'Sorgeberechtigter3_Straße'      => null,
                    'Sorgeberechtigter3_Plz'         => null,
                    'Sorgeberechtigter3_Wohnort'     => null,
                    'Sorgeberechtigter3_Ortsteil'    => null,
                    'Fächer_Religionsunterricht'     => null,
                    'Fächer_Fremdsprache1'           => null,
                    'Fächer_Fremdsprache2'           => null,
                    'Fächer_Fremdsprache3'           => null,
                    'Fächer_Fremdsprache4'           => null,
                );
                for ($RunX = 0; $RunX < $X; $RunX++) {
                    $Value = $Document->getValue( $Document->getCell( $RunX, 0 ) );
                    if (array_key_exists( $Value, $Location )) {
                        $Location[$Value] = $RunX;
                    }
                }

                /**
                 * Import
                 */
                if (!in_array( null, $Location, true )) {
                    for ($RunY = 1; $RunY < $Y; $RunY++) {

                        // Search for Student

                        $StudentNumber = trim( $Document->getValue(
                            $Document->getCell( $Location['Schüler_Schülernummer'], $RunY )
                        ) );
                        $tblStudent = Management::serviceStudent()->entityStudentByNumber( $StudentNumber );

                        /**
                         * Insert/Update
                         */
                        if (!$tblStudent) {

                            // Insert Student-Person

                            $PersonFirstName = $Document->getValue( $Document->getCell( $Location['Schüler_Vorname'],
                                $RunY ) );
                            $PersonLastName = $Document->getValue( $Document->getCell( $Location['Schüler_Name'],
                                $RunY ) );
                            $PersonBirthDay = $Document->getValue( $Document->getCell( $Location['Schüler_Geburtsdatum'],
                                $RunY ) );
                            $PersonBirthPlace = $Document->getValue( $Document->getCell( $Location['Schüler_Geburtsort'],
                                $RunY ) );
                            if (!$PersonBirthPlace) {
                                $PersonBirthPlace = '';
                            }
                            $PersonNationality = $Document->getValue( $Document->getCell( $Location['Schüler_Staatsangehörigkeit'],
                                $RunY ) );
                            if (!$PersonNationality) {
                                $PersonNationality = '';
                            }
                            $PersonGender = $Document->getValue( $Document->getCell( $Location['Schüler_Geschlecht'],
                                $RunY ) );
                            $PersonRemark = $Document->getValue( $Document->getCell( $Location['Schüler_allgemeine_Bemerkungen'],
                                $RunY ) );
                            $PersonGender = strtoupper( $PersonGender[0] );
                            switch ($PersonGender) {
                                case 'M':
                                    $PersonGender = 'Männlich';
                                    $PersonSalutation = 'Herr';
                                    break;
                                case 'W':
                                    $PersonGender = 'Weiblich';
                                    $PersonSalutation = 'Frau';
                                    break;
                            }
                            $PersonDenomination = $Document->getValue( $Document->getCell( $Location['Schüler_Konfession'],
                                $RunY ) );

                            $tblPersonSalutation = Management::servicePerson()->entityPersonSalutationByName( $PersonSalutation );
                            $tblPersonGender = Management::servicePerson()->entityPersonGenderByName( $PersonGender );
                            $tblPersonType = Management::servicePerson()->entityPersonTypeByName( 'Schüler' );

                            $tblPersonStudent = Management::servicePerson()->actionCreatePerson(
                                '', $PersonFirstName, '', $PersonLastName, $PersonBirthDay, $PersonBirthPlace,
                                $PersonNationality, $tblPersonSalutation, $tblPersonGender, $tblPersonType,
                                $PersonRemark, $PersonDenomination
                            );

                            // Address

                            $CityCode = $Document->getValue( $Document->getCell( $Location['Schüler_Plz'], $RunY ) );
                            if (strlen( trim( $CityCode ) ) > 0) {
                                $CityName = $Document->getValue( $Document->getCell( $Location['Schüler_Wohnort'],
                                    $RunY ) );
                                $CityDistrict = $Document->getValue( $Document->getCell( $Location['Schüler_Ortsteil'],
                                    $RunY ) );
                                $State = $Document->getValue( $Document->getCell( $Location['Schüler_Bundesland'],
                                    $RunY ) );

                                $tblAddressState = Management::serviceAddress()->entityAddressStateByName( $State );
                                if (!$tblAddressState) {
                                    $tblAddressState = Management::serviceAddress()->entityAddressStateByName( 'Sachsen' );
                                }
                                $tblAddressCity = Management::serviceAddress()->actionCreateAddressCity(
                                    $CityCode, $CityName, $CityDistrict
                                );
                                $AddressStreet = $Document->getValue( $Document->getCell( $Location['Schüler_Straße'],
                                    $RunY ) );
                                if (preg_match( '![0-9]!is', $AddressStreet )) {
                                    preg_match( '!(.*?)([0-9]+.*?)$!is', $AddressStreet, $Match );
                                    $AddressStreet = trim( $Match[1] );
                                    $AddressNumber = trim( $Match[2] );
                                } else {
                                    $AddressStreet = trim( $AddressStreet );
                                    $AddressNumber = '';
                                }
                                if (strlen( trim( $AddressStreet ) ) > 0) {
                                    $tblAddress = Management::serviceAddress()->actionCreateAddress(
                                        $tblAddressState, $tblAddressCity, $AddressStreet, $AddressNumber
                                    );
                                    Management::servicePerson()->executeAddAddress( $tblPersonStudent->getId(),
                                        $tblAddress->getId() );
                                }
                            }

                            // Student

                            $ChildRank = $Document->getValue( $Document->getCell( $Location['Schüler_Geschwister'],
                                $RunY ) );
                            $ChildRank++;
                            $tblChildRank = Management::serviceStudent()->entityChildRankByName( $ChildRank );

                            // TODO:
                            $tblCourse = Management::serviceCourse()->entityCourseById( 1 );

                            $tblStudent = Management::serviceStudent()->actionCreateStudent(
                                $StudentNumber, $tblPersonStudent, $tblCourse, $tblChildRank
                            );

                            $TransferFromDate = $Document->getValue( $Document->getCell( $Location['Schüler_Aufnahme_am'],
                                $RunY ) );
                            $TransferToDate = $Document->getValue( $Document->getCell( $Location['Schüler_Abgang_am'],
                                $RunY ) );

                            Management::serviceStudent()->actionChangeTransferFromDate( $tblStudent,
                                $TransferFromDate );
                            Management::serviceStudent()->actionChangeTransferToDate( $tblStudent, $TransferToDate );

                            // Subject

                            $Subject = $Document->getValue( $Document->getCell( $Location['Fächer_Religionsunterricht'],
                                $RunY ) );
                            $tblSubject = Management::serviceEducation()->entitySubjectByAcronym( $Subject );
                            if ($tblSubject) {
                                var_dump( $tblSubject );
                            }

                            /**
                             * SB1 - Address
                             */
                            $CityCode = $Document->getValue( $Document->getCell( $Location['Sorgeberechtigter1_Plz'],
                                $RunY ) );
                            if (strlen( trim( $CityCode ) ) > 0) {
                                $CityName = $Document->getValue( $Document->getCell( $Location['Sorgeberechtigter1_Wohnort'],
                                    $RunY ) );
                                $CityDistrict = $Document->getValue( $Document->getCell( $Location['Sorgeberechtigter1_Ortsteil'],
                                    $RunY ) );
                                $State = $Document->getValue( $Document->getCell( $Location['Schüler_Bundesland'],
                                    $RunY ) );
                                $tblAddressState = Management::serviceAddress()->entityAddressStateByName( $State );
                                if (!$tblAddressState) {
                                    $tblAddressState = Management::serviceAddress()->entityAddressStateByName( 'Sachsen' );
                                }
                                $tblAddressCity = Management::serviceAddress()->actionCreateAddressCity(
                                    $CityCode, $CityName, $CityDistrict
                                );
                                $AddressStreet = $Document->getValue( $Document->getCell( $Location['Sorgeberechtigter1_Straße'],
                                    $RunY ) );
                                if (preg_match( '![0-9]!is', $AddressStreet )) {
                                    preg_match( '!(.*?)([0-9]+.*?)$!is', $AddressStreet, $Match );
                                    $AddressStreet = trim( $Match[1] );
                                    $AddressNumber = trim( $Match[2] );
                                } else {
                                    $AddressStreet = trim( $AddressStreet );
                                    $AddressNumber = '';
                                }
                                if (strlen( trim( $AddressStreet ) ) > 0) {
                                    $tblAddress = Management::serviceAddress()->actionCreateAddress(
                                        $tblAddressState, $tblAddressCity, $AddressStreet, $AddressNumber
                                    );
                                }
                            }
                            /**
                             * SB2 - Address
                             */
                            $CityCode = $Document->getValue( $Document->getCell( $Location['Sorgeberechtigter2_Plz'],
                                $RunY ) );
                            if (strlen( trim( $CityCode ) ) > 0) {
                                $CityName = $Document->getValue( $Document->getCell( $Location['Sorgeberechtigter2_Wohnort'],
                                    $RunY ) );
                                $CityDistrict = $Document->getValue( $Document->getCell( $Location['Sorgeberechtigter2_Ortsteil'],
                                    $RunY ) );
                                $State = $Document->getValue( $Document->getCell( $Location['Schüler_Bundesland'],
                                    $RunY ) );
                                $tblAddressState = Management::serviceAddress()->entityAddressStateByName( $State );
                                if (!$tblAddressState) {
                                    $tblAddressState = Management::serviceAddress()->entityAddressStateByName( 'Sachsen' );
                                }
                                $tblAddressCity = Management::serviceAddress()->actionCreateAddressCity(
                                    $CityCode, $CityName, $CityDistrict
                                );
                                $AddressStreet = $Document->getValue( $Document->getCell( $Location['Sorgeberechtigter2_Straße'],
                                    $RunY ) );
                                if (preg_match( '![0-9]!is', $AddressStreet )) {
                                    preg_match( '!(.*?)([0-9]+.*?)$!is', $AddressStreet, $Match );
                                    $AddressStreet = trim( $Match[1] );
                                    $AddressNumber = trim( $Match[2] );
                                } else {
                                    $AddressStreet = trim( $AddressStreet );
                                    $AddressNumber = '';
                                }
                                if (strlen( trim( $AddressStreet ) ) > 0) {
                                    $tblAddress = Management::serviceAddress()->actionCreateAddress(
                                        $tblAddressState, $tblAddressCity, $AddressStreet, $AddressNumber
                                    );
                                }
                            }
                            /**
                             * SB3 - Address
                             */
                            $CityCode = $Document->getValue( $Document->getCell( $Location['Sorgeberechtigter3_Plz'],
                                $RunY ) );
                            if (strlen( trim( $CityCode ) ) > 0) {
                                $CityName = $Document->getValue( $Document->getCell( $Location['Sorgeberechtigter3_Wohnort'],
                                    $RunY ) );
                                $CityDistrict = $Document->getValue( $Document->getCell( $Location['Sorgeberechtigter3_Ortsteil'],
                                    $RunY ) );
                                $State = $Document->getValue( $Document->getCell( $Location['Schüler_Bundesland'],
                                    $RunY ) );
                                $tblAddressState = Management::serviceAddress()->entityAddressStateByName( $State );
                                if (!$tblAddressState) {
                                    $tblAddressState = Management::serviceAddress()->entityAddressStateByName( 'Sachsen' );
                                }
                                $tblAddressCity = Management::serviceAddress()->actionCreateAddressCity(
                                    $CityCode, $CityName, $CityDistrict
                                );
                                $AddressStreet = $Document->getValue( $Document->getCell( $Location['Sorgeberechtigter3_Straße'],
                                    $RunY ) );
                                if (preg_match( '![0-9]!is', $AddressStreet )) {
                                    preg_match( '!(.*?)([0-9]+.*?)$!is', $AddressStreet, $Match );
                                    $AddressStreet = trim( $Match[1] );
                                    $AddressNumber = trim( $Match[2] );
                                } else {
                                    $AddressStreet = trim( $AddressStreet );
                                    $AddressNumber = '';
                                }
                                if (strlen( trim( $AddressStreet ) ) > 0) {
                                    $tblAddress = Management::serviceAddress()->actionCreateAddress(
                                        $tblAddressState, $tblAddressCity, $AddressStreet, $AddressNumber
                                    );
                                }
                            }

                        } else {
                            // Update Student-Person

                            $tblPersonStudent = $tblStudent->getServiceManagementPerson();

                            $PersonFirstName = $Document->getValue( $Document->getCell( $Location['Schüler_Vorname'],
                                $RunY ) );
                            $PersonLastName = $Document->getValue( $Document->getCell( $Location['Schüler_Name'],
                                $RunY ) );
                            $PersonBirthDay = $Document->getValue( $Document->getCell( $Location['Schüler_Geburtsdatum'],
                                $RunY ) );
                            $PersonBirthPlace = $Document->getValue( $Document->getCell( $Location['Schüler_Geburtsort'],
                                $RunY ) );
                            if (!$PersonBirthPlace) {
                                $PersonBirthPlace = '';
                            }
                            $PersonNationality = $Document->getValue( $Document->getCell( $Location['Schüler_Staatsangehörigkeit'],
                                $RunY ) );
                            if (!$PersonNationality) {
                                $PersonNationality = '';
                            }
                            $PersonGender = $Document->getValue( $Document->getCell( $Location['Schüler_Geschlecht'],
                                $RunY ) );
                            $PersonRemark = $Document->getValue( $Document->getCell( $Location['Schüler_allgemeine_Bemerkungen'],
                                $RunY ) );
                            $PersonGender = strtoupper( $PersonGender[0] );
                            switch ($PersonGender) {
                                case 'M':
                                    $PersonGender = 'Männlich';
                                    $PersonSalutation = 'Herr';
                                    break;
                                case 'W':
                                    $PersonGender = 'Weiblich';
                                    $PersonSalutation = 'Frau';
                                    break;
                            }
                            $PersonDenomination = $Document->getValue( $Document->getCell( $Location['Schüler_Konfession'],
                                $RunY ) );

                            $tblPersonSalutation = Management::servicePerson()->entityPersonSalutationByName( $PersonSalutation );
                            $tblPersonGender = Management::servicePerson()->entityPersonGenderByName( $PersonGender );
                            $tblPersonType = Management::servicePerson()->entityPersonTypeByName( 'Schüler' );

                            if (!$tblPersonStudent) {
                                $tblPersonStudent = Management::servicePerson()->actionCreatePerson(
                                    '', $PersonFirstName, '', $PersonLastName, $PersonBirthDay,
                                    $PersonBirthPlace,
                                    $PersonNationality, $tblPersonSalutation, $tblPersonGender, $tblPersonType,
                                    $PersonRemark, $PersonDenomination
                                );
                            } else {
                                Management::servicePerson()->actionChangePerson(
                                    $tblPersonStudent, '', $PersonFirstName, '', $PersonLastName, $PersonBirthDay,
                                    $PersonBirthPlace,
                                    $PersonNationality, $tblPersonSalutation, $tblPersonGender, $tblPersonType,
                                    $PersonRemark, $PersonDenomination
                                );
                            }

                            // Update Student

                            Management::serviceStudent()->actionChangePerson( $tblStudent, $tblPersonStudent );

                            $TransferFromDate = $Document->getValue( $Document->getCell( $Location['Schüler_Aufnahme_am'],
                                $RunY ) );
                            $TransferToDate = $Document->getValue( $Document->getCell( $Location['Schüler_Abgang_am'],
                                $RunY ) );

                            Management::serviceStudent()->actionChangeTransferFromDate( $tblStudent,
                                $TransferFromDate );
                            Management::serviceStudent()->actionChangeTransferToDate( $tblStudent, $TransferToDate );
                        }
                    }
                }
            }
        }

        return $View;
    }
}
