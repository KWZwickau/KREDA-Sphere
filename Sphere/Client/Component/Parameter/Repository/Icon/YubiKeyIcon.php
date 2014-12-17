<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;

/**
 * Class YubiKey Icon
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Icon
 */
class YubiKeyIcon extends AbstractIcon implements IParameterInterface
{

    /**
     *
     */
    function __construct()
    {

        $this->setValue( AbstractIcon::ICON_YUBIKEY );
    }
}
