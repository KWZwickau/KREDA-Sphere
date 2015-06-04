<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodityItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItemAccount;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Course\Entity\TblCourse;
use KREDA\Sphere\Application\Management\Service\Student\Entity\TblChildRank;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ListIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MinusIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MoneyEuroIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MoneyIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PlusIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuantityIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
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
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Frontend
 */
class Commodity extends AbstractFrontend
{
    /**
     * @return Stage
     */
    public static function frontendStatus()
    {
        $View = new Stage();
        $View->setTitle( 'Leistungen' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt die verfügbaren Leistungen an' );
        $View->addButton(
            new Primary( 'Leistung anlegen', '/Sphere/Billing/Commodity/Create', new PlusIcon() )
        );

        $tblCommodityAll = Billing::serviceCommodity()->entityCommodityAll();

        if (!empty($tblCommodityAll))
        {
            array_walk($tblCommodityAll, function (tblCommodity $tblCommodity)
            {
              $tblCommodity->Type = $tblCommodity->getTblCommodityType()->getName();
              $tblCommodity->ItemCount = Billing::serviceCommodity()->countItemAllByCommodity( $tblCommodity );
              $tblCommodity->SumPriceItem = Billing::serviceCommodity()->sumPriceItemAllByCommodity( $tblCommodity);
              $tblCommodity->Option =
                  (new Primary( 'Bearbeiten', '/Sphere/Billing/Commodity/Edit',
                        new EditIcon(), array(
                            'Id' => $tblCommodity->getId()
                    ) ) )->__toString().
                  (new Danger( 'Löschen', '/Sphere/Billing/Commodity/Delete',
                      new RemoveIcon(), array(
                          'Id' => $tblCommodity->getId()
                      ) ) )->__toString().
                  (new Primary( 'Artikel auswählen', '/Sphere/Billing/Commodity/Item/Select',
                        new ListIcon(), array(
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
     * @param $Commodity
     *
     * @return Stage
     */
    public static function frontendCreate( $Commodity )
    {
        $View = new Stage();
        $View->setTitle( 'Leistungen' );
        $View->setDescription( 'Hinzufügen' );

        $View->setContent(Billing::serviceCommodity()->executeCreateCommodity(
            new Form( array(
                new FormGroup( array(
                    new FormRow( array(
                        new FormColumn(
                            new TextField( 'Commodity[Name]', 'Name', 'Name', new ConversationIcon()
                            ), 6 ),
                        new FormColumn(
                            new SelectBox( 'Commodity[Type]', 'Leistungsart', array(
                                'Name' => Billing::serviceCommodity()->entityCommodityTypeAll()
                            ) )
                            , 6 )
                    ) ),
                    new FormRow( array(
                        new FormColumn(
                            new TextField( 'Commodity[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                            ), 12 )
                    ) )
        ))), new SubmitPrimary( 'Hinzufügen' )), $Commodity));

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendDelete( $Id)
    {
        $View = new Stage();
        $View->setTitle( 'Leistung' );
        $View->setDescription( 'Entfernen' );

        $tblCommodity = Billing::serviceCommodity()->entityCommodityById($Id);
        $View->setContent(Billing::serviceCommodity()->executeRemoveCommodity( $tblCommodity ));

        return $View;
    }


    /**
     * @param $Id
     * @param $Commodity
     *
     * @return Stage
     */
    public static function frontendEdit ( $Id, $Commodity )
    {
        $View = new Stage();
        $View->setTitle( 'Leistungen' );
        $View->setDescription( 'Bearbeiten' );

        if (empty( $Id )) {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblCommodity = Billing::serviceCommodity()->entityCommodityById($Id);
            if (empty( $tblCommodity )) {
                $View->setContent( new Warning( 'Die Leistung konnte nicht abgerufen werden' ) );
            } else {

                $Global = self::extensionSuperGlobal();
                $Global->POST['Commodity']['Name'] = $tblCommodity->getName();
                $Global->POST['Commodity']['Description'] = $tblCommodity->getDescription();
                $Global->POST['Commodity']['Type'] = $tblCommodity->getTblCommodityType()->getId();
                $Global->savePost();

                $View->setContent(Billing::serviceCommodity()->executeEditCommodity(
                    new Form( array(
                            new FormGroup( array(
                                new FormRow( array(
                                    new FormColumn(
                                        new TextField( 'Commodity[Name]', 'Name', 'Name', new ConversationIcon()
                                        ), 6 ),
                                    new FormColumn(
                                        new SelectBox( 'Commodity[Type]', 'Leistungsart', array(
                                            'Name' => Billing::serviceCommodity()->entityCommodityTypeAll()
                                        ) )
                                        , 6 )
                                ) ),
                                new FormRow( array(
                                    new FormColumn(
                                        new TextField( 'Commodity[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                                        ), 12 )
                                ) )
                        ))), new SubmitPrimary( 'Änderungen speichern' )
                    ), $tblCommodity, $Commodity));
            }
        }

        return $View;
    }

    /**
     * @return Stage
     */
    public static function frontendItemStatus()
    {
        $View = new Stage();
        $View->setTitle( 'Artikel' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt die verfügbaren Artikel an' );
        $View->addButton(
            new Primary( 'Artikel anlegen', '/Sphere/Billing/Commodity/Item/Create', new PlusIcon() )
        );

        $tblItemAll = Billing::serviceCommodity()->entityItemAll();

        if (!empty($tblItemAll))
        {
            array_walk($tblItemAll, function (TblItem $tblItem)
            {
                if (Billing::serviceCommodity()->entityCommodityItemAllByItem($tblItem))
                {
                    $tblItem->Option =
                        (new Primary( 'Bearbeiten', '/Sphere/Billing/Commodity/Item/Edit',
                            new EditIcon(), array(
                                'Id' => $tblItem->getId()
                            ) ) )->__toString().
                        (new Primary( 'FIBU-Konten auswählen', '/Sphere/Billing/Commodity/Item/Account/Select',
                            new ListIcon(), array(
                                'Id' => $tblItem->getId()
                            ) ))->__toString();
                }
                else
                {
                    $tblItem->Option =
                        (new Primary( 'Bearbeiten', '/Sphere/Billing/Commodity/Item/Edit',
                            new EditIcon(), array(
                                'Id' => $tblItem->getId()
                            ) ) )->__toString().
                        (new \KREDA\Sphere\Client\Frontend\Button\Link\Danger( 'Löschen', '/Sphere/Billing/Commodity/Item/Delete',
                            new RemoveIcon(), array(
                                'Id' => $tblItem->getId()
                            ) ) )->__toString().
                        (new Primary( 'FIBU-Konten auswählen', '/Sphere/Billing/Commodity/Item/Account/Select',
                            new ListIcon(), array(
                                'Id' => $tblItem->getId()
                            ) ))->__toString();
                }
            });
        }

        $View->setContent(
            new TableData( $tblItemAll, null,
                array(
                    'Name'  => 'Name',
                    'Description' => 'Beschreibung',
                    'Price' => 'Preis',
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
    public static function frontendItemRemove ( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Artikel entfernen' );
        $tblCommodityItem = Billing::serviceCommodity()->entityCommodityItemById( $Id );
        if (!empty($tblCommodityItem))
        {
            $View ->setContent( Billing::serviceCommodity()->executeRemoveCommodityItem( $tblCommodityItem ));
        }

        return $View;
    }

    /**
     * @param $tblCommodityId
     * @param $tblItemId
     * @param $Item
     * @return Stage
     */
    public static function frontendItemAdd ( $tblCommodityId, $tblItemId, $Item )
    {
        $View = new Stage();
        $View->setTitle( 'Artikel hinzufügen' );
        $tblCommodity = Billing::serviceCommodity()->entityCommodityById($tblCommodityId);
        $tblItem = Billing::serviceCommodity()->entityItemById($tblItemId);

        if (!empty($tblCommodityId) && !empty($tblItemId))
        {
            $View ->setContent( Billing::serviceCommodity()->executeAddCommodityItem( $tblCommodity, $tblItem, $Item ));
        }

        return $View;
    }


    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendItemSelect ( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Leistung' );
        $View->setDescription( 'Artikel auswählen' );

        if (empty( $Id ))
        {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        }
        else
        {
            $tblCommodity = Billing::serviceCommodity()->entityCommodityById($Id);
            if (empty( $tblCommodity ))
            {
                $View->setContent( new Warning( 'Die Leistung konnte nicht abgerufen werden' ) );
            }
            else
            {
                $tblCommodityItem = Billing::serviceCommodity()->entityCommodityItemAllByCommodity($tblCommodity);
                $tblItemAllByCommodity = Billing::serviceCommodity()->entityItemAllByCommodity($tblCommodity);
                $tblItemAll = Billing::serviceCommodity()->entityItemAll();

                if (!empty($tblItemAllByCommodity))
                {
                    $tblItemAll = array_udiff( $tblItemAll, $tblItemAllByCommodity,
                        function ( TblItem $ObjectA, TblItem $ObjectB ) {

                            return $ObjectA->getId() - $ObjectB->getId();
                        }
                    );
                }

                if (!empty($tblCommodityItem))
                {
                    array_walk($tblCommodityItem, function (TblCommodityItem $tblCommodityItem)
                    {
                        $tblItem = $tblCommodityItem->getTblItem();

                        $tblCommodityItem->Name = $tblItem->getName();
                        $tblCommodityItem->Description = $tblItem->getDescription();
                        $tblCommodityItem->Price = $tblItem->getPrice();
                        $tblCommodityItem->CostUnit = $tblItem->getCostUnit();
                        $tblCommodityItem->Option =
                            (new Danger( 'Entfernen', '/Sphere/Billing/Commodity/Item/Remove',
                                new MinusIcon(), array(
                                    'Id' => $tblCommodityItem->getId()
                                ) ))->__toString();
                    });
                }

                if (!empty($tblItemAll))
                {
                    foreach ($tblItemAll as $tblItem)
                    {
                        $tblItem->Option=
                            ( new Form(
                                new FormGroup(
                                    new FormRow( array(
                                        new FormColumn(
                                            new TextField( 'Item[Quantity]', 'Menge', 'Menge', new QuantityIcon()
                                            )
                                            , 7 ),
                                        new FormColumn(
                                            new SubmitPrimary( 'Hinzufügen', new PlusIcon() )
                                            , 5 )
                                    ) )
                                ), null,
                                '/Sphere/Billing/Commodity/Item/Add', array(
                                    'tblCommodityId'       => $tblCommodity->getId(),
                                    'tblItemId' => $tblItem->getId()
                                )
                            ) )->__toString();
                    }
                }

                $View->setTitle( 'Leisung: '. $tblCommodity->getName());
                $View->setContent(
                  new Layout(array(
                    new LayoutGroup( array(
                        new LayoutRow( array(
                            new LayoutColumn( array(
                                    new TableData( $tblCommodityItem, null,
                                        array(
                                            'Name'  => 'Name',
                                            'Description' => 'Beschreibung',
                                            'CostUnit' => 'Kostenstelle',
                                            'Price' => 'Preis',
                                            'Quantity' => 'Menge',
                                            'Option'  => 'Option'
                                        )
                                    )
                                )
                            )
                        ) ),
                    ), new LayoutTitle( 'vorhandene Artikel' ) ),
                    new LayoutGroup( array(
                        new LayoutRow( array(
                            new LayoutColumn( array(
                                    new TableData( $tblItemAll, null,
                                        array(
                                            'Name'  => 'Name',
                                            'Description' => 'Beschreibung',
                                            'CostUnit' => 'Kostenstelle',
                                            'Price' => 'Preis',
                                            'Option'  => 'Option'
                                        )
                                )
                              )
                            )
                        ) ),
                    ), new LayoutTitle( 'mögliche Artikel' ) ) ))
                );
            }
        }

        return $View;
    }

    /**
     * @param $Item
     *
     * @return Stage
     */
    public static function frontendItemCreate( $Item )
    {
        $View = new Stage();
        $View->setTitle( 'Artikel' );
        $View->setDescription( 'Hinzufügen' );

        $tblCourseAll = Management::serviceCourse()->entityCourseAll();
        array_unshift( $tblCourseAll, new TblCourse( '' ) );
        $tblChildRankAll = Management::serviceStudent()->entityChildRankAll();
        array_unshift( $tblChildRankAll, new TblChildRank( '' ) );

        $View->setContent(Billing::serviceCommodity()->executeCreateItem(
            new Form( array(
                new FormGroup( array(
                    new FormRow( array(
                        new FormColumn(
                            new TextField( 'Item[Name]', 'Name', 'Name', new ConversationIcon()
                            ), 6 ),
                        new FormColumn(
                            new TextField( 'Item[Price]', 'Preis in €', 'Preis', new MoneyEuroIcon()
                            ), 6 )
                    ) ),
                    new FormRow( array(
                        new FormColumn(
                            new TextField( 'Item[CostUnit]', 'Kostenstelle', 'Kostenstelle', new MoneyIcon()
                            ), 6)
                    ) ),
                    new FormRow( array(
                        new FormColumn(
                            new TextField( 'Item[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                            ), 12)
                    ) ),
                    new FormRow( array(
                        new FormColumn(
                            new SelectBox( 'Item[Course]', 'Bedingung Bildungsgang',
                                array('Name' => $tblCourseAll
                                ) )
                            , 6 ),
                        new FormColumn(
                            new SelectBox( 'Item[ChildRank]', 'Bedingung Kind-Reihenfolge',
                                array('Description' => $tblChildRankAll
                                ) )
                            , 6 )
                    ) )
                ))), new SubmitPrimary( 'Hinzufügen' )), $Item));

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendItemDelete( $Id)
    {
        $View = new Stage();
        $View->setTitle( 'Artikel' );
        $View->setDescription( 'Entfernen' );

        $tblItem = Billing::serviceCommodity()->entityItemById($Id);
        $View->setContent(Billing::serviceCommodity()->executeDeleteItem($tblItem));

        return $View;
    }


    /**
     * @param $Id
     * @param $Item
     *
     * @return Stage
     */
    public static function frontendItemEdit ( $Id, $Item )
    {
        $View = new Stage();
        $View->setTitle( 'Artikel' );
        $View->setDescription( 'Bearbeiten' );

        $tblCourseAll = Management::serviceCourse()->entityCourseAll();
        array_unshift( $tblCourseAll, new TblCourse( '' ) );
        $tblChildRankAll = Management::serviceStudent()->entityChildRankAll();
        array_unshift( $tblChildRankAll, new TblChildRank( '' ) );

        if (empty( $Id )) {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblItem = Billing::serviceCommodity()->entityItemById($Id);
            if (empty( $tblItem )) {
                $View->setContent( new Warning( 'Der Artikel konnte nicht abgerufen werden' ) );
            } else {

                $Global = self::extensionSuperGlobal();
                $Global->POST['Item']['Name'] = $tblItem->getName();
                $Global->POST['Item']['Description'] = $tblItem->getDescription();
                $Global->POST['Item']['Price'] = str_replace('.',',', $tblItem->getPrice());
                $Global->POST['Item']['CostUnit'] = $tblItem->getCostUnit();
                if ($tblItem->getServiceManagementCourse())
                {
                    $Global->POST['Item']['Course'] = $tblItem->getServiceManagementCourse()->getId();
                }
                if ($tblItem->getServiceManagementStudentChildRank())
                {
                    $Global->POST['Item']['ChildRank'] = $tblItem->getServiceManagementStudentChildRank()->getId();
                }
                $Global->savePost();

                $View->setContent(Billing::serviceCommodity()->executeEditItem(
                    new Form( array(
                            new FormGroup( array(
                                new FormRow( array(
                                    new FormColumn(
                                        new TextField( 'Item[Name]', 'Name', 'Name', new ConversationIcon()
                                        ), 6 ),
                                    new FormColumn(
                                        new TextField( 'Item[Price]', 'Preis in €', 'Preis', new MoneyEuroIcon()
                                        ), 6 )
                                ) ),
                                new FormRow( array(
                                    new FormColumn(
                                        new TextField( 'Item[CostUnit]', 'Kostenstelle', 'Kostenstelle', new MoneyIcon()
                                        ), 6)
                                ) ),
                                new FormRow( array(
                                    new FormColumn(
                                        new TextField( 'Item[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                                        ), 12)
                                ) ),
                                new FormRow( array(
                                    new FormColumn(
                                        new SelectBox( 'Item[Course]', 'Bedingung Bildungsgang',
                                            array('Name' => $tblCourseAll
                                            ) )
                                        , 6 ),
                                    new FormColumn(
                                        new SelectBox( 'Item[ChildRank]', 'Bedingung Kind-Reihenfolge',
                                            array('Description' => $tblChildRankAll
                                            ) )
                                        , 6 )
                                ) )
                            ))), new SubmitPrimary( 'Änderungen speichern' )
                    ), $tblItem, $Item));
            }
        }

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendItemAccountSelect ( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Artikel' );
        $View->setDescription( 'FIBU-Konten auswählen' );

        if (empty( $Id ))
        {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        }
        else
        {
            $tblItem = Billing::serviceCommodity()->entityItemById($Id);
            if (empty( $tblItem ))
            {
                $View->setContent( new Warning( 'Der Artikel konnte nicht abgerufen werden' ) );
            }
            else
            {
                $tblItemAccountByItem = Billing::serviceCommodity()->entityItemAccountAllByItem($tblItem);
                $tblAccountByItem = Billing::serviceCommodity()->entityAccountAllByItem($tblItem);
                $tblAccountAllByActiveState = Billing::serviceAccount()->entityAccountAllByActiveState();

                if (!empty( $tblAccountAllByActiveState ) ) {
                    $tblAccountAllByActiveState = array_udiff( $tblAccountAllByActiveState, $tblAccountByItem,
                        function ( TblAccount $ObjectA, TblAccount $ObjectB ) {
                            return $ObjectA->getId() - $ObjectB->getId();
                        }
                    );
                }

                if (!empty($tblItemAccountByItem))
                {
                    array_walk($tblItemAccountByItem, function (TblItemAccount $tblItemAccountByItem)
                    {
                        $tblItemAccountByItem->Number = $tblItemAccountByItem->getServiceBilling_Account()->getNumber();
                        $tblItemAccountByItem->Description = $tblItemAccountByItem->getServiceBilling_Account()->getDescription();
                        $tblItemAccountByItem->Option =
                            new \KREDA\Sphere\Client\Frontend\Button\Link\Danger( 'Entfernen', '/Sphere/Billing/Commodity/Item/Account/Remove',
                                new MinusIcon(), array(
                                    'Id' => $tblItemAccountByItem->getId()
                                ));
                    });
                }

                if(!empty($tblAccountAllByActiveState))
                {
                    array_walk($tblAccountAllByActiveState, function (TblAccount $tblAccountAllByActiveState, $Index, TblItem $tblItem)
                    {
                        $tblAccountAllByActiveState->Option =
                            new Primary( 'Hinzufügen', '/Sphere/Billing/Commodity/Item/Account/Add',
                                new PlusIcon(), array(
                                    'tblAccountId' => $tblAccountAllByActiveState->getId(),
                                    'tblItemId' => $tblItem->getId()
                                ) );
                    }, $tblItem);
                }

                $View->setTitle( 'Artikel: '. $tblItem->getName());
                $View->setContent(
                    new Layout(array(
                        new LayoutGroup( array(
                            new LayoutRow( array(
                                new LayoutColumn( array(
                                        new TableData( $tblItemAccountByItem, null,
                                            array(
                                                'Number' => 'Nummer',
                                                'Description' => 'Beschreibung',
                                                'Option'  => 'Option'
                                            )
                                        )
                                    )
                                )
                            ) ),
                        ), new LayoutTitle( 'zugewiesene FIBU-Konten' ) ),
                        new LayoutGroup( array(
                            new LayoutRow( array(
                                new LayoutColumn( array(
                                        new TableData( $tblAccountAllByActiveState, null,
                                            array(
                                                'Number'  => 'Nummer',
                                                'Description' => 'Beschreibung',
                                                'Option'  => 'Option '
                                            )
                                        )
                                    )
                                )
                            ) ),
                        ), new LayoutTitle( 'mögliche FIBU-Konten' ) ) ))
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
    public static function frontendItemAccountRemove ( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'FIBU-Konto entfernen' );
        $tblItemAccount = Billing::serviceCommodity()->entityItemAccountById( $Id );
        if (!empty($tblItemAccount))
        {
            $View ->setContent( Billing::serviceCommodity()->executeRemoveItemAccount( $tblItemAccount));
        }

        return $View;
    }

    /**
     * @param $tblItemId
     * @param $tblAccountId
     *
     * @return Stage
     */
    public static function frontendItemAccountAdd ( $tblItemId, $tblAccountId )
    {
        $View = new Stage();
        $View->setTitle( 'FIBU-Konto hinzufügen' );
        $tblItem = Billing::serviceCommodity()->entityItemById($tblItemId);
        $tblAccount = Billing::serviceAccount()->entityAccountById( $tblAccountId);

        if (!empty($tblItemId) && !empty($tblAccountId))
        {
            $View ->setContent( Billing::serviceCommodity()->executeAddItemAccount( $tblItem, $tblAccount ));
        }

        return $View;
    }

    /**
     * @param $DebtorCommodity
     *
     * @return Stage
     */
    public static function frontendDebtorCommodityCreate( $DebtorCommodity )
    {
        $View = new Stage();
        $View->setTitle( 'Debitor-Leistung' );
        $View->setDescription( 'Hinzufügen' );

        $View->setContent(Billing::serviceCommodity()->executeCreateDebtorCommodity(
            new Form( array(
                new FormGroup( array(
                    new FormRow( array(
                        new FormColumn(
                            new TextField( 'DebtorCommodity[Name]', 'Name', 'Name', new ConversationIcon()
                            ), 12 )
                    ) ),
                    new FormRow( array(
                        new FormColumn(
                            new TextField( 'DebtorCommodity[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                            ), 12 )
                    ) )
                ))), new SubmitPrimary( 'Hinzufügen' )), $DebtorCommodity));

        return $View;
    }
}
