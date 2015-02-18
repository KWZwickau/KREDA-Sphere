<?php
namespace KREDA\Sphere\Common\Frontend\Complex\Structure;

use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Common\Frontend\Complex\AbstractStructure;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class ComplexAddress
 *
 * @package KREDA\Sphere\Common\Frontend\Complex\Structure
 */
class ComplexAddress extends AbstractStructure
{

    /**
     * @param TblAddress $tblAddress
     *
     * @throws TemplateTypeException
     */
    function __construct( TblAddress $tblAddress )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/ComplexAddress.twig' );
        $this->Template->setVariable( 'Address', $tblAddress );
    }

}
