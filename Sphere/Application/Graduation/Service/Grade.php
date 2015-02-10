<?php
namespace KREDA\Sphere\Application\Graduation\Service;

use KREDA\Sphere\Application\Gatekeeper\Service\Consumer\Entity\TblConsumer;
use KREDA\Sphere\Application\Graduation\Service\Grade\Entity\TblGradeType;
use KREDA\Sphere\Application\Graduation\Service\Grade\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Grade
 *
 * @package KREDA\Sphere\Application\Graduation\Service
 */
class Grade extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @param TblConsumer $tblConsumer
     */
    function __construct( TblConsumer $tblConsumer = null )
    {

        $this->setDatabaseHandler( 'Graduation', 'Grade', $this->getConsumerSuffix( $tblConsumer ) );
    }

    public function setupDatabaseContent()
    {

        $this->actionCreateGradeType( 'LK', 'Leistungskontrolle' );
        $this->actionCreateGradeType( 'KA', 'Klassenarbeit' );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblGradeType
     */
    public function entityGradeTypeById( $Id )
    {

        return parent::entityGradeTypeById( $Id );
    }

    /**
     * @param string $Acronym
     *
     * @return bool|TblGradeType
     */
    public function entityGradeTypeByAcronym( $Acronym )
    {

        return parent::entityGradeTypeByAcronym( $Acronym );
    }
}
