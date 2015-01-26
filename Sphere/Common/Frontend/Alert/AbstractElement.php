<?php
namespace KREDA\Sphere\Common\Frontend\Alert;

use KREDA\Sphere\Common\AbstractFrontend;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractElement
 *
 * @package KREDA\Sphere\Common\Frontend\Alert
 */
abstract class AbstractElement extends AbstractFrontend
{

    /** @var string $Name */
    protected $Name;
    /** @var IBridgeInterface $Template */
    protected $Template = null;

    /**
     * @param string $Name
     */
    function __construct( $Name )
    {

        $this->Name = $Name;
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

    /**
     * @return string
     */
    public function getName()
    {

        return $this->Name;
    }
}
