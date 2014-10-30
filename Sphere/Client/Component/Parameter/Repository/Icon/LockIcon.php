<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

/**
 * Class LockIcon
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Icon
 */
class LockIcon extends Icon implements IParameterInterface
{

    function __construct()
    {

        $this->setValue( Icon::ICON_LOCK );
    }
}
