<?php
namespace KREDA\Sphere\Common\Frontend\Complex;

use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractAddress
 *
 * @package KREDA\Sphere\Common\Frontend\Address
 */
abstract class AbstractStructure extends AbstractFrontend implements IElementInterface
{

    /** @var TblAddress $tblAddress */
    protected $tblAddress = null;
    /** @var IBridgeInterface $Template */
    protected $Template = null;
    /** @var string $Hash */
    protected $Hash = '';

    /**
     * @return string
     */
    public function getHash()
    {

        if (empty( $this->Hash )) {
            $this->Hash = sha1( json_encode( $this->tblAddress ) );
        }
        return $this->Hash;
    }

    /**
     * @return string
     */
    public function getName()
    {

        return '';
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

}
