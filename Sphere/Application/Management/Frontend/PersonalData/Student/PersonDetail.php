<?php
namespace KREDA\Sphere\Application\Management\Frontend\PersonalData\Student;

use KREDA\Sphere\Application\Management\Frontend\PersonalData\Common\Error;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class PersonDetail
 *
 * @package KREDA\Sphere\Application\Management\PersonalData\Student
 */
class PersonDetail extends Error implements IElementInterface
{

    /**
     * @param TblPerson $PersonDetail
     *
     * @throws TemplateTypeException
     */
    function __construct( $PersonDetail )
    {

        $this->Template = Template::getTemplate( __DIR__.'/PersonDetail.twig' );

        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );
        $this->Template->setVariable( 'PersonDetail', $PersonDetail );
    }

}
