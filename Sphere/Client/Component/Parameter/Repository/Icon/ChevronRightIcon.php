<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;

/**
 * Class ChevronRightIcon
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Icon
 */
class ChevronRightIcon extends AbstractIcon implements IParameterInterface
{

    /**
     *
     */
    public function __construct()
    {

        $this->setValue( AbstractIcon::ICON_CHEVRON_RIGHT );
    }
}
