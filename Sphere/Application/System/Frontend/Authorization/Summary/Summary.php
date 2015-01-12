<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization\Summary;

use KREDA\Sphere\Application\System\Frontend\Authorization\AbstractError;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class Summary
 *
 * @package KREDA\Sphere\Application\System\Frontend\Authorization\Summary
 */
class Summary extends AbstractError
{

    /**
     * @param array $AccountRoleList
     * @param array $RightList
     *
     * @throws TemplateTypeException
     */
    function __construct( $AccountRoleList, $RightList )
    {

        $this->Template = Template::getTemplate( __DIR__.'/Summary.twig' );

        $this->Template->setVariable( 'AccountRoleList', $AccountRoleList );
        $this->Template->setVariable( 'RightList', $RightList );
    }
}
