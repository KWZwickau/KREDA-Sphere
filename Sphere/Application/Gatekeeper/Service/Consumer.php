<?php
namespace KREDA\Sphere\Application\Gatekeeper\Service;

use Doctrine\DBAL\Schema\Table;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumerTyp;
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

    /**
     * @throws \Exception
     */
    public function __construct()
    {

        $this->setDatabaseHandler( 'Gatekeeper', 'Consumer' );
    }

    public function setupDatabaseContent()
    {

        $this->actionCreateConsumer( 'Demo-Schule', 'DS' );
        $this->actionCreateConsumer( 'Evangelische Schulgemeinschaft Erzgebirge', 'EGE' );
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

        return parent::entityConsumerById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblConsumerTyp
     */
    public function entityConsumerTypById( $Id )
    {

        return parent::entityConsumerTypById( $Id );
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

        return parent::entityConsumerBySuffix( $Suffix );
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
