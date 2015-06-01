<?php
namespace KREDA\Sphere\Application\Management\Frontend\Person;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PencilIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\AutoCompleter;
use KREDA\Sphere\Client\Frontend\Input\Type\NumberField;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Address
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Person
 */
class Address extends AbstractFrontend
{

    /**
     * @param TblPerson $tblPerson
     *
     * @return Layout
     */
    public static function layoutAddress( TblPerson $tblPerson )
    {

        $tblAddressList = Management::servicePerson()->entityAddressAllByPerson( $tblPerson );
        if (!empty( $tblAddressList )) {
            array_walk( $tblAddressList, function ( TblAddress &$tblAddress ) {

                $tblAddress->Code = $tblAddress->getTblAddressCity()->getCode();
                $tblAddress->Name = $tblAddress->getTblAddressCity()->getName();
                $tblAddress->District = $tblAddress->getTblAddressCity()->getDistrict();
                $tblAddress->State = $tblAddress->getTblAddressState()->getName();
            } );
        }
        return new Layout(
            new LayoutGroup(
                new LayoutRow(
                    new LayoutColumn( array(
                        new TableData( $tblAddressList, null, array(
                            'StreetName'   => 'Strasse',
                            'StreetNumber' => 'Hausnummer',
                            'Code'         => 'Postleitzahl',
                            'Name'         => 'Ort',
                            'District'     => 'Ortsteil',
                            'State'        => 'Bundesland',
                        ) ),
                        new Primary( 'Bearbeiten', '/Sphere/Management/Person/Address/Edit', new PencilIcon(),
                            array( 'Id' => $tblPerson->getId() )
                        )
                    ) )
                ), new LayoutTitle( 'Adressen' )
            )
        );
    }

