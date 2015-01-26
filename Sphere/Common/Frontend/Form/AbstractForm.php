<?php
namespace KREDA\Sphere\Common\Frontend\Form;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormCol;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormGroup;
use KREDA\Sphere\Common\Frontend\Form\Structure\GridFormRow;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractForm
 *
 * @package KREDA\Sphere\Common\Frontend\Form
 */
abstract class AbstractForm extends AbstractFrontend
{

    /** @var GridFormGroup[] $GridGroupList */
    protected $GridGroupList = array();
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
            $this->Hash = sha1( json_encode( $this->GridGroupList ) );
        }
        return $this->Hash;
    }

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

        /** @var GridFormGroup $GridGroup */
        foreach ((array)$this->GridGroupList as $GridGroup) {
            /** @var GridFormRow $GridRow */
            foreach ((array)$GridGroup->getRowList() as $GridRow) {
                /** @var GridFormCol $GridCol */
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

        /** @var GridFormGroup $GridGroup */
        foreach ((array)$this->GridGroupList as $GridGroup) {
            /** @var GridFormRow $GridRow */
            foreach ((array)$GridGroup->getRowList() as $GridRow) {
                /** @var GridFormCol $GridCol */
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

    /**
     * @param GridFormGroup $GridGroup
     */
    public function appendGridGroup( GridFormGroup $GridGroup )
    {

        array_push( $this->GridGroupList, $GridGroup );
    }

    /**
     * @param GridFormGroup $GridGroup
     */
    public function prependGridGroup( GridFormGroup $GridGroup )
    {

        array_unshift( $this->GridGroupList, $GridGroup );
    }
}
