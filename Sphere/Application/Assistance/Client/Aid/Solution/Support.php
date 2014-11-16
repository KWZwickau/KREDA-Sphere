<?php
namespace KREDA\Sphere\Application\Assistance\Client\Aid\Solution;

use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class Support
 * @package KREDA\Sphere\Application\Assistance\Client\Aid\Solution
 */
class Support extends Element implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /**
     * @param string $Message
     * @throws TemplateTypeException
     */
    function __construct($Message)
    {

        $this->Template = Template::getTemplate(__DIR__ . '/Support.twig');
        $this->Template->setVariable('Message', $Message);
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }
}
