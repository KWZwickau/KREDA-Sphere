<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Company
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Company extends AbstractFrontend
{
    /**
     * @return Stage
     */
    public static function stageCompanyList()
    {
        $View = new Stage();
        return $View;
    }
}
