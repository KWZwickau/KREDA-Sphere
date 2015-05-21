<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodityItem;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblItem;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ChildIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Type\Table;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;

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
        $View->setMessage( 'Zeigt die verfügbaren Leistungen' );

        $tblCommodityAll = Billing::serviceCommodity()->entityCommodityAll();

        if (!empty($tblCommodityAll))
        {
            array_walk($tblCommodityAll, function (tblCommodity $tblCommodity)
            {
              $tblCommodity->ItemCount = Billing::serviceCommodity()->countItemAllByCommodity( $tblCommodity );
              $tblCommodity->Option =
                  (new Primary( 'Bearbeiten', '/Sphere/Billing/Commodity/Edit',
                        new EditIcon(), array(
                            'Id' => $tblCommodity->getId()
                    ) ) )->__toString().
                  (new \KREDA\Sphere\Client\Frontend\Button\Link\Danger( 'Löschen', '/Sphere/Billing/Commodity/Delete',
                      new RemoveIcon(), array(
                          'Id' => $tblCommodity->getId()
                      ) ) )->__toString().
                  (new Primary( 'Artikel auswählen', '/Sphere/Billing/Commodity/Item/Select',
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
                    'ItemCount' => 'Artikelanzahl',
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
                            new TextField( 'Commodity[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                            ), 6 )
                ) )

        ))), new SubmitPrimary( 'Hinzufügen' )), $Commodity));

        return $View;
    }

    public static function frontendDelete( $Id)
    {
        $View = new Stage();
        $View->setTitle( 'Leistung' );
        $View->setDescription( 'Entfernen' );

        $tblCommodity = Billing::serviceCommodity()->entityCommodityById($Id);
        $View->setContent(Billing::serviceCommodity()->executeRemoveCommodity($View, $tblCommodity));

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
                $Global->savePost();

                $View->setContent(Billing::serviceCommodity()->executeEditCommodity(
                    new Form( array(
                        new FormGroup( array(
                            new FormRow( array(
                                new FormColumn(
                                    new TextField( 'Commodity[Name]', 'Name', 'Name', new ConversationIcon()
                                    ), 6 ),
                                new FormColumn(
                                    new TextField( 'Commodity[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                                    ), 6 )
                            ) )

                        ))), new SubmitPrimary( 'Änderungen speichern' )
                    ), $tblCommodity, $Commodity));
            }
        }

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
            $View ->setContent( Billing::serviceCommodity()->executeRemoveCommodityItem($View, $tblCommodityItem));
        }

        return $View;
    }

    /**
     * @param $tblCommodityId
     * @param $tblItemId
     * @param $Quantity
     * @return Stage
     */
    public static function frontendItemAdd ( $tblCommodityId, $tblItemId, $Quantity )
    {
        $View = new Stage();
        $View->setTitle( 'Artikel hinzufügen' );
        $tblCommodity = Billing::serviceCommodity()->entityCommodityById($tblCommodityId);
        $tblItem = Billing::serviceCommodity()->entityItemById($tblItemId);

        if (!empty($tblCommodityId) && !empty($tblItemId))
        {
            $View ->setContent( Billing::serviceCommodity()->executeAddCommodityItem($View, $tblCommodity, $tblItem, $Quantity));
        }

        return $View;
    }


    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendItemSelect ( $Id)//, $Quantity = null )
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
                $tblItemAll = Billing::serviceCommodity()->entityItemAll();

                if (!empty($tblCommodityItem))
                {
                    array_walk($tblCommodityItem, function (TblCommodityItem $tblCommodityItem)
                    {
                        $tblItem = $tblCommodityItem->getTblItem();
                        $tblCommodityItem->Name = $tblItem->getName();
                        $tblCommodityItem->Description = $tblItem->getDescription();
                        $tblCommodityItem->Option =
                            (new Primary( 'Bearbeiten', '/Sphere/Billing/Commodity/Item/Edit',
                                new EditIcon(), array(
                                    'Id' => $tblCommodityItem->getId()
                                ) ) )->__toString().
                            (new \KREDA\Sphere\Client\Frontend\Button\Link\Danger( 'Artikel entfernen', '/Sphere/Billing/Commodity/Item/Remove',
                                new RemoveIcon(), array(
                                    'Id' => $tblCommodityItem->getId()
                                ) ))->__toString();
                    });
                }

                if (!empty($tblItemAll))
                {
                    foreach ($tblItemAll as $tblItem)
                    {
                        $tblItem->Option=
                            (new TextField( 'Quantity[Quantity]', 'Menge', 'Menge', new ConversationIcon()
                            ))->__toString().
                            (new Primary( 'Artikel hinzufügen', '/Sphere/Billing/Commodity/Item/Add',
                                new ChildIcon(), array(
                                    'tblCommodityId' => $tblCommodity->getId(),
                                    'tblItemId' => $tblItem->getId(),
                                    'Quantity'=> 1
                                ) ))->__toString();
                    }
                }

                $View->setTitle( 'Leisung: '. $tblCommodity->getName());
                $View->setContent(
                    new TableData( $tblCommodityItem, null,
                        array(
                            'Name'  => 'Name',
                            'Description' => 'Beschreibung',
                            'Quantity' => 'Menge',
                            'Option'  => 'Option'
                        )
                    )
                    . new TableData( $tblItemAll, null,
                        array(
                            'Name'  => 'Name',
                            'Description' => 'Beschreibung',
                            'Option'  => 'Option'
                        )
                    )
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

        $View->setContent(Billing::serviceCommodity()->executeCreateItem(
            new Form( array(
                new FormGroup( array(
                    new FormRow( array(
                        new FormColumn(
                            new TextField( 'Item[Name]', 'Name', 'Name', new ConversationIcon()
                            ), 6 ),
                        new FormColumn(
                            new TextField( 'Item[Price]', 'Preis in €', 'Preis', new ConversationIcon()
                            ), 6 )
                    ) ),
                    new FormRow( array(
                        new FormColumn(
                            new TextField( 'Item[CostUnit]', 'Kostenstelle', 'Kostenstelle', new ConversationIcon()
                            ), 6)

                    ) ),
                    new FormRow( array(
                        new FormColumn(
                            new TextField( 'Item[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                            ), 12)

                    ) )
                ))), new SubmitPrimary( 'Hinzufügen' )), $Item));

        return $View;
    }
}