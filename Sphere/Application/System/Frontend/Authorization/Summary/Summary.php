<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization\Summary;

use KREDA\Sphere\Common\AbstractFrontend;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class Summary
 *
 * @package KREDA\Sphere\Application\System\Frontend\Authorization\Summary
 */
class Summary extends AbstractFrontend
{

    /** @var IBridgeInterface $Template */
    protected $Template = null;

    /**
     * @param array $AccountRoleList
     *
     * @throws TemplateTypeException
     */
    function __construct( $AccountRoleList )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/Summary.twig' );

        $this->Template->setVariable( 'AccountRoleList', $AccountRoleList );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }
}
