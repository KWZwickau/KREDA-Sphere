<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumerType;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\EntityAction;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Consumer
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Service
 */
class Consumer extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;
    /** @var array $ConsumerByIdCache */
    private static $ConsumerByIdCache = array();
    /** @var array $ConsumerByIdSuffix */
    private static $ConsumerBySuffixCache = array();

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Gatekeeper', 'Consumer' );
    }

    public function setupDatabaseContent()
    {

        $this->actionCreateConsumer( 'Demo-Schule', 'DS' );
    }

    /**
     * @return Table
     */
    public function getTableConsumer()
    {

        return parent::getTableConsumer();
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblConsumer
     */
    public function entityConsumerById( $Id )
    {

        if (array_key_exists( $Id, self::$ConsumerByIdCache )) {
            return self::$ConsumerByIdCache[$Id];
        }
        self::$ConsumerByIdCache[$Id] = parent::entityConsumerById( $Id );
        return self::$ConsumerByIdCache[$Id];
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblConsumerType
     */
    public function entityConsumerTypeById( $Id )
    {

        return parent::entityConsumerTypeById( $Id );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblConsumer
     */
    public function entityConsumerByName( $Name )
    {

        return parent::entityConsumerByName( $Name );
    }

    /**
     * @param null|string $Session
     *
     * @return bool|TblConsumer
     */
    public function entityConsumerBySession( $Session = null )
    {

        return parent::entityConsumerBySession( $Session );
    }

    /**
     * @return bool|tblConsumer[]
     */
    public function entityConsumerAll()
    {

        return parent::entityConsumerAll();
    }

    /**
     * @param string $Suffix
     *
     * @return bool|TblConsumer
     */
    public function entityConsumerBySuffix( $Suffix )
    {

        if (array_key_exists( $Suffix, self::$ConsumerBySuffixCache )) {
            return self::$ConsumerBySuffixCache[$Suffix];
        }
        self::$ConsumerBySuffixCache[$Suffix] = parent::entityConsumerBySuffix( $Suffix );
        return self::$ConsumerBySuffixCache[$Suffix];
    }

    /**
     * @param TblAddress       $tblAddress
     * @param null|TblConsumer $tblConsumer
     *
     * @return bool
     */
    public function executeChangeAddress( TblAddress $tblAddress, TblConsumer $tblConsumer = null )
    {

        return parent::actionChangeAddress( $tblAddress, $tblConsumer );
    }
}
