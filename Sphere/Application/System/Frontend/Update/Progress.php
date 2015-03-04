<?php
namespace KREDA\Sphere\Application\System\Frontend\Update;

use KREDA\Sphere\Common\AbstractFrontend;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class Progress
 *
 * @package KREDA\Sphere\Application\System\Frontend\Update
 */
class Progress extends AbstractFrontend
{

    /** @var IBridgeInterface $Template */
    protected $Template = null;

    /**
     * @param null $Identifier
     */
    function __construct( $Identifier = null )
    {

        if (null === $Identifier) {
            $Identifier = sha1( uniqid( 'Progress', true ) );
        }
        $this->Template = $this->extensionTemplate( __DIR__.'/Progress.twig' );
        $this->Template->setVariable( 'Identifier', $Identifier );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }
}
