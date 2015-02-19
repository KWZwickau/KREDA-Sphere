<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Content;

use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\Element\Repository\AbstractContent;
use KREDA\Sphere\Client\Component\IElementInterface;

/**
 * Class Container
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Content
 */
class Container extends AbstractContent implements IElementInterface
{

    /** @var Element $Element */
    private $Element = null;

    /**
     * @param Element $Element
     */
    function __construct( Element $Element )
    {

        $this->Element = $Element;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Element->getContent();
    }
}
