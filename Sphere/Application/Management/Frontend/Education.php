<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Education
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Education extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageEducation()
    {

        $View = new Stage();
        $View->setTitle( 'Klassen und Fächer' );

        return $View;
    }

}
