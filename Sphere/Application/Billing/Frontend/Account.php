<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BarCodeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;


/**
 * Class Account
 *
 * @package KREDA\Sphere\Application\Billing\Frontend
 */
class Account extends AbstractFrontend
{
    /**
     * @return Stage
     */
    public static function frontendAccount()
    {
        $View = new Stage();
        $View->setTitle( 'Account' );

        $tblAccountAll = Billing::serviceAccount()->entityAccountAll();

        if (!empty($tblAccountAll))
        {
            array_walk($tblAccountAll, function (TblAccount $tblAccount) {
                $tblAccount->Taxes = $tblAccount->getTblAccountKey()->getValue();
                $tblAccount->Code = $tblAccount->getTblAccountKey()->getCode();
                $tblAccount->Typ = $tblAccount->getTblAccountType()->getName();
                $tblAccount->Option =
                    (new Primary( 'Bearbeiten', '/Sphere/Billing/Account/Edit',
                        new EditIcon(), array(
                            'Id' => $tblAccount->getId()
                        ) ) )->__toString();

            });
        }
        $View->setContent(
            new TableData( $tblAccountAll, null,
                array(
                    'Number' => 'Kennziffer',
                    'Description' => 'Beschreibung',
                    'Taxes' => 'Mehrwertsteuer',
                    'Code' => 'Code',
                    'Typ' => 'Konto',
                    'Option' => 'Bearbeitung'
                )
            )
        );


        return $View;
    }

    /**
     * @param $Account
     * @return Stage
     */
    public static function frontendCreateAccount( $Account )
    {
        $View = new Stage();
        $View->setTitle( 'Account' );
        $View->setDescription( 'hinzufügen' );

        $tblAccountKey = Billing::serviceAccount()->entityKeyValueAll();
        $tblAccountType = Billing::serviceAccount()->entityTypeValueAll();

        $View->setContent(Billing::serviceAccount()->executeAddAccount(
            new Form( array(
                new FormGroup( array(
                    new FormRow( array(

                        new FormColumn(
                            new TextField( 'Account[Number]', 'Nummer', 'Nummer', new ConversationIcon()
                            ), 6),
                        new FormColumn(
                            new TextField( 'Account[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                            ), 6),
                        new FormColumn(
                            new TextField( 'Account[IsActive]', 'Aktiv', 'Aktiv', new ConversationIcon()
                            ), 6),
                        )),
                    new FormRow( array(
                        new FormColumn(
                            new SelectBox( 'Account[Key]', 'Mehrwertsteuer',
                                array('Value' => $tblAccountKey
                            ) )
                            , 6 ),
                        new FormColumn(
                            new SelectBox( 'Account[Type]', 'Typ',
                                array('Name' => $tblAccountType
                            ) )
                            , 6 )
                    ) )
                ))),  new SubmitPrimary( 'Hinzufügen' )), $Account ));

        return $View;
    }

    /**
     * @param $Id
     * @param $Account
     * @return Stage
     */
    public static function frontendEdit ( $Id, $Account )
    {
        $View = new Stage();
        $View->setTitle( 'Account' );
        $View->setDescription( 'Bearbeiten' );

        $tblAccountKey = Billing::serviceAccount()->entityAccountKeyAll();
        $tblAccountType = Billing::serviceAccount()->entityAccountTypeAll();

        if (empty( $Id )) {
            $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
        } else {
            $tblAccount = Billing::serviceAccount()->entityAccountById($Id);
            if (empty( $tblAccount )) {
                $View->setContent( new Warning( 'Die Daten konnten nicht abgerufen werden' ) );
            } else {

                $Global = self::extensionSuperGlobal();
                $Global->POST['Account']['Description'] = $tblAccount->getDescription();
                $Global->POST['Account']['Number'] = $tblAccount->getNumber();
                $Global->POST['Account']['IsActive'] = $tblAccount->getIsActive();
                $Global->POST['Account']['tblAccountKey'] = $tblAccount->getTblAccountKey()->getId();
                $Global->POST['Account']['tblAccountType'] = $tblAccount->getTblAccountType()->getId();
                $Global->savePost();

                $View->setContent(Billing::serviceAccount()->executeEditAccount(
                    new Form( array(
                        new FormGroup( array(
                            new FormRow( array(
                                new FormColumn(
                                    new TextField( 'Account[Number]', 'Kennziffer', 'Kennziffer', new ConversationIcon()
                                    ), 5 ),
                                new FormColumn(
                                    new TextField( 'Account[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                                    ), 5 ),
                                new FormColumn(
                                    new TextField( 'Account[IsActive]', 'Aktiv', 'Aktiv', new ConversationIcon()
                                    ), 2 )
                            ) ),
                                new FormRow( array(
                                    new FormColumn(
                                        new SelectBox( 'Account[tblAccountKey]', 'Schlüssel', array(
                                            'Value' => Billing::serviceAccount()->entityAccountKeyAll()
                                        ) )
                                        , 1 ),
                                    new FormColumn(
                                        new SelectBox( 'Account[tblAccountType]', 'Kontoart', array(
                                            'Name' => Billing::serviceAccount()->entityAccountTypeAll()
                                        ) )
                                        , 2 )
                            ) ),

                        ))), new SubmitPrimary( 'Änderungen speichern' )
                    ), $tblAccount, $Account));
            }
        }

        return $View;
    }

    /**
     * @param $Debitor
     * @return Stage
     */
    public static function frontendAddDebitor( $Debitor )
    {

        $View = new Stage();
        $View->setTitle( 'Debitoren' );
        $View->setDescription( 'Anlegen' );

        $View->setContent(Billing::serviceAccount()->executeAddDebitor(
            new Form( array(
                new FormGroup( array(
                    new FormRow( array(
                        new FormColumn(
                            new TextField( 'Debitor[First]', 'erste Vorlaufzeit', 'erste Vorlaufzeit', new TimeIcon()
                            ), 6 ),
                        new FormColumn(
                            new TextField( 'Debitor[Second]', 'folgende Vorlaufzeit', 'folgende Vorlaufzeit', new TimeIcon()
                            ), 6 ),
                        new FormColumn(
                            new TextField( 'Debitor[Number]', 'Debitoren Nummer', 'Debitoren Nummer', new BarCodeIcon()
                            ), 12 )
                        ))
                ))), new SubmitPrimary( 'Hinzufügen' )), $Debitor ));

        return $View;
    }
}