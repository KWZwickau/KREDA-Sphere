<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization\Privilege;

use KREDA\Sphere\Application\Gatekeeper\Service\Access\Entity\TblAccessPrivilege;
use KREDA\Sphere\Application\System\Frontend\Authorization\AbstractError;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class Privilege
 *
 * @package KREDA\Sphere\Application\System\Frontend\Authorization\Privilege
 */
class Privilege extends AbstractError
{

    /**
     * @param TblAccessPrivilege[] $AccessPrivilegeList
     *
     * @throws TemplateTypeException
     */
    function __construct( $AccessPrivilegeList )
    {

        $this->Template = Template::getTemplate( __DIR__.'/Privilege.twig' );

        $this->Template->setVariable( 'AccessPrivilegeList', $AccessPrivilegeList );
    }
}
