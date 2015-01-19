<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Address\Structure;

use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Common\AbstractFrontend\Address\AbstractAddress;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class AddressDefault
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Address\Structure
 */
class AddressDefault extends AbstractAddress
{

    /**
     * @param TblAddress $tblAddress
     *
     * @throws TemplateTypeException
     */
    function __construct( TblAddress $tblAddress )
    {

        $this->Template = Template::getTemplate( __DIR__.'/AddressDefault.twig' );
        $this->Template->setVariable( 'Address', $tblAddress );
    }

}
