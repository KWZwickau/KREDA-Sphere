<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Balance
 *
 * @package KREDA\Sphere\Application\Billing\Frontend
 */
class Balance extends AbstractFrontend
{

    public static function frontendBalance()
    {
        $View = new Stage();
        $View->setTitle('Posten');
        $View->setDescription('Offen');

        return $View;
    }
}