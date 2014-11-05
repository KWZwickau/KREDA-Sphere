<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

/**
 * Class BriefcaseIcon
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Icon
 */
class BriefcaseIcon extends Icon implements IParameterInterface
{

    function __construct()
    {

        $this->setValue( Icon::ICON_BRIEFCASE );
    }
}
