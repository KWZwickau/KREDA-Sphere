<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Module\Commodity;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtorCommodity;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasketItem;
use KREDA\Sphere\Application\Billing\Service\Basket\Entity\TblBasketPerson;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ChevronLeftIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ChevronRightIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MinusIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MoneyEuroIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PlusIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuantityIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\DatePicker;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Basket
 *
 * @package KREDA\Sphere\Application\Billing\Frontend
 */
class Basket extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function frontendBasketList()
    {

        $View = new Stage();
        $View->setTitle( 'Warenkorb' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt die vorhandenen Warenkörbe, welche noch nicht fakuriert wurden' );
        $View->addButton(
            new Primary( 'Warenkorb anlegen', '/Sphere/Billing/Basket/Create' )
        );

        $tblBasketAll = Billing::serviceBasket()->entityBasketAll();

        if (!empty( $tblBasketAll )) {
            array_walk( $tblBasketAll, function ( TblBasket &$tblBasket ) {

                $tblBasket->Number = $tblBasket->getId();
                $tblBasket->Option =
                    ( new Primary( 'Weiter Bearbeiten', '/Sphere/Billing/Basket/Commodity/Select',
                        new EditIcon(), array(
                            'Id' => $tblBasket->getId()
                        ) ) )->__toString().
                    ( new Danger( 'Löschen', '/Sphere/Billing/Basket/Delete',
                        new RemoveIcon(), array(
                            'Id' => $tblBasket->getId()
                        ) ) )->__toString();
            } );
        }

        $View->setContent(
            new TableData( $tblBasketAll, null,
                array(
                    'Number' => 'Nummer',
                    'CreateDate' => 'Erstellt am',
                    'Option' => 'Option'
                )
            )
        );

        return $View;
    }

    /**
     *
     * @return Stage
     */
    public static function frontendBasketCreate()
    {

        $View = new Stage();
        $View->setTitle( 'Warenkorb' );
        $View->setDescription( 'Erstellen' );

        $View->setContent( Billing::serviceBasket()->executeCreateBasket() );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketDelete( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Warenkorb' );
        $View->setDescription( 'Löschen' );

        $tblBasket = Billing::serviceBasket()->entityBasketById( $Id );
        $View->setContent( Billing::serviceBasket()->executeDestroyBasket( $tblBasket ) );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketCommoditySelect( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Leistungen' );
        $View->setDescription( 'Auswählen' );
        $View->setMessage( 'Bitte wählen Sie die Leistungen zur Fakturierung aus' );
        $View->addButton( new Primary( 'Weiter', '/Sphere/Billing/Basket/Item',
            new ChevronRightIcon(), array(
                'Id' => $Id
            ) ) );

        $tblBasket = Billing::serviceBasket()->entityBasketById( $Id );
        $tblCommodityAll = Billing::serviceCommodity()->entityCommodityAll();
        $tblCommodityAllByBasket = Billing::serviceBasket()->entityCommodityAllByBasket( $tblBasket );

        if (!empty( $tblCommodityAllByBasket )) {
            $tblCommodityAll = array_udiff( $tblCommodityAll, $tblCommodityAllByBasket,
                function ( TblCommodity $ObjectA, TblCommodity $ObjectB ) {

                    return $ObjectA->getId() - $ObjectB->getId();
                }
            );

            array_walk( $tblCommodityAllByBasket,
                function ( TblCommodity &$tblCommodity, $Index, TblBasket $tblBasket ) {

                    $tblCommodity->Type = $tblCommodity->getTblCommodityType()->getName();
                    $tblCommodity->ItemCount = Billing::serviceCommodity()->countItemAllByCommodity( $tblCommodity );
                    $tblCommodity->SumPriceItem = Billing::serviceCommodity()->sumPriceItemAllByCommodity( $tblCommodity );
                    $tblCommodity->Option =
                        ( new Danger( 'Entfernen', '/Sphere/Billing/Basket/Commodity/Remove',
                            new MinusIcon(), array(
                                'Id'          => $tblBasket->getId(),
                                'CommodityId' => $tblCommodity->getId()
                            ) ) )->__toString();
                }, $tblBasket );
        }

        if (!empty( $tblCommodityAll )) {
            array_walk( $tblCommodityAll, function ( TblCommodity $tblCommodity, $Index, TblBasket $tblBasket ) {

                $tblCommodity->Type = $tblCommodity->getTblCommodityType()->getName();
                $tblCommodity->ItemCount = Billing::serviceCommodity()->countItemAllByCommodity( $tblCommodity );
                $tblCommodity->SumPriceItem = Billing::serviceCommodity()->sumPriceItemAllByCommodity( $tblCommodity );
                $tblCommodity->Option =
                    ( new Primary( 'Hinzufügen', '/Sphere/Billing/Basket/Commodity/Add',
                        new PlusIcon(), array(
                            'Id' => $tblBasket->getId(),
                            'CommodityId' => $tblCommodity->getId()
                        ) ) )->__toString();
            }, $tblBasket );
        }

        $View->setContent(
            new Layout( array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblCommodityAllByBasket, null,
                                    array(
                                        'Name'        => 'Name',
                                        'Description' => 'Beschreibung',
                                        'Type'        => 'Leistungsart',
                                        'ItemCount'   => 'Artikelanzahl',
                                        'SumPriceItem' => 'Gesamtpreis',
                                        'Option'      => 'Option'
                                    )
                                )
                            )
                        )
                    ) ),
                ), new LayoutTitle( 'zugewiesene Leistungen' ) ),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblCommodityAll, null,
                                    array(
                                        'Name'        => 'Name',
                                        'Description' => 'Beschreibung',
                                        'Type'        => 'Leistungsart',
                                        'ItemCount'   => 'Artikelanzahl',
                                        'SumPriceItem' => 'Gesamtpreis',
                                        'Option'      => 'Option'
                                    )
                                )
                            )
                        )
                    ) ),
                ), new LayoutTitle( 'mögliche Leistungen' ) )
            ) )
        );

        return $View;
    }

    /**
     * @param $Id
     * @param $CommodityId
     *
     * @return Stage
     */
    public static function frontendBasketCommodityAdd( $Id, $CommodityId )
    {

        $View = new Stage();
        $View->setTitle( 'Leistung' );
        $View->setDescription( 'Hinzufügen' );

        $tblBasket = Billing::serviceBasket()->entityBasketById( $Id );
        $tblCommodity = Billing::serviceCommodity()->entityCommodityById( $CommodityId );
        $View->setContent( Billing::serviceBasket()->executeAddBasketCommodity( $tblBasket, $tblCommodity ) );

        return $View;
    }

    /**
     * @param $Id
     * @param $CommodityId
     *
     * @return Stage
     */
    public static function frontendBasketCommodityRemove( $Id, $CommodityId )
    {

        $View = new Stage();
        $View->setTitle( 'Leistung' );
        $View->setDescription( 'Entfernen' );

        $tblBasket = Billing::serviceBasket()->entityBasketById( $Id );
        $tblCommodity = Billing::serviceCommodity()->entityCommodityById( $CommodityId );
        $View->setContent( Billing::serviceBasket()->executeRemoveBasketCommodity( $tblBasket, $tblCommodity ) );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketItemStatus( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Warenkorb Artikel' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt die Artikel im Warenkorb' );
        $View->addButton( new Primary( 'Zurück', '/Sphere/Billing/Basket/Commodity/Select',
            new ChevronLeftIcon(), array(
                'Id' => $Id
            ) ) );
        $View->addButton( new Primary( 'Weiter', '/Sphere/Billing/Basket/Person/Select',
            new ChevronRightIcon(), array(
                'Id' => $Id
            ) ) );

        $tblBasket = Billing::serviceBasket()->entityBasketById( $Id );
        $tblBasketItemAll = Billing::serviceBasket()->entityBasketItemAllByBasket( $tblBasket );

        if (!empty( $tblBasketItemAll )) {
            array_walk( $tblBasketItemAll, function ( TblBasketItem &$tblBasketItem ) {

                $tblCommodity = $tblBasketItem->getServiceBillingCommodityItem()->getTblCommodity();
                $tblItem = $tblBasketItem->getServiceBillingCommodityItem()->getTblItem();
                $tblBasketItem->CommodityName = $tblCommodity->getName();
                $tblBasketItem->ItemName = $tblItem->getName();
                $tblBasketItem->Option =
                    ( new Primary( 'Bearbeiten', '/Sphere/Billing/Basket/Item/Edit',
                        new EditIcon(), array(
                            'Id' => $tblBasketItem->getId()
                        ) ) )->__toString().
                    ( new \KREDA\Sphere\Client\Frontend\Button\Link\Danger( 'Entfernen',
                        '/Sphere/Billing/Basket/Item/Remove',
                        new MinusIcon(), array(
                            'Id' => $tblBasketItem->getId()
                        ) ) )->__toString();
            } );
        }

        $View->setContent(
            new TableData( $tblBasketItemAll, null,
                array(
                    'CommodityName' => 'Leistung',
                    'ItemName'      => 'Artikel',
                    'Price'         => 'Preis',
                    'Quantity'      => 'Menge',
                    'Option'        => 'Option'
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
    public static function frontendBasketItemRemove( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Artikel' );
        $View->setDescription( 'Entfernen' );

        $tblBasketItem = Billing::serviceBasket()->entityBasketItemById( $Id );
        $View->setContent( Billing::serviceBasket()->executeRemoveBasketItem( $tblBasketItem ) );

        return $View;
    }

    /**
     * @param $Id
     * @param $BasketItem
     *
     * @return Stage
     */
    public static function frontendBasketItemEdit( $Id, $BasketItem )
    {

        $View = new Stage();
        $View->setTitle( 'Artikel' );
        $View->setDescription( 'Bearbeiten' );

        if (empty( $Id )) {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblBasketItem = Billing::serviceBasket()->entityBasketItemById( $Id );
            if (empty( $tblBasketItem )) {
                $View->setContent( new Warning( 'Der Artikel konnte nicht abgerufen werden' ) );
            } else {

                $Global = self::extensionSuperGlobal();
                $Global->POST['BasketItem']['Price'] = str_replace( '.', ',', $tblBasketItem->getPrice() );
                $Global->POST['BasketItem']['Quantity'] = str_replace( '.', ',', $tblBasketItem->getQuantity() );
                $Global->savePost();

                $View->setContent(
                    new Success($tblBasketItem->getServiceBillingCommodityItem()->getTblItem()->getName())
                    .Billing::serviceBasket()->executeEditBasketItem(
                        new Form( array(
                            new FormGroup( array(
                                new FormRow( array(
                                    new FormColumn(
                                        new TextField( 'BasketItem[Price]', 'Preis in €', 'Preis', new MoneyEuroIcon()
                                        ), 6 ),
                                    new FormColumn(
                                        new TextField( 'BasketItem[Quantity]', 'Menge', 'Menge', new QuantityIcon()
                                        ), 6 )
                                ) )
                            ) )
                        ), new SubmitPrimary( 'Änderungen speichern' )
                        ), $tblBasketItem, $BasketItem
                    )
                );
            }
        }

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketPersonSelect(
        $Id
    ) {

        $View = new Stage();
        $View->setTitle( 'Schüler' );
        $View->setDescription( 'Auswählen' );
        $View->setMessage( 'Bitte wählen Sie Schüler zur Fakturierung aus' );
        $View->addButton( new Primary( 'Zurück', '/Sphere/Billing/Basket/Item', new ChevronLeftIcon(),
            array( 'Id' => $Id ) ) );
        $View->addButton( new Primary( 'Weiter', '/Sphere/Billing/Basket/Summary', new ChevronRightIcon(),
            array( 'Id' => $Id ) ) );

        $tblBasket = Billing::serviceBasket()->entityBasketById( $Id );
        $tblBasketPersonList = Billing::serviceBasket()->entityBasketPersonAllByBasket( $tblBasket );
        $tblPersonByBasketList = Billing::serviceBasket()->entityPersonAllByBasket( $tblBasket );
        $tblStudentAll = Management::servicePerson()->entityPersonAllByType( Management::servicePerson()->entityPersonTypeByName( 'Schüler' ) );

        if (!empty( $tblPersonByBasketList )) {
            $tblStudentAll = array_udiff( $tblStudentAll, $tblPersonByBasketList,
                function ( TblPerson $ObjectA, TblPerson $ObjectB ) {

                    return $ObjectA->getId() - $ObjectB->getId();
                }
            );
        }

        if (!empty( $tblBasketPersonList )) {
            array_walk( $tblBasketPersonList, function ( TblBasketPerson &$tblBasketPerson ) {

                $tblPerson = $tblBasketPerson->getServiceManagementPerson();
                $tblBasketPerson->FirstName = $tblPerson->getFirstName();
                $tblBasketPerson->LastName = $tblPerson->getLastName();
                $tblBasketPerson->Option =
                    ( new Danger( 'Entfernen', '/Sphere/Billing/Basket/Person/Remove',
                        new MinusIcon(), array(
                            'Id' => $tblBasketPerson->getId()
                        ) ) )->__toString();
            } );
        }

        if (!empty( $tblStudentAll )) {
            array_walk( $tblStudentAll, function ( TblPerson &$tblPerson, $Index, TblBasket $tblBasket ) {

                $tblPerson->Option =
                    ( new Primary( 'Hinzufügen', '/Sphere/Billing/Basket/Person/Add',
                        new PlusIcon(), array(
                            'Id' => $tblBasket->getId(),
                            'PersonId' => $tblPerson->getId()
                        ) ) )->__toString();
            }, $tblBasket );
        }

        $View->setContent(
            new Layout( array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblBasketPersonList, null,
                                    array(
                                        'FirstName' => 'Vorname',
                                        'LastName'  => 'Nachname',
                                        'Option'    => 'Option '
                                    )
                                )
                            )
                        )
                    ) ),
                ), new LayoutTitle( 'zugewiesene Studenten' ) ),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblStudentAll, null,
                                    array(
                                        'FirstName' => 'Vorname',
                                        'LastName'  => 'Nachname',
                                        'Option'    => 'Option'
                                    )
                                )
                            )
                        )
                    ) ),
                ), new LayoutTitle( 'mögliche Studenten' ) )
            ) )
        );

        return $View;
    }

    /**
     * @param $Id
     * @param $PersonId
     *
     * @return Stage
     */
    public static function frontendBasketPersonAdd( $Id, $PersonId )
    {

        $View = new Stage();
        $View->setTitle( 'Student' );
        $View->setDescription( 'Hinzufügen' );

        $tblBasket = Billing::serviceBasket()->entityBasketById( $Id );
        $tblPerson = Management::servicePerson()->entityPersonById( $PersonId );
        $View->setContent( Billing::serviceBasket()->executeAddBasketPerson( $tblBasket, $tblPerson ) );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketPersonRemove( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Student' );
        $View->setDescription( 'Entfernen' );

        $tblBasketPerson = Billing::serviceBasket()->entityBasketPersonById( $Id );
        $View->setContent( Billing::serviceBasket()->executeRemoveBasketPerson( $tblBasketPerson ) );

        return $View;
    }


    /**
     * @param $Id
     * @param $Basket
     *
     * @return Stage
     */
    public static function frontendBasketSummary( $Id, $Basket = null )
    {

        $View = new Stage();
        $View->setTitle( 'Warenkorb' );
        $View->setDescription( 'Zusammenfassung' );
        $View->setMessage( 'Schließen Sie den Warenkorb zur Fakturierung ab' );
        $View->addButton( new Primary( 'Zurück', '/Sphere/Billing/Basket/Person/Select',
            new ChevronLeftIcon(), array(
                'Id' => $Id
            ) ) );

        $tblBasket = Billing::serviceBasket()->entityBasketById( $Id );
        $tblBasketItemAll = Billing::serviceBasket()->entityBasketItemAllByBasket( $tblBasket );

        if (!empty( $tblBasketItemAll )) {
            array_walk( $tblBasketItemAll, function ( TblBasketItem &$tblBasketItem ) {

                $tblCommodity = $tblBasketItem->getServiceBillingCommodityItem()->getTblCommodity();
                $tblItem = $tblBasketItem->getServiceBillingCommodityItem()->getTblItem();
                $tblBasketItem->CommodityName = $tblCommodity->getName();
                $tblBasketItem->ItemName = $tblItem->getName();
            } );
        }

        $tblBasket = Billing::serviceBasket()->entityBasketById( $Id );
        $tblPersonByBasketList = Billing::serviceBasket()->entityPersonAllByBasket( $tblBasket );

        $View->setContent(
            new Layout( array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn(
                            array(
                                new TableData( $tblBasketItemAll, null,
                                    array(
                                        'CommodityName' => 'Leistung',
                                        'ItemName'      => 'Artikel',
                                        'Price'         => 'Preis',
                                        'Quantity'      => 'Menge',
                                    )
                                )
                            )
                        )
                    ) )
                ), new LayoutTitle( 'Artikel' ) ),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn(
                            array(
                                new TableData( $tblPersonByBasketList, null,
                                    array(
                                        'FirstName' => 'Vorname',
                                        'LastName'  => 'Nachname'
                                    )
                                )
                            )
                        )
                    ) )
                ), new LayoutTitle( 'Studenten' ) ),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn(
                            Billing::serviceBasket()->executeCheckBasket(
                                new Form(
                                    new FormGroup( array(
                                        new FormRow( array(
                                            new FormColumn(
                                                new DatePicker( 'Basket[Date]', 'Fälligkeitsdatum', 'Fälligkeitsdatum',
                                                    new TimeIcon() )
                                                , 3 )
                                        ) ),
                                    ), new FormTitle( 'Fälligkeit' ) )
                                    , new SubmitPrimary( 'Warenkorb fakturieren (prüfen)' )
                                ), $tblBasket, $Basket
                            )
                        )
                    ) )
                ) )
            ) )
        );

        return $View;
    }

    /**
     * @param $Id
     * @param $Date
     * @param $Data
     * @param $SelectList
     * @param $TempInvoiceList
     *
     * @return Stage
     */
    public static function frontendBasketDebtorSelect( $Id, $Date, $Data, $SelectList, $TempInvoiceList )
    {
        $View = new Stage();
        $View->setTitle( 'Warenkorb' );
        $View->setDescription( 'Debitoren zuordnen' );
        $View->setMessage( 'Weisen Sie die entsprechenden Debitoren zu' );

        $TableData = array();
        foreach ($SelectList as $Key => $Select)
        {
            $Debtors = array();
            foreach ($Select['Debtors'] as $Debtor)
            {
                $tblDebtor = Billing::serviceBanking()->entityDebtorById($Debtor);
                $tblDebtor->setDebtorNumber($tblDebtor->getDebtorNumber() . " - " . $tblDebtor->getServiceManagement_Person()->getFullName());
                array_push($Debtors, $tblDebtor);
            }

            $TableData[]=array(
              'Person' => Management::servicePerson()->entityPersonById($Select['tblPerson'])->getFullName(),
              'Commodity' => Billing::serviceCommodity()->entityCommodityById($Select['tblCommodity'])->getName(),
              'Debtors' =>  (new SelectBox( 'Data['. $Key .']', null, array( 'DebtorNumber' => $Debtors )))->__toString()
            );
        }

        $View->setContent(
            Billing::serviceBasket()->executeCheckDebtors(
                new Form(
                    new FormGroup( array(
                        new FormRow( array(
                            new FormColumn(
                                new TableData( $TableData, null,
                                    array(
                                        'Person' => 'Student',
                                        'Commodity'  => 'Leistung',
                                        'Debtors' => 'DebitorenNummer'
                                    )
                                )
                            )
                        ) )
                    ), new FormTitle( 'Debitoren' ) )
                    , new SubmitPrimary( 'Debitoren zuordnen (prüfen)' )
                ), $Id, $Date, $Data, $SelectList, $TempInvoiceList
            )
        );

        return $View;
    }
}
