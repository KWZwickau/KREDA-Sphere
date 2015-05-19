<?php
namespace KREDA\Sphere\Application\Billing\Service;

use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Billing\Service\Commodity\EntityAction;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;

/**
 * Class Commodity
 *
 * @package KREDA\Sphere\Application\Billing\Service
 */
class Commodity extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Billing', 'Commodity', $this->getConsumerSuffix() );
    }

    /**
     * @return bool|TblCommodity[]
     */
    public function entityCommodityAll()
    {
        return parent::entityCommodityAll();
    }

    /**
     * @param \KREDA\Sphere\Client\Frontend\Form\AbstractType $View
     *
     * @param $Commodity
     *
     * @return \KREDA\Sphere\Client\Frontend\Form\AbstractType
     */
    public function executeCreateCommodity(
        AbstractType &$View = null,
        $Commodity
    ) {

        /**
         * Skip to Frontend
         */
        if (null === $Commodity
        ) {
            return $View;
        }

        $Error = false;

        if (isset($Commodity['Name'] ) && empty( $Commodity['Name'] )) {
            $View->setError( 'Commodity[Name]', 'Bitte geben Sie einen Namen an' );
            $Error = true;
        }
        if (isset( $Commodity['Description'] ) && empty(  $Commodity['Description'] )) {
            $View->setError( 'Commodity[Description]', 'Bitte geben Sie eine Beschreibung an' );
            $Error = true;
        }

        if (!$Error) {
            $this->actionCreateCommodity(
                $Commodity['Name'],
                $Commodity['Description']
            );
            return new Success( 'Die Leistung wurde erfolgreich angelegt' )
            .new Redirect( '/Sphere/Billing/Commodity', 2);
        }
        return $View;
    }
}
