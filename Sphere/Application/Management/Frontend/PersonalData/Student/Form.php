<?php
namespace KREDA\Sphere\Application\Management\Frontend\PersonalData\Student;

use KREDA\Sphere\Application\Management\Frontend\PersonalData\Common\Error;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Form
 *
 * @package KREDA\Sphere\Application\Management\PersonalData\Student
 */
class Form extends Error implements IElementInterface
{

    /**
     * @param array $Form
     *
     * @throws TemplateTypeException
     */
    function __construct( $Form )
    {

        $this->Template = Template::getTemplate( __DIR__.'/Form.twig' );

        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );
        $this->Template->setVariable( 'Form', $Form );

        foreach ((array)$_REQUEST as $Key => $Value) {
            if (is_string( $Value )) {
                $this->Template->setVariable( $Key.'Value', $Value );
            }
        }
    }

}
