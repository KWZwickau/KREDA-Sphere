<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Application\Management\Service\Company\Entity\TblCompany;
use KREDA\Sphere\Application\Management\Service\Company\Entity\TblCompanyAddress;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ChevronLeftIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ListIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MapMarkerIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PencilIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PlusIcon;
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
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutAddress;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Company
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Company extends AbstractFrontend
{
    /**
     * @return Stage
     */
    public static function frontendCompanyList()
    {
        $View = new Stage();
        $View->setTitle( 'Firmen' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt alle vorhandenen Firmen an' );
        $View->addButton(
            new Primary( 'Firma anlegen', '/Sphere/Management/Company/Create', new PlusIcon() )
        );

        $tblCompanyList = Management::serviceCompany()->entityCompanyAll();

        if (!empty( $tblCompanyList ))
        {
            array_walk( $tblCompanyList, function ( TblCompany &$tblCompany )
            {
                $tblAdressList = Management::serviceCompany()->entityAddressAllByCompany($tblCompany);
                if (!empty($tblAdressList))
                {
                    $tblCompany->ZipCode = $tblAdressList[0]->getTblAddressCity()->getCode();
                    $tblCompany->City = $tblAdressList[0]->getTblAddressCity()->getName();
                }
                $tblCompany->Option =
                    (new Primary( 'Bearbeiten', '/Sphere/Management/Company/Edit',
                        new EditIcon(), array('Id' => $tblCompany->getId())))->__toString() .
                    (new Danger( 'Löschen', '/Sphere/Management/Company/Destroy',
                        new RemoveIcon(), array('Id' => $tblCompany->getId())))->__toString();
            });
        }

        $View->setContent(
            new TableData( $tblCompanyList, null,
                array(
                    'Name' => 'Name',
                    'ZipCode' => 'PLZ',
                    'City' => 'Ort',
                    'Option' => 'Option'
                )
            )
        );

        return $View;
    }

    /**
     * @param $Company
     *
     * @return Stage
     */
    public static function frontendCompanyCreate( $Company )
    {
        $View = new Stage();
        $View->setTitle( 'Firma' );
        $View->setDescription( 'Hinzufügen' );

        $View->setContent(Management::serviceCompany()->executeCreateCompany(
            new Form( array(
                new FormGroup( array(
                    new FormRow( array(
                        new FormColumn(
                            new TextField( 'Company[Name]', 'Name', 'Name', new ConversationIcon()
                            ), 6 ),
                    ) )
                ))
            ), new SubmitPrimary( 'Hinzufügen' )), $Company));

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendCompanyDestroy( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Firma' );
        $View->setDescription( 'Entfernen' );

        $tblCompany = Management::serviceCompany()->entityCompanyById( $Id );
        $View->setContent(Management::serviceCompany()->executeDestroyCompany( $tblCompany ));

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendCompanyEdit ( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Firma' );
        $View->setDescription( 'Bearbeiten' );
        $View->addButton( new Primary( 'Zurück zur Übersicht', '/Sphere/Management/Company', new ChevronLeftIcon()));

        if (empty( $Id ))
        {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        }
        else
        {
            $tblCompany = Management::serviceCompany()->entityCompanyById( $Id );
            if (empty( $tblCompany ))
            {
                $View->setContent( new Warning( 'Die Leistung konnte nicht abgerufen werden' ) );
            }
            else
            {
                $View->setContent(
                    new Layout(array(
                            new LayoutGroup( array(
                                new LayoutRow( array(
                                    new LayoutColumn(
                                        new LayoutPanel('Name', $tblCompany->getName()
                                            , LayoutPanel::PANEL_TYPE_SUCCESS ), 4
                                    )
                                ) )
                            ), new LayoutTitle( 'Grunddaten')),
                    ))
                    . new Primary( 'Bearbeiten', '/Sphere/Management/Company/Basic/Edit', new PencilIcon(),
                        array( 'Id' => $tblCompany->getId() ))
                    . self::layoutAddress( $tblCompany )
                    . new Primary( 'Bearbeiten', '/Sphere/Management/Company/Address/Edit', new PencilIcon(),
                        array( 'Id' => $tblCompany->getId() ))
                );
            }
        }

        return $View;
    }


    /**
     * @param $Id
     * @param $Company
     *
     * @return Stage
     */
    public static function frontendCompanyBasicEdit ( $Id, $Company )
    {
        $View = new Stage();
        $View->setTitle( 'Firma' );
        $View->setDescription( 'Grunddaten' );

        if (empty( $Id ))
        {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        }
        else
        {
            $tblCompany = Management::serviceCompany()->entityCompanyById( $Id );
            if (empty( $tblCompany ))
            {
                $View->setContent( new Warning( 'Die Leistung konnte nicht abgerufen werden' ) );
            }
            else
            {
                $Global = self::extensionSuperGlobal();
                $Global->POST['Company']['Name'] = $tblCompany->getName();
                $Global->savePost();

                $View->setContent(
                    Management::serviceCompany()->executeEditCompany(
                        new Form( array(
                                new FormGroup( array(
                                    new FormRow( array(
                                        new FormColumn(
                                            new TextField( 'Company[Name]', 'Name', 'Name', new ConversationIcon()
                                            ), 6 ),
                                    ) )
                                ))
                            ), new SubmitPrimary( 'Änderungen speichern' )
                        ), $tblCompany, $Company
                    )
                );
            }
        }

        return $View;
    }

    /**
     * @param $Id
     * @param $State
     * @param $City
     * @param $Street
     *
     * @return Stage
     */
    public static function frontendCompanyAddressEdit( $Id, $State, $City, $Street )
    {

        $View = new Stage( 'Adressen', 'Bearbeiten' );

        $tblCompany = Management::serviceCompany()->entityCompanyById( $Id );

        $Form = self::formAddress();
        $Form->appendFormButton( new SubmitPrimary( 'Adresse hinzufügen' ) );

        $View->setContent(
            new Layout(array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn(
                            new LayoutPanel('Name', $tblCompany->getName()
                                , LayoutPanel::PANEL_TYPE_SUCCESS ), 4
                        )
                    ) )
                ))
            ))
            .new Primary( 'Zurück zur Firma', '/Sphere/Management/Company/Edit', new ChevronLeftIcon(), array( 'Id' => $Id ) )
            .self::layoutAddress( $tblCompany, true )
            .new Layout(
                new LayoutGroup(
                    new LayoutRow(
                        new LayoutColumn(
                            Management::serviceAddress()->executeCreateCompanyAddress(
                                $Form, $State, $City, $Street, $tblCompany
                            )
                        )
                    ), new LayoutTitle( 'Adresse hinzufügen' )
                )
            )
        );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendCompanyAddressRemove ( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Adresse entfernen' );
        $tblCompanyAddress = Management::serviceCompany()->entityCompanyAddressById( $Id );
        if (!empty($tblCompanyAddress))
        {
            $View ->setContent( Management::serviceCompany()->executeRemoveCompanyAddress( $tblCompanyAddress ));
        }

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
     * @param TblCompany $tblCompany
     * @param $hasRemove
     *
     * @return Layout
     */
    public static function layoutAddress( TblCompany $tblCompany, $hasRemove = false )
    {
        $tblAddressList = Management::serviceCompany()->entityAddressAllByCompany( $tblCompany );
        if (!empty( $tblAddressList ))
        {
            foreach ($tblAddressList as $Key => &$tblAddress)
            {
                if ($Key == 0)
                {
                    $AddressType = new MapMarkerIcon().' Hauptadresse';
                    $PanelType = LayoutPanel::PANEL_TYPE_WARNING;
                }
                else
                {
                    $AddressType = new MapMarkerIcon().' Adresse';
                    $PanelType = LayoutPanel::PANEL_TYPE_DEFAULT;
                }

                $tblCompanyAddress = Management::serviceCompany()->entityCompanyAddressByCompanyAndAddress($tblCompany, $tblAddress);
                $tblAddress = new LayoutColumn(
                    new LayoutPanel(
                        $AddressType, new LayoutAddress( $tblAddress ), $PanelType,
                            ($hasRemove
                                 ? new ButtonGroup( array(
                                    ( $Key != 0
                                        ? new Primary(
                                            'Hauptadresse', '/Sphere/Management/Company/Address/Type', new TransferIcon(),
                                            array( 'Id' => $tblCompanyAddress->getId() )
                                        )
                                        : ''
                                    ),
                                    new Danger(
                                        'Löschen', '/Sphere/Management/Company/Address/Remove', new RemoveIcon(),
                                        array( 'Id' => $tblCompanyAddress->getId() )
                                    )
                                ) )
                                :null
                            )
                    ), 4 );
            }
        }
        else
        {
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
}
