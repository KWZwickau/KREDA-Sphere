<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblBasket;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblBasketItem;
use KREDA\Sphere\Application\Billing\Service\Invoice\Entity\TblBasketPerson;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
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
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Invoice
 *
 * @package KREDA\Sphere\Application\Billing\Frontend
 */
class Invoice extends AbstractFrontend
{
    /**
     * @return Stage
     */
    public static function frontendBasketCommoditySelect()
    {
        $View = new Stage();
        $View->setTitle( 'Leistung' );
        $View->setDescription( 'Auswählen' );
        $View->setMessage( 'Bitte wählen Sie eine Leistung zur Fakturierung aus' );

        $tblCommodityAll = Billing::serviceCommodity()->entityCommodityAll();

        if (!empty($tblCommodityAll))
        {
            array_walk($tblCommodityAll, function (TblCommodity $tblCommodity)
            {
                $tblCommodity->Type = $tblCommodity->getTblCommodityType()->getName();
                $tblCommodity->ItemCount = Billing::serviceCommodity()->countItemAllByCommodity( $tblCommodity );
                $tblCommodity->SumPriceItem = Billing::serviceCommodity()->sumPriceItemAllByCommodity( $tblCommodity)." €";
                $tblCommodity->Option =
                    (new Primary( 'Auswählen', '/Sphere/Billing/Invoice/Basket/Create',
                        new EditIcon(), array(
                            'Id' => $tblCommodity->getId()
                        ) ))->__toString();
            });
        }

        $View->setContent(
            new TableData( $tblCommodityAll, null,
                array(
                    'Name'  => 'Name',
                    'Description' => 'Beschreibung',
                    'Type' => 'Leistungsart',
                    'ItemCount' => 'Artikelanzahl',
                    'SumPriceItem' => 'Gesamtpreis',
                    'Option'  => 'Option'
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
    public static function frontendBasketCreate( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Warenkorb' );
        $View->setDescription( 'Erstellen' );

        $tblCommodity = Billing::serviceCommodity()->entityCommodityById($Id);
        $View->setContent(Billing::serviceInvoice()->executeCreateBasket( $tblCommodity ));

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
        $View->addButton(new Primary( 'Weiter', '/Sphere/Billing/Invoice/Basket/Person/Select',
            new EditIcon(), array(
                'Id' => $Id
            ) ));

        $tblBasket = Billing::serviceInvoice()->entityBasketById( $Id );
        $tblBasketItemAll = Billing::serviceInvoice()->entityBasketItemAllByBasket( $tblBasket );

        if (!empty($tblBasketItemAll))
        {
            array_walk($tblBasketItemAll, function (TblBasketItem &$tblBasketItem)
            {
                $tblCommodity = $tblBasketItem->getServiceBillingCommodityItem()->getTblCommodity();
                $tblItem = $tblBasketItem->getServiceBillingCommodityItem()->getTblItem();
                $tblBasketItem->CommodityName = $tblCommodity->getName();
                $tblBasketItem->ItemName = $tblItem->getName();
                $tblBasketItem->Option =
                        (new Primary( 'Bearbeiten', '/Sphere/Billing/Invoice/Basket/Item/Edit',
                            new EditIcon(), array(
                                'Id' => $tblBasketItem->getId()
                            ) ) )->__toString().
                        (new \KREDA\Sphere\Client\Frontend\Button\Link\Danger( 'Entfernen', '/Sphere/Billing/Invoice/Basket/Item/Remove',
                            new RemoveIcon(), array(
                                'Id' => $tblBasketItem->getId()
                            ) ) )->__toString();
            });
        }

        $View->setContent(
            new TableData( $tblBasketItemAll, null,
                array(
                    'CommodityName'  => 'Leistung',
                    'ItemName' => 'Artikel',
                    'Price' => 'Preis',
                    'Quantity' => 'Menge',
                    'Option'  => 'Option'
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
    public static function frontendBasketItemRemove( $Id)
    {
        $View = new Stage();
        $View->setTitle( 'Artikel' );
        $View->setDescription( 'Entfernen' );

        $tblBasketItem = Billing::serviceInvoice()->entityBasketItemById( $Id );
        $View->setContent(Billing::serviceInvoice()->executeRemoveBasketItem( $tblBasketItem ));

        return $View;
    }

    /**
     * @param $Id
     * @param $BasketItem
     *
     * @return Stage
     */
    public static function frontendBasketItemEdit ( $Id, $BasketItem )
    {
        $View = new Stage();
        $View->setTitle( 'Artikel' );
        $View->setDescription( 'Bearbeiten' );

        if (empty( $Id )) {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblBasketItem = Billing::serviceInvoice()->entityBasketItemById($Id);
            if (empty( $tblBasketItem )) {
                $View->setContent( new Warning( 'Der Artikel konnte nicht abgerufen werden' ) );
            } else {

                $Global = self::extensionSuperGlobal();
                $Global->POST['BasketItem']['Price'] = str_replace('.',',', $tblBasketItem->getPrice());
                $Global->POST['BasketItem']['Quantity'] = str_replace('.',',', $tblBasketItem->getQuantity());
                $Global->savePost();

                $View->setContent(Billing::serviceInvoice()->executeEditBasketItem(
                    new Form( array(
                            new FormGroup( array(
                                new FormRow( array(
                                    new FormColumn(
                                        new TextField( 'BasketItem[Price]', 'Preis in €', 'Preis', new ConversationIcon()
                                        ), 6 ),
                                    new FormColumn(
                                        new TextField( 'BasketItem[Quantity]', 'Menge', 'Menge', new ConversationIcon()
                                        ), 6 )
                                ) )
                            ))), new SubmitPrimary( 'Änderungen speichern' )
                    ), $tblBasketItem, $BasketItem));
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
    )
    {
        $View = new Stage();
        $View->setTitle( 'Schüler' );
        $View->setDescription( 'Auswählen' );
        $View->setMessage( 'Bitte wählen Sie Schüler zur Fakturierung aus' );
        $View->addButton( new Primary('Zurück', '/Sphere/Billing/Invoice/Basket/Item',null, array('Id' => $Id) ));
        $View->addButton( new Primary( 'Weiter', '/Sphere/Billing/Invoice/Basket/Summary',null, array('Id' => $Id) ));

        $tblBasket = Billing::serviceInvoice()->entityBasketById( $Id );
        $tblBasketPersonList = Billing::serviceInvoice()->entityBasketPersonAllByBasket( $tblBasket );
        $tblPersonByBasketList = Billing::serviceInvoice()->entityPersonAllByBasket( $tblBasket );
        $tblStudentAll = Management::servicePerson()->entityPersonAllByType( Management::servicePerson()->entityPersonTypeByName('Schüler'));

        if (!empty( $tblPersonByBasketList ))
        {
            $tblStudentAll = array_udiff( $tblStudentAll, $tblPersonByBasketList,
                function ( TblPerson $ObjectA, TblPerson $ObjectB ) {

                    return $ObjectA->getId() - $ObjectB->getId();
                }
            );
        }

        if (!empty($tblBasketPersonList))
        {
            array_walk($tblBasketPersonList, function (TblBasketPerson &$tblBasketPerson)
            {
                $tblPerson = $tblBasketPerson->getServiceManagementPerson();
                $tblBasketPerson->FirstName = $tblPerson->getFirstName();
                $tblBasketPerson->LastName = $tblPerson->getLastName();
                $tblBasketPerson->Option =
                    (new Danger( 'Entfernen', '/Sphere/Billing/Invoice/Basket/Person/Remove',
                        new RemoveIcon(), array(
                            'Id' => $tblBasketPerson->getId()
                        ) ))->__toString();
            });
        }

        if (!empty($tblStudentAll))
        {
            array_walk($tblStudentAll, function (TblPerson &$tblPerson, $Index, TblBasket $tblBasket)
            {
                $tblPerson->Option =
                    (new Primary( 'Auswählen', '/Sphere/Billing/Invoice/Basket/Person/Add',
                        new EditIcon(), array(
                            'Id' => $tblBasket->getId(),
                            'PersonId' => $tblPerson->getId()
                        ) ))->__toString();
            }, $tblBasket);
        }

        $View->setContent(
            new Layout(array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblBasketPersonList, null,
                                    array(
                                        'FirstName'  => 'Vorname',
                                        'LastName' => 'Nachname',
                                        'Option'  => 'Option '
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
                                        'FirstName'  => 'Vorname',
                                        'LastName' => 'Nachname',
                                        'Option'  => 'Option'
                                    )
                                )
                            )
                        )
                    ) ),
                ), new LayoutTitle( 'mögliche Studenten' ) )
            ))
        );

        return $View;
    }

    /**
     * @param $Id
     * @param $PersonId
     *
     * @return Stage
     */
    public static function frontendBasketPersonAdd( $Id, $PersonId)
    {
        $View = new Stage();
        $View->setTitle( 'Student' );
        $View->setDescription( 'Hinzufügen' );

        $tblBasket = Billing::serviceInvoice()->entityBasketById( $Id );
        $tblPerson = Management::servicePerson()->entityPersonById( $PersonId );
        $View->setContent(Billing::serviceInvoice()->executeAddBasketPerson( $tblBasket, $tblPerson ));

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBasketPersonRemove( $Id)
    {
        $View = new Stage();
        $View->setTitle( 'Student' );
        $View->setDescription( 'Entfernen' );

        $tblBasketPerson = Billing::serviceInvoice()->entityBasketPersonById( $Id );
        $View->setContent(Billing::serviceInvoice()->executeRemoveBasketPerson( $tblBasketPerson ));

        return $View;
    }

    /**
     * @param $Id
     * @param $Basket
     *
     * @return Stage
     */
    public static function frontendBasketSummary( $Id, $Basket = null)
    {
        $View = new Stage();
        $View->setTitle( 'Warenkorb' );
        $View->setDescription( 'Zusammenfassung' );
        $View->setMessage( 'Schließen Sie den Warenkorb zur Fakturierung ab' );
        $View->addButton(new Primary( 'Zurück', '/Sphere/Billing/Invoice/Basket/Person/Select',
            new EditIcon(), array(
                'Id' => $Id
            ) ));

        $tblBasket = Billing::serviceInvoice()->entityBasketById( $Id );
        $tblBasketItemAll = Billing::serviceInvoice()->entityBasketItemAllByBasket( $tblBasket );

        if (!empty($tblBasketItemAll))
        {
            array_walk($tblBasketItemAll, function (TblBasketItem &$tblBasketItem)
            {
                $tblCommodity = $tblBasketItem->getServiceBillingCommodityItem()->getTblCommodity();
                $tblItem = $tblBasketItem->getServiceBillingCommodityItem()->getTblItem();
                $tblBasketItem->CommodityName = $tblCommodity->getName();
                $tblBasketItem->ItemName = $tblItem->getName();
            });
        }

        $tblBasket = Billing::serviceInvoice()->entityBasketById( $Id );
        $tblPersonByBasketList = Billing::serviceInvoice()->entityPersonAllByBasket( $tblBasket );

        $View->setContent(Billing::serviceInvoice()->executeCreateInvoiceFromBasketList(
            new Form( array(
                    new FormGroup( array(
                        new FormRow( array(
                             new FormColumn(
                                 array(new TableData( $tblBasketItemAll, null,
                                    array(
                                        'CommodityName'  => 'Leistung',
                                        'ItemName' => 'Artikel',
                                        'Price' => 'Preis',
                                        'Quantity' => 'Menge',
                                    )
                                ) )
                            )
                        ) )
                    ), new FormTitle('Artikel')),
                    new FormGroup( array(
                        new FormRow( array(
                            new FormColumn(
                                array(new TableData( $tblPersonByBasketList, null,
                                    array(
                                        'FirstName'  => 'Vorname',
                                        'LastName' => 'Nachname'
                                    )
                                ) )
                             )
                        ) )
                    ), new FormTitle('Studenten')),
                    new FormGroup( array(
                        new FormRow( array(
                            new FormColumn(
                                new DatePicker( 'Basket[Date]', 'Fälligkeitsdatum', 'Fälligkeitsdatum', new TimeIcon() )
                                , 3 )
                        ) ),
                    ), new FormTitle('Fälligkeit'))
                ), new SubmitPrimary( 'Warenkorb fakturieren (abschließen)' )
            ), $tblBasket, $Basket)
        );

        return $View;
    }
}
