<?php
namespace KREDA\Sphere\Application\Management\Frontend;

use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;

/**
 * Class Group
 *
 * @package KREDA\Sphere\Application\Management\Frontend
 */
class Group extends Group\Group
{

    /**
     * @param null|array $Group
     *
     * @return Stage
     */
    public static function frontendGroup( $Group )
    {

        return parent::stageGroup( $Group );
    }

}
