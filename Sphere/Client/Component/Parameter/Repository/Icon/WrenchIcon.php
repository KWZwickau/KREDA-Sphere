<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

/**
 * Class WrenchIcon
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Icon
 */
class WrenchIcon extends Icon implements IParameterInterface
{

    function __construct()
    {

        $this->setValue( Icon::ICON_WRENCH );
    }
}
