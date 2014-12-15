<?php
namespace KREDA\Sphere\Application\Management\PersonalData\Student;

use KREDA\Sphere\Application\Management\PersonalData\Common\Error;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Identity
 *
 * @package KREDA\Sphere\Application\Management\PersonalData\Student
 */
class Identity extends Error implements IElementInterface
{

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/Identity.twig' );
        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );

        foreach ((array)$_REQUEST as $Key => $Value) {
            if (is_string( $Value )) {
                $this->Template->setVariable( $Key.'Value', $Value );
            }
        }
    }

}
