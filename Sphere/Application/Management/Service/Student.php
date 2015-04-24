<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Student\Entity\TblStudent;
use KREDA\Sphere\Application\Management\Service\Student\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Student
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Student extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Management', 'Student', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {

        $this->actionCreateChildRank( '1', '1. Kind' );
        $this->actionCreateChildRank( '2', '2. Kind' );
        $this->actionCreateChildRank( '3', '3. Kind' );
        $this->actionCreateChildRank( '4', '4. Kind' );
        $this->actionCreateChildRank( '5', '5. Kind' );
        $this->actionCreateChildRank( '6', '6. Kind' );
    }

    /**
     * @param TblPerson $tblPerson
     *
     * @return bool|TblStudent
     */
    public function entityStudentByPerson( TblPerson $tblPerson )
    {

        return parent::entityStudentByPerson( $tblPerson );
    }

}
