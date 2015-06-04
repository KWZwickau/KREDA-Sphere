<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Account\Entity\TblAccount;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DisableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OkIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PlusIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Button\Link\Success;
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
    public static function frontendAccountFibu()
    {
        $View = new Stage();
        $View->setTitle( 'FIBU' );
        $View->setDescription( 'Konten' );
        $View->addButton(
            new Primary( 'FIBU Konto Anlegen', '/Sphere/Billing/Account/Create', new PlusIcon() )
        );

        $tblAccountAll = Billing::serviceAccount()->entityAccountAll();

        if (!empty($tblAccountAll))
        {
            array_walk($tblAccountAll, function (TblAccount $tblAccount) {
                $tblAccount->Taxes = $tblAccount->getTblAccountKey()->getValue();
                $tblAccount->Code = $tblAccount->getTblAccountKey()->getCode();
                $tblAccount->Typ = $tblAccount->getTblAccountType()->getName();
                if( $tblAccount->getIsActive()=== true )
                {
                    $tblAccount->Activity = new \KREDA\Sphere\Client\Frontend\Message\Type\Success( 'Aktiviert' );
                }
                else
                {
                    $tblAccount->Activity = new \KREDA\Sphere\Client\Frontend\Message\Type\Danger( 'Deaktiviert' );
                }


                if($tblAccount->getIsActive() === false)
                {
                    $tblAccount->Option =
                        (new Success( 'Aktivieren', '/Sphere/Billing/Account/Activate',
                            new OkIcon(), array(
                                'Id' => $tblAccount->getId()
                            ) ) )->__toString();
                }
                else
                {
                    $tblAccount->Option =
                        (new Danger( 'Deaktivieren', '/Sphere/Billing/Account/Deactivate',
                            new DisableIcon(), array(
                                'Id' => $tblAccount->getId()
                            ) ) )->__toString();
                }


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
                    'Activity' => 'Aktiv',
                    'Option' => 'Aktivität ändern'
                )
            )
        );

        return $View;
    }

    /**
     * @param $Id
     * @return Stage
     */
    public static function frontendAccountFibuActivate( $Id )
    {
        $View = new Stage();
        $View->setTitle('Aktivierung');

        $View->setContent(Billing::serviceAccount()->setFibuActivate( $Id ));



        return $View;

    }

    /**
     * @param $Id
     * @return Stage
     */
    public static function frontendAccountFibuDeactivate( $Id )
    {
        $View = new Stage();
        $View->setTitle('Deaktivierung');

        $View->setContent(Billing::serviceAccount()->setFibuDeactivate( $Id ));



        return $View;

    }

    /**
     * @param $Account
     * @return Stage
     */
    public static function frontendCreateAccount( $Account )
    {
        $View = new Stage();
        $View->setTitle( 'FIBU Konto' );
        $View->setDescription( 'hinzufügen' );

        $tblAccountKey = Billing::serviceAccount()->entityKeyValueAll();
        $tblAccountType = Billing::serviceAccount()->entityTypeValueAll();

        $View->setContent(Billing::serviceAccount()->executeAddAccount(
            new Form( array(
                new FormGroup( array(
                    new FormRow( array(

                        new FormColumn(
                            new TextField( 'Account[Number]', 'Kennziffer', 'Kennziffer', new ConversationIcon()
                            ), 6),
                        new FormColumn(
                            new TextField( 'Account[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
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
    public static function frontendEditAccountFibu ( $Id, $Account )
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
                                        , 2 ),
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




}