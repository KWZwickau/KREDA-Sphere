<?php
namespace KREDA\Sphere\Application\Management\Frontend\Education;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Group
 *
 * @package KREDA\Sphere\Application\Management\Frontend\Education
 */
class Group extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageGroup()
    {

        $View = new Stage();
        $View->setTitle( 'Klassen' );
        return $View;
    }
}
