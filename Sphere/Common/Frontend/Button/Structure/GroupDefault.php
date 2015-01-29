<?php
namespace KREDA\Sphere\Common\Frontend\Button\Structure;

use KREDA\Sphere\Common\Frontend\Button\AbstractGroup;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class GroupDefault
 *
 * @package KREDA\Sphere\Common\Frontend\Button\Structure
 */
class GroupDefault extends AbstractGroup
{

    /**
     * @param \KREDA\Sphere\Common\Frontend\Button\Element\ButtonPrimaryLink[]|\KREDA\Sphere\Common\Frontend\Button\Element\ButtonDangerLink[] $ButtonList
     *
     * @throws TemplateTypeException
     */
    function __construct( $ButtonList )
    {

        if (!is_array( $ButtonList )) {
            $ButtonList = array( $ButtonList );
        }
        $this->ButtonList = $ButtonList;

        $this->Template = $this->extensionTemplate( __DIR__.'/GroupDefault.twig' );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'ButtonList', $this->ButtonList );
        return $this->Template->getContent();
    }
}
