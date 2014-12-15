<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

/**
 * Class MapMarkerIcon
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Icon
 */
class MapMarkerIcon extends Icon implements IParameterInterface
{

    /**
     *
     */
    function __construct()
    {

        $this->setValue( Icon::ICON_MAP_MARKER );
    }
}
