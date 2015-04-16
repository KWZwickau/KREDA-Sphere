<?php
namespace KREDA\Sphere\Client\Frontend\Form;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Frontend\Button\AbstractType as AbstractButton;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Input\AbstractType as AbstractInput;
use KREDA\Sphere\Common\AbstractFrontend;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractType
 *
 * @package KREDA\Sphere\Client\Frontend\Form
 */
abstract class AbstractType extends AbstractFrontend
{

    /** @var FormGroup[] $GridGroupList */
    protected $GridGroupList = array();
    /** @var AbstractInput[] $GridButtonList */
    protected $GridButtonList = array();
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
            $GroupList = $this->GridGroupList;
            array_walk( $GroupList, function ( &$G ) {

                if (is_object( $G )) {
                    $G = serialize( $G );
                }
            } );
            $this->Hash = sha1( json_encode( $GroupList ) );
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
     * @param string $Name
     * @param string       $Message
     * @param AbstractIcon $Icon
     */
    public function setError( $Name, $Message, AbstractIcon $Icon = null )
    {

        /** @var FormGroup $GridGroup */
        foreach ((array)$this->GridGroupList as $GridGroup) {
            /** @var FormRow $GridRow */
            foreach ((array)$GridGroup->getFormRow() as $GridRow) {
                /** @var FormColumn $GridCol */
                foreach ((array)$GridRow->getFormColumn() as $GridCol) {
                    /** @var AbstractInput $GridElement */
                    foreach ((array)$GridCol->getAbstractFrontend() as $GridElement) {
                        if ($GridElement->getName() == $Name) {
                            $GridElement->setError( $Message, $Icon );
                        }
                    }
                }
            }
        }
    }

    /**
     * @param string $Name
     * @param string       $Message
     * @param AbstractIcon $Icon
     */
    public function setSuccess( $Name, $Message = '', AbstractIcon $Icon = null )
    {

        /** @var FormGroup $GridGroup */
        foreach ((array)$this->GridGroupList as $GridGroup) {
            /** @var FormRow $GridRow */
            foreach ((array)$GridGroup->getFormRow() as $GridRow) {
                /** @var FormColumn $GridCol */
                foreach ((array)$GridRow->getFormColumn() as $GridCol) {
                    /** @var AbstractInput $GridElement */
                    foreach ((array)$GridCol->getAbstractFrontend() as $GridElement) {
                        if ($GridElement->getName() == $Name) {
                            $GridElement->setSuccess( $Message, $Icon );
                        }
                    }
                }
            }
        }
    }

    /**
     * @param string $Message
     */
    public function setConfirm( $Message )
    {

        $this->Template->setVariable( 'FormConfirm', $Message );
    }

    /**
     * @param AbstractButton $Button
     */
    public function appendFormButton( AbstractButton $Button )
    {

        array_push( $this->GridButtonList, $Button );
    }

    /**
     * @param AbstractButton $Button
     */
    public function prependFormButton( AbstractButton $Button )
    {

        array_unshift( $this->GridButtonList, $Button );
    }

    /**
     * @param FormGroup $GridGroup
     */
    public function appendGridGroup( FormGroup $GridGroup )
    {

        array_push( $this->GridGroupList, $GridGroup );
    }

    /**
     * @param FormGroup $GridGroup
     */
    public function prependGridGroup( FormGroup $GridGroup )
    {

        array_unshift( $this->GridGroupList, $GridGroup );
    }
}
