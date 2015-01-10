<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization\Access;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccess;
use KREDA\Sphere\Application\System\Frontend\Authorization\AbstractError;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class Access
 *
 * @package KREDA\Sphere\Application\System\Frontend\Authorization\Access
 */
class Access extends AbstractError
{

    /**
     * @param TblAccess[] $AccessList
     *
     * @throws TemplateTypeException
     */
    function __construct( $AccessList )
    {

        $this->Template = Template::getTemplate( __DIR__.'/Access.twig' );

        $this->Template->setVariable( 'AccessList', $AccessList );
    }
}
