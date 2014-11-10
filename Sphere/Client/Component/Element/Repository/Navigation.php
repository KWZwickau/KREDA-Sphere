<?php
namespace KREDA\Sphere\Client\Component\Element\Repository;

use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\IElementInterface;

/**
 * Class Navigation
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository
 */
abstract class Navigation extends Element implements IElementInterface
{

    /**
     * @return string
     */
    abstract public function getContent();
}
