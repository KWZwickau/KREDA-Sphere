<?php
namespace KREDA\Sphere\Application\Assistance\Service;

use KREDA\Sphere\Application\Assistance\Client\Aid\Cause\Danger;
use KREDA\Sphere\Application\Assistance\Client\Aid\Cause\Info;
use KREDA\Sphere\Application\Assistance\Client\Aid\Cause\Warning;
use KREDA\Sphere\Application\Assistance\Client\Aid\Solution\Support;
use KREDA\Sphere\Application\Assistance\Client\Aid\Solution\User;
use KREDA\Sphere\Application\Service;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;

/**
 * Class Account
 *
 * @package KREDA\Sphere\Application\Assistance\Service
 */
class Account extends Service
{

    /**
     * @return Stage
     */
    public function apiPasswordForgotten()
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
            .new User( 'Versuchen Sie bitte erneut Ihre Zugangsdaten korrekt einzugeben' )
            .new Support( 'Bitte wenden Sie sich an den Support damit das Problem schnellstmöglich behoben werden kann' )
        );
        return $View;
    }

}
