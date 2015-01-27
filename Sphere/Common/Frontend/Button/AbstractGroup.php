<?php
namespace KREDA\Sphere\Common\Frontend\Button;

use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonPrimaryLink;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractGroup
 *
 * @package KREDA\Sphere\Common\Frontend\Button
 */
abstract class AbstractGroup extends AbstractFrontend
{

    /** @var ButtonPrimaryLink[] $ButtonList */
    protected $ButtonList = array();
    /** @var IBridgeInterface $Template */
    protected $Template = null;

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }
}
