<?php
namespace KREDA\Sphere\Application\Billing\Frontend\Summary;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Summary
 *
 * @package KREDA\Sphere\Application\Billing\Frontend\Summary
 */
class Summary extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageSummary()
    {

        $View = new Stage();
        return $View;
    }
}
