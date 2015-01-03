<?php
namespace KREDA\Sphere\Application\System\Frontend\Consumer\Setting;

use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class CreateConsumer
 *
 * @package KREDA\Sphere\Application\System\Consumer\Setting
 */
class CreateConsumer extends Shell implements IElementInterface
{

    /**
     * @param TblConsumer[] $tblConsumer
     *
     * @throws TemplateTypeException
     */
    function __construct( $tblConsumer )
    {

        $this->Template = Template::getTemplate( __DIR__.'/CreateConsumer.twig' );
        $this->Template->setVariable( 'UrlBase', HttpKernel::getRequest()->getUrlBase() );

        if (isset( $_REQUEST['ConsumerName'] )) {
            $this->Template->setVariable( 'ConsumerNameValue', $_REQUEST['ConsumerName'] );
        }
        if (isset( $_REQUEST['ConsumerSuffix'] )) {
            $this->Template->setVariable( 'ConsumerSuffixValue', $_REQUEST['ConsumerSuffix'] );
        }
        $this->Template->setVariable( 'tblConsumer', $tblConsumer );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }
}
