<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
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

    public static function fontendCreateAccount()
    {
        $View = new Stage();
        $View->setTitle( 'Account' );
        $View->setDescription( 'hinzufügen' );
        return $View;
    }
}