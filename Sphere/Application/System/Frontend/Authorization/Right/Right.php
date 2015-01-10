<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization\Right;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessRight;
use KREDA\Sphere\Application\System\Frontend\Authorization\AbstractError;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class Right
 *
 * @package KREDA\Sphere\Application\System\Frontend\Authorization\Right
 */
class Right extends AbstractError
{

    /**
     * @param TblAccessRight[] $AccessRightList
     *
     * @throws TemplateTypeException
     */
    function __construct( $AccessRightList )
    {

        $this->Template = Template::getTemplate( __DIR__.'/Right.twig' );

        $this->Template->setVariable( 'AccessRightList', $AccessRightList );
    }
}