    public static function stageCreate( $Id, $State, $City, $Street )
    {

        $tblPerson = Management::servicePerson()->entityPersonById( $Id );

        $View = new Stage( 'Person', 'Bearbeiten - Adresse' );

        $tblAddressList = Management::servicePerson()->entityAddressAllByPerson( $tblPerson );
        if (!empty( $tblAddressList )) {
            array_walk( $tblAddressList, function ( TblAddress &$tblAddress ) {

                $tblAddress->Code = $tblAddress->getTblAddressCity()->getCode();
                $tblAddress->Name = $tblAddress->getTblAddressCity()->getName();
                $tblAddress->District = $tblAddress->getTblAddressCity()->getDistrict();
                $tblAddress->State = $tblAddress->getTblAddressState()->getName();
            } );
        }

        $View->setContent(
            new LayoutGroup( array(
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new Success( $tblPerson->getTblPersonSalutation()->getName().' '.$tblPerson->getFullName() ),
                        new TableData( $tblAddressList, null, array(
                            'StreetName'   => 'Strasse',
                            'StreetNumber' => 'Hausnummer',
                            'Code'         => 'Postleitzahl',
                            'Name'         => 'Ort',
                            'District'     => 'Ortsteil',
                            'State'        => 'Bundesland',
                        ) ),
                        Management::serviceAddress()->executeCreatePersonAddress(
                            new Form(
                                new FormGroup( array(
                                    new FormRow( array(
                                        new FormColumn(
                                            new AutoCompleter( 'Street[Name]', 'Strasse', 'Strasse',
                                                array( 'StreetName' => Management::serviceAddress()->entityAddressAll() )
                                            )
                                            , 5 ),
                                        new FormColumn(
                                            new NumberField( 'Street[Number]', 'Hausnummer', 'Hausnummer' )
                                            , 2 ),
                                    ) ),
                                    new FormRow( array(
                                        new FormColumn(
                                            new TextField( 'City[Code]', 'Postleitzahl', 'Postleitzahl' )
                                            , 2 ),
                                        new FormColumn(
                                            new AutoCompleter( 'City[Name]', 'Ort', 'Ort',
                                                array( 'Name' => Management::serviceAddress()->entityAddressCityAll() )
                                            )
                                            , 5 ),
                                        new FormColumn(
                                            new TextField( 'City[District]', 'Ortsteil', 'Ortsteil' )
                                            , 5 ),
                                    ) ),
                                    new FormRow( array(
                                        new FormColumn(
                                            new SelectBox( 'State', 'Bundesland',
                                                array( 'Name' => Management::serviceAddress()->entityAddressStateAll() )
                                            )
                                            , 5 ),
                                    ) ),
                                ) ), new SubmitPrimary( 'Adresse hinzufügen' )
                            ), $State, $City, $Street, $tblPerson )
                    ) )
                ) )
            ) )
        );
        return $View;
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return Form
     */
    public static function formAddress( TblPerson $tblPerson, $State, $City, $Street )
    {

        $tblAddressState = Management::serviceAddress()->entityAddressStateAll();

        $tblAddressStreet = Management::serviceAddress()->entityAddressAll();
        if (!empty( $tblAddressStreet )) {
            array_walk( $tblAddressStreet, function ( TblAddress &$tblAddress ) {

                $tblAddress = $tblAddress->getStreetName();
            } );
        }
        $tblAddressStreet = array_unique( $tblAddressStreet );

        $tblAddressCity = Management::serviceAddress()->entityAddressAll();
        if (!empty( $tblAddressCity )) {
            array_walk( $tblAddressCity, function ( TblAddress &$tblAddress ) {

                $tblAddress = $tblAddress->getTblAddressCity()->getName();
            } );
        }
        $tblAddressCity = array_unique( $tblAddressCity );

        $tblPersonAddressList = Management::servicePerson()->entityAddressAllByPerson( $tblPerson );
        if (!empty( $tblPersonAddressList )) {
            array_walk( $tblPersonAddressList, function ( TblAddress &$tblPersonAddress ) {

                $tblPersonAddress->Code = $tblPersonAddress->getTblAddressCity()->getCode();
                $tblPersonAddress->Name = $tblPersonAddress->getTblAddressCity()->getName();
                $tblPersonAddress->District = $tblPersonAddress->getTblAddressCity()->getDistrict();
                $tblPersonAddress->State = $tblPersonAddress->getTblAddressState()->getName();

            } );
        }

        return new Layout(
            new LayoutGroup( array(
                new LayoutRow( array(
                    new LayoutColumn( array(
                        new TableData( $tblPersonAddressList, null, array(
                            'StreetName'   => 'Strasse',
                            'StreetNumber' => 'Hausnummer',
                            'Code'         => 'Postleitzahl',
                            'Name'         => 'Ort',
                            'District'     => 'Ortsteil',
                            'State'        => 'Bundesland',
                        ) ),
                        Management::serviceAddress()->executeCreatePersonAddress(
                            new Form(
                                new FormGroup( array(
                                    new FormRow( array(
                                        new FormColumn(
                                            new AutoCompleter( 'Street[Name]', 'Strasse', 'Strasse', $tblAddressStreet )
                                            , 5 ),
                                        new FormColumn(
                                            new NumberField( 'Street[Number]', 'Hausnummer', 'Hausnummer' )
                                            , 2 ),
                                    ) ),
                                    new FormRow( array(
                                        new FormColumn(
                                            new TextField( 'City[Code]', 'Postleitzahl', 'Postleitzahl' )
                                            , 2 ),
                                        new FormColumn(
                                            new AutoCompleter( 'City[Name]', 'Ort', 'Ort', $tblAddressCity )
                                            , 5 ),
                                        new FormColumn(
                                            new TextField( 'City[District]', 'Ortsteil', 'Ortsteil' )
                                            , 5 ),
                                    ) ),
                                    new FormRow( array(
                                        new FormColumn(
                                            new SelectBox( 'State', 'Bundesland', array(
                                                'Name' => $tblAddressState
                                            ) )
                                            , 5 ),
                                    ) ),
                                ) ), new SubmitPrimary( 'Adresse hinzufügen' ) )
                            , $State, $City, $Street, $tblPerson )
                    ) )
                ) ),
            ), new LayoutTitle( 'Adressdaten' ) )
        );

    }
}


