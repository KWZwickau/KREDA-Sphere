<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumerType;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\EntityAction;
use KREDA\Sphere\Application\Management\Service\Address\Entity\TblAddress;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Redirect;
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

        $this->actionCreateConsumer( 'DS', 'Demo-Schule' );
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
     * @param TblAddress       $tblAddress
     * @param null|TblConsumer $tblConsumer
     *
     * @return bool
     */
    public function executeChangeAddress( TblAddress $tblAddress, TblConsumer $tblConsumer = null )
    {

        return parent::actionChangeAddress( $tblAddress, $tblConsumer );
    }

    /**
     * @param AbstractType $View
     * @param string          $ConsumerSuffix
     * @param string          $ConsumerName
     * @param null|TblAddress $tblAddress
     *
     * @return AbstractType|\KREDA\Sphere\Client\Frontend\Redirect
     */
    public function executeCreateConsumer(
        AbstractType &$View,
        $ConsumerSuffix,
        $ConsumerName,
        TblAddress $tblAddress = null
    ) {

        if (null === $ConsumerName
            && null === $ConsumerSuffix
        ) {
            return $View;
        }

        $Error = false;
        if (null !== $ConsumerSuffix && empty( $ConsumerSuffix )) {
            $View->setError( 'ConsumerSuffix', 'Bitte geben Sie ein Mandantenkürzel an' );
            $Error = true;
        }
        if ($this->entityConsumerBySuffix( $ConsumerSuffix )) {
            $View->setError( 'ConsumerSuffix', 'Das Mandantenkürzel muss einzigartig sein' );
            $Error = true;
        }
        if (null !== $ConsumerName && empty( $ConsumerName )) {
            $View->setError( 'ConsumerName', 'Bitte geben Sie einen gültigen Mandantenname ein' );
            $Error = true;
        }

        if ($Error) {
            return $View;
        } else {
            $this->actionCreateConsumer( $ConsumerSuffix, $ConsumerName, $tblAddress );
            return new Redirect( '/Sphere/System/Consumer/Create', 0 );
        }
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
}
