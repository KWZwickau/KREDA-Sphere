<?php
namespace KREDA\Sphere\Application\System\Frontend\Authorization;

use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use KREDA\Sphere\Common\AbstractFrontend;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class AbstractError
 *
 * @package KREDA\Sphere\Application\System\Frontend
 */
abstract class AbstractError extends AbstractFrontend implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    protected $Template = null;

    /**
     * @return string
     */
    public function getContent()
    {

        $this->setRequestValues( $this->Template );
        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );
        return $this->Template->getContent();
    }
}
