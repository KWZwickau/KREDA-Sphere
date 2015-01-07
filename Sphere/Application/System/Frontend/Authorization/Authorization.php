<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Authorization
 *
 * @package KREDA\Sphere\Application\System\Frontend\Authorization
 */
class Authorization extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageAuthorizationRight()
    {

        $View = new Stage();
        $View->setTitle( 'Berechtigung' );
        $View->setDescription( 'Rechte' );
        $View->setMessage( 'Zeigt die aktuell verfügbaren Rechte' );
        $View->setContent(
            new Database\Authorization(
                System::serviceDatabase()->executeDatabaseAuthorization()
            )
        );
        return $View;
    }

}
