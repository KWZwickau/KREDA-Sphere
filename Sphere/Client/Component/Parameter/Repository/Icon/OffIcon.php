<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;

/**
 * Class OffIcon
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Icon
 */
class OffIcon extends AbstractIcon implements IParameterInterface
{

    /**
     *
     */
    public function __construct()
    {

        $this->setValue( AbstractIcon::ICON_OFF );
    }
}
