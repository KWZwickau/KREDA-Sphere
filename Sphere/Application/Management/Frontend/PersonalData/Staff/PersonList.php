<?php
namespace KREDA\Sphere\Application\Management\Frontend\PersonalData\Staff;

use KREDA\Sphere\Application\Management\Frontend\PersonalData\Common\Error;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class PersonList
 *
 * @package KREDA\Sphere\Application\Management\PersonalData\Staff
 */
class PersonList extends Error implements IElementInterface
{

    /**
     * @param array $PersonList
     *
     * @throws TemplateTypeException
     */
    function __construct( $PersonList )
    {

        $this->Template = Template::getTemplate( __DIR__.'/PersonList.twig' );

        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );
        $this->Template->setVariable( 'PersonList', $PersonList );

        foreach ((array)$_REQUEST as $Key => $Value) {
            if (is_string( $Value )) {
                $this->Template->setVariable( $Key.'Value', $Value );
            }
        }
    }

}
