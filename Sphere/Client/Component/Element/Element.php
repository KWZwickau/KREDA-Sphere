<?php
namespace KREDA\Sphere\Client\Component\Element;

use KREDA\Sphere\Client\Component\IElementInterface;
use KREDA\Sphere\Common\AbstractAddOn;

/**
 * Class Element
 *
 * @package KREDA\Sphere\Client\Component\Element
 */
abstract class Element extends AbstractAddOn implements IElementInterface
{

    /**
     * @return string
     */
    function __toString()
    {

        return $this->getContent();
    }
}
