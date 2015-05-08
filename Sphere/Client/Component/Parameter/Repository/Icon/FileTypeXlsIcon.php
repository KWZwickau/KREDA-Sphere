<?php
namespace KREDA\Sphere\Client\Component\Parameter\Repository\Icon;

use KREDA\Sphere\Client\Component\IParameterInterface;
use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;

/**
 * Class FileTypeXlsIcon
 *
 * @package KREDA\Sphere\Client\Component\Parameter\Repository\Icon
 */
class FileTypeXlsIcon extends AbstractIcon implements IParameterInterface
{

    /**
     *
     */
    public function __construct()
    {

        $this->setValue( AbstractIcon::ICON_FILETYPE_XLS );
    }
}
