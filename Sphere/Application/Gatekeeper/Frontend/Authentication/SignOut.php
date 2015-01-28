<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class SignOut
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication
 */
class SignOut extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageSignOut()
    {

        Gatekeeper::serviceAccount()->executeActionSignOut();
        $View = new Stage();
        $View->setTitle( 'Abmelden' );
        $View->setDescription( '' );
        $View->setMessage( 'Bitte warten...' );
        $View->setContent( self::getRedirect( '/Sphere/Gatekeeper/SignIn', 1 ) );
        return $View;
    }
}
