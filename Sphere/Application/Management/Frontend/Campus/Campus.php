<?php
namespace KREDA\Sphere\Application\Management\Frontend\Campus;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Campus
 *
 * @package KREDA\Sphere\Application\Management\Campus
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
