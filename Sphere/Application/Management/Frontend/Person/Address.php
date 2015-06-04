<?php
namespace KREDA\Sphere\Application\Management\Frontend\Person;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Wire;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DisableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MapMarkerIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuestionIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TransferIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Button\Structure\ButtonGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\AutoCompleter;
use KREDA\Sphere\Client\Frontend\Input\Type\NumberField;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutAddress;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Address
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Person
 */
class Address extends AbstractFrontend
{

    /**
     * @param $Id
     * @param $State
     * @param $City
     * @param $Street
     *
     * @return Stage
     */
    public static function stageEdit( $Id, $State, $City, $Street )
    {

        $View = new Stage( 'Adressen', 'Bearbeiten' );

        $tblPerson = Management::servicePerson()->entityPersonById( $Id );

        $Form = self::formAddress();
        $Form->appendFormButton( new SubmitPrimary( 'Adresse hinzufügen' ) );

        $View->setContent(
            new Success( $tblPerson->getTblPersonSalutation()->getName().' '.$tblPerson->getFullName() )
            .new Primary( 'Zurück zur Person', '/Sphere/Management/Person/Edit', null, array( 'Id' => $Id ) )
            .self::layoutAddress( $tblPerson, true )
            .new Layout(
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn(
                            Management::serviceAddress()->executeCreatePersonAddress(
                                $Form, $State, $City, $Street, $tblPerson
                            )
                        )
                    ), new LayoutTitle( 'Adresse hinzufügen' )
                )
            )
        );

        return $View;
    }

    /**
     * @return Form
     */
    public static function formAddress()
    {

        $tblAddress = Management::serviceAddress()->entityAddressAll();
        $tblAddressCity = Management::serviceAddress()->entityAddressCityAll();
        $tblAddressState = Management::serviceAddress()->entityAddressStateAll();

        return new Form(
            new FormGroup( array(
                new FormRow( array(
                    new FormColumn(
                        new AutoCompleter( 'Street[Name]', 'Strasse', 'Strasse',
                            array( 'StreetName' => $tblAddress ) )
                        , 5 ),
                    new FormColumn(
                        new NumberField( 'Street[Number]', 'Hausnummer', 'Hausnummer' )
                        , 2 ),
                ) ),
                new FormRow( array(
                    new FormColumn(
                        new AutoCompleter( 'City[Code]', 'Postleitzahl', 'Postleitzahl',
                            array( 'Code' => $tblAddressCity ) )
                        , 2 ),
                    new FormColumn(
                        new AutoCompleter( 'City[Name]', 'Ort', 'Ort',
                            array( 'Name' => $tblAddressCity ) )
                        , 5 ),
                    new FormColumn(
                        new AutoCompleter( 'City[District]', 'Ortsteil', 'Ortsteil',
                            array( 'District' => $tblAddressCity ) )
                        , 5 ),
                ) ),
                new FormRow( array(
                    new FormColumn(
                        new SelectBox( 'State', 'Bundesland', array( 'Name' => $tblAddressState ) )
                        , 5 ),
                ) ),
            ) )
        );
    }

    /**
     * @param TblPerson $tblPerson
     * @param bool      $hasRemove
     *
     * @return Layout
     */
    public static function layoutAddress( TblPerson $tblPerson, $hasRemove = false )
    {

        $tblAddressList = Management::servicePerson()->entityAddressAllByPerson( $tblPerson );

        if (!empty( $tblAddressList )) {
            /** @noinspection PhpUnusedParameterInspection */
            array_walk( $tblAddressList, function ( TblAddress &$tblAddress, $Index, $Data ) {

                if ($Index == 0) {
                    $AddressType = new MapMarkerIcon().' Hauptadresse';
                    $PanelType = LayoutPanel::PANEL_TYPE_WARNING;
                } else {
                    $AddressType = new MapMarkerIcon().' Adresse';
                    $PanelType = LayoutPanel::PANEL_TYPE_DEFAULT;
                }

                /** @var bool[]|TblPerson[] $Data */
                $tblAddress = new LayoutColumn(
                    new LayoutPanel(
                        $AddressType, new LayoutAddress( $tblAddress ), $PanelType,
                        ( $Data[0]
                            ? new ButtonGroup( array(
                                ( $PanelType != LayoutPanel::PANEL_TYPE_PRIMARY
                                    ? new Primary(
                                        'Hauptadresse', '/Sphere/Management/Person/Address/Type', new TransferIcon(),
                                        array( 'Id' => $Data[1]->getId(), 'Address' => $tblAddress->getId() )
                                    )
                                    : ''
                                ),
                                new Danger(
                                    'Löschen', '/Sphere/Management/Person/Address/Destroy', new RemoveIcon(),
                                    array( 'Id' => $Data[1]->getId(), 'Address' => $tblAddress->getId() )
                                ),
                            ) )
                            : null
                        )
                    ), 4 );
            }, array( $hasRemove, $tblPerson ) );
        } else {
            $tblAddressList = array(
                new LayoutColumn(
                    new Warning( 'Keine Adressen hinterlegt', new WarningIcon() )
                )
            );
        }

        return new Layout(
            new LayoutGroup( new LayoutRow( $tblAddressList ), new LayoutTitle( 'Adressen' ) )
        );
    }

    /**
     * @param int  $Id
     * @param int  $Address
     * @param bool $Confirm
     *
     * @return Stage
     */
    public static function stageDestroy( $Id, $Address, $Confirm = false )
    {

        $View = new Stage();
        $View->setTitle( 'Adressen' );
        $View->setDescription( 'Löschen' );

        $tblPerson = Management::servicePerson()->entityPersonById( $Id );
        $tblAddress = Management::serviceAddress()->entityAddressById( $Address );
        $tblPersonAddress = Management::servicePerson()->entityPersonAddressByPersonAndAddress( $tblPerson,
            $tblAddress );
        if (!$Confirm) {
            $View->setContent(
                new Layout(
                    new LayoutGroup( array(
                        new LayoutRow(
                            new LayoutColumn( array(
                                new Warning( new LayoutAddress( $tblPersonAddress->getTblAddress() ) ),
                                new Warning( 'Wollen Sie die Addresse wirklich löschen?', new QuestionIcon() ),
                            ) )
                        ),
                        new LayoutRow(
                            new LayoutColumn( array(
                                new Danger(
                                    'Ja', '/Sphere/Management/Person/Address/Destroy', new OkIcon(),
                                    array( 'Id' => $Id, 'Address' => $Address, 'Confirm' => true )
                                ),
                                new Primary(
                                    'Nein', '/Sphere/Management/Person/Address/Edit', new DisableIcon(),
                                    array( 'Id' => $Id )
                                )
                            ) )
                        )
                    ) )
                )
            );
        } else {
            if (true !== ( $Wire = Management::servicePerson()->executeRemoveAddress( $Id, $Address ) )) {
                return new Wire( $Wire );
            }
            $View->setContent(
                new Layout( new LayoutGroup( array(
                    new LayoutRow(
                        new LayoutColumn( array(
                            new Success( new LayoutAddress( $tblPersonAddress->getTblAddress() ) ),
                            new Success( 'Die Adresse wurde erfolgreich gelöscht' ),
                        ) )
                    ),
                    new LayoutRow(
                        new LayoutColumn( array(
                            new Redirect( '/Sphere/Management/Person/Address/Edit', 1, array( 'Id' => $Id ) )
                        ) )
                    )
                ) ) ) );
        }
        return $View;
    }

}


