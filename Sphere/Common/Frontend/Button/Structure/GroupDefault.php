<?php
namespace KREDA\Sphere\Common\Frontend\Button\Structure;

use KREDA\Sphere\Common\Frontend\Button\AbstractGroup;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonDangerLink;
use KREDA\Sphere\Common\Frontend\Button\Element\ButtonPrimaryLink;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class GroupDefault
 *
 * @package KREDA\Sphere\Common\Frontend\Button\Structure
 */
class GroupDefault extends AbstractGroup
{

    /**
     * @param ButtonPrimaryLink[]|ButtonDangerLink[] $ButtonList
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
