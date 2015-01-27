<?php
namespace KREDA\Sphere\Client\Component\Element;

use KREDA\Sphere\Client\Component\IElementInterface;
use KREDA\Sphere\Common\AbstractExtension;

/**
 * Class Element
 *
 * @package KREDA\Sphere\Client\Component\Element
 */
abstract class Element extends AbstractExtension implements IElementInterface
{

    /**
     * @return string
     */
    function __toString()
    {

        return $this->getContent();
    }
}
