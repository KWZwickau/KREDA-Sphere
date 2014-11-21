<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

/**
 * Class CertificateIcon
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Icon
 */
class CertificateIcon extends Icon implements IParameterInterface
{

    function __construct()
    {

        $this->setValue( Icon::ICON_CERTIFICATE );
    }
}
