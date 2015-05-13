<?php
namespace KREDA\Sphere\Application\Assistance\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Frontend\Message\Type\Danger;
use KREDA\Sphere\Client\Frontend\Message\Type\Info;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Message\Type\Warning;
use KREDA\Sphere\Common\AbstractFrontend;

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
            .new Info( 'Vergewissern Sie sich, dass die Feststelltaste nicht aktiviert ist' )
            .new Warning( 'Die Anwendung kann wegen Kapazitätsproblemen im Moment nicht verwendet werden' )
            .new Danger( 'Ihr Zugang wurde gesperrt' )
            .'<h2 class="text-left" ><small > Mögliche Lösungen </small></h2> '
            .new Info( 'Versuchen Sie bitte erneut Ihre Zugangsdaten korrekt einzugeben' )
            .new Success( 'Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann' )
        );
        return $View;
    }

}
