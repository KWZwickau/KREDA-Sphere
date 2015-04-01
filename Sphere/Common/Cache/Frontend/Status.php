<?php
namespace KREDA\Sphere\Common\Cache\Frontend;

use KREDA\Sphere\Client\Frontend\Input\AbstractType;
use KREDA\Sphere\Common\Cache\ICacheInterface;

/**
 * Class Status
 *
 * @package KREDA\Sphere\Common\Cache\Frontend
 */
class Status extends AbstractType
{

    /** @var string $Stage */
    private $Stage = '';

    /**
     * @param ICacheInterface $Cache
     */
    function __construct( ICacheInterface $Cache )
    {

        $Rate = $this->extensionTemplate( __DIR__.'/Rate.twig' );
        $Rate->setVariable( 'CountHits', $Cache->getCountHits() );
        $Rate->setVariable( 'CountMisses', $Cache->getCountMisses() );

        $Memory = $this->extensionTemplate( __DIR__.'/Memory.twig' );
        $Memory->setVariable( 'SizeAvailable', $Cache->getSizeAvailable() );
        $Memory->setVariable( 'SizeUsed', $Cache->getSizeUsed() );
        $Memory->setVariable( 'SizeFree', $Cache->getSizeFree() );
        $Memory->setVariable( 'SizeWasted', $Cache->getSizeWasted() );

        $this->Stage = $Rate->getContent().$Memory->getContent();
    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Stage;
    }

}
