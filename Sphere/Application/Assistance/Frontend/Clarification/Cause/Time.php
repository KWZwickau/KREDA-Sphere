<?php
namespace KREDA\Sphere\Application\Assistance\Frontend\Clarification\Cause;

use KREDA\Sphere\Common\AbstractFrontend;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class Time
 *
 * @package KREDA\Sphere\Application\Assistance\Frontend\Clarification\Cause
 */
class Time extends AbstractFrontend
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /**
     * @param string $Message
     *
     * @throws TemplateTypeException
     */
    function __construct( $Message )
    {

        $this->Template = Template::getTemplate( __DIR__.'/Time.twig' );
        $this->Template->setVariable( 'Message', $Message );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }
}
