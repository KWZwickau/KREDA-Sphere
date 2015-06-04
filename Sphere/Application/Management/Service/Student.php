<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Service\Course\Entity\TblCourse;
use KREDA\Sphere\Application\Management\Service\Person\Entity\TblPerson;
use KREDA\Sphere\Application\Management\Service\Student\Entity\TblChildRank;
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
        $this->actionCreateChildRank( '7', '7. Kind' );
        $this->actionCreateChildRank( '8', '8. Kind' );
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

    /**
     * @param string $StudentNumber
     *
     * @return bool|TblStudent
     */
    public function entityStudentByNumber( $StudentNumber )
    {

        return parent::entityStudentByNumber( $StudentNumber );
    }

    /**
     * @param int $Id
     *
     * @return bool|TblChildRank
     */
    public function entityChildRankById( $Id )
    {

        return parent::entityChildRankById( $Id );
    }

    /**
     * @return bool|TblChildRank[]
     */
    public function entityChildRankAll()
    {

        return parent::entityChildRankAll();
    }

    /**
     * @param string $Name
     *
     * @return bool|TblChildRank
     */
    public function entityChildRankByName( $Name )
    {

        return parent::entityChildRankByName( $Name );
    }

    /**
     * @param string       $StudentNumber
     * @param TblPerson    $tblPerson
     * @param TblCourse    $tblCourse
     * @param TblChildRank $tblChildRank
     *
     * @return TblStudent
     */
    public function actionCreateStudent(
        $StudentNumber,
        TblPerson $tblPerson,
        TblCourse $tblCourse,
        TblChildRank $tblChildRank
    ) {

        return parent::actionCreateStudent( $StudentNumber, $tblPerson, $tblCourse, $tblChildRank );
    }

    /**
     * @param TblStudent $tblStudent
     * @param string     $Date
     *
     * @return bool
     */
    public function actionChangeTransferFromDate(
        TblStudent $tblStudent,
        $Date
    ) {

        return parent::actionChangeTransferFromDate( $tblStudent, $Date );
    }

    /**
     * @param TblStudent $tblStudent
     * @param string     $Date
     *
     * @return bool
     */
    public function actionChangeTransferToDate(
        TblStudent $tblStudent,
        $Date
    ) {

        return parent::actionChangeTransferToDate( $tblStudent, $Date );
    }

    /**
     * @param TblStudent $tblStudent
     * @param TblPerson  $tblPerson
     *
     * @return bool
     */
    public function actionChangePerson(
        TblStudent $tblStudent,
        TblPerson $tblPerson
    ) {

        return parent::actionChangePerson( $tblStudent, $tblPerson );
    }
}
