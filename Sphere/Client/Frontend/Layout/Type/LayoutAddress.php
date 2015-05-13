<?php
namespace KREDA\Sphere\Client\Frontend\Layout\Type;

use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Client\Frontend\Layout\AbstractType;

/**
 * Class LayoutAddress
 *
 * @package KREDA\Sphere\Client\Frontend\Layout\Type
 */
class LayoutAddress extends AbstractType
{

    /**
     * @param TblAddress $tblAddress
     */
    public function __construct( TblAddress $tblAddress )
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/LayoutAddress.twig' );
        $this->Template->setVariable( 'Address', $tblAddress );
    }
}
