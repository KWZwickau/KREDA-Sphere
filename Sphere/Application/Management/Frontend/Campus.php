<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Campus
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Campus extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageCampus()
    {

        $View = new Stage();
        return $View;
    }

}
