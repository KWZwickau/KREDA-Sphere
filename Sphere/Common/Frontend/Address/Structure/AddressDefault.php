<?php
namespace KREDA\Sphere\Common\Frontend\Address\Structure;

use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Common\Frontend\Address\AbstractAddress;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class AddressDefault
 *
 * @package KREDA\Sphere\Common\Frontend\Address\Structure
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

        $this->Template = $this->extensionTemplate( __DIR__.'/AddressDefault.twig' );
        $this->Template->setVariable( 'Address', $tblAddress );
    }

}
