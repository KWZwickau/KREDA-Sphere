<?php
namespace KREDA\Sphere\Common\AbstractFrontend\Form;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridCol;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridGroup;
use KREDA\Sphere\Common\AbstractFrontend\Form\Structure\GridRow;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractForm
 *
 * @package KREDA\Sphere\Common\AbstractFrontend\Form
 */
abstract class AbstractForm extends AbstractFrontend
{

    /** @var GridGroup[] $GridGroupList */
    protected $GridGroupList = array();
    /** @var IBridgeInterface $Template */
    protected $Template = null;

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }

    /**
     * @param              $Name
     * @param string       $Message
     * @param AbstractIcon $Icon
     */
    public function setError( $Name, $Message, AbstractIcon $Icon = null )
    {

        /** @var GridGroup $GridGroup */
        foreach ((array)$this->GridGroupList as $GridGroup) {
            /** @var GridRow $GridRow */
            foreach ((array)$GridGroup->getRowList() as $GridRow) {
                /** @var GridCol $GridCol */
                foreach ((array)$GridRow->getColList() as $GridCol) {
                    /** @var AbstractElement $GridElement */
                    foreach ((array)$GridCol->getElementList() as $GridElement) {
                        if ($GridElement->getName() == $Name) {
                            $GridElement->setError( $Message, $Icon );
                        }
                    }
                }
            }
        }
    }

    /**
     * @param              $Name
     * @param string       $Message
     * @param AbstractIcon $Icon
     */
    public function setSuccess( $Name, $Message, AbstractIcon $Icon = null )
    {

        /** @var GridGroup $GridGroup */
        foreach ((array)$this->GridGroupList as $GridGroup) {
            /** @var GridRow $GridRow */
            foreach ((array)$GridGroup->getRowList() as $GridRow) {
                /** @var GridCol $GridCol */
                foreach ((array)$GridRow->getColList() as $GridCol) {
                    /** @var AbstractElement $GridElement */
                    foreach ((array)$GridCol->getElementList() as $GridElement) {
                        if ($GridElement->getName() == $Name) {
                            $GridElement->setSuccess( $Message, $Icon );
                        }
                    }
                }
            }
        }
    }
}
