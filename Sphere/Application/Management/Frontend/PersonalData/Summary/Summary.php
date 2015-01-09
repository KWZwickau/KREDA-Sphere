<?php
namespace KREDA\Sphere\Application\Management\Frontend\PersonalData\Summary;

use KREDA\Sphere\Application\Management\Frontend\PersonalData\Common\Error;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class Summary
 *
 * @package KREDA\Sphere\Application\Management\PersonalData\Student
 */
class Summary extends Error implements IElementInterface
{

    /**
     * @param array $Summary
     *
     * @throws TemplateTypeException
     */
    function __construct( $Summary )
    {

        $this->Template = Template::getTemplate( __DIR__.'/Summary.twig' );
        $this->Template->setVariable( 'Summary', $Summary );
    }

}
