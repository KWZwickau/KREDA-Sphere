<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BarCodeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
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
        return $View;
    }

    /**
     * @param $Account
     * @return Stage
     */
    public static function fontendCreateAccount( $Account )
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