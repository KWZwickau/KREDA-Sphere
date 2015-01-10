<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization\Role;

use KREDA\Sphere\Application\Gatekeeper\Service\Account\Entity\TblAccountRole;
use KREDA\Sphere\Application\System\Frontend\Authorization\AbstractError;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class Role
 *
 * @package KREDA\Sphere\Application\System\Frontend\Authorization\Role
 */
class Role extends AbstractError
{

    /**
     * @param TblAccountRole[] $AccountRoleList
     *
     * @throws TemplateTypeException
     */
    function __construct( $AccountRoleList )
    {

        $this->Template = Template::getTemplate( __DIR__.'/Role.twig' );

        $this->Template->setVariable( 'AccountRoleList', $AccountRoleList );
    }
}
