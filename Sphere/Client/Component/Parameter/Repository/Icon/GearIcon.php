<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

/**
 * Class GearIcon
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Icon
 */
class GearIcon extends Icon implements IParameterInterface
{

    function __construct()
    {

        $this->setValue( Icon::ICON_GEAR );
    }
}
