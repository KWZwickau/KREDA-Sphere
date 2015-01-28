<?php
namespace KREDA\Sphere\Application\Assistance\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageInfo;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageSuccess;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageWarning;

/**
 * Class Account
 *
 * @package KREDA\Sphere\Application\Assistance\Frontend\Account
 */
class Account extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageWelcome()
    {

        $View = new Stage();
        $View->setTitle( 'Benutzerkonto' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageForgottenPassword()
    {

        $View = new Stage();
        $View->setTitle( 'Hilfe' );
        $View->setDescription( 'Passwort vergessen' );
        $View->setMessage( '<strong>Problem:</strong> Nach Eingabe der Benutzerdaten wird der Zugang verweigert' );
        $View->setContent(
            '<h2 class="text-left"><small>Mögliche Ursachen</small></h2>'
            .new MessageInfo( 'Vergewissern Sie sich, dass die Feststelltaste nicht aktiviert ist' )
            .new MessageWarning( 'Die Anwendung kann wegen Kapazitätsproblemen im Moment nicht verwendet werden' )
            .new MessageDanger( 'Ihr Zugang wurde gesperrt' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new MessageInfo( 'Versuchen Sie bitte erneut Ihre Zugangsdaten korrekt einzugeben' )
            .new MessageSuccess( 'Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann' )
        );
        return $View;
    }

}
