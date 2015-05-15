<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblCategory;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblLevel;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubjectGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblTerm;
use KREDA\Sphere\Application\Management\Service\Education\EntityAction;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Education
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Education extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Management', 'Education', $this->getConsumerSuffix() );
    }

    public function setupDatabaseContent()
    {

        $this->actionCreateSubject( 'Ast', 'Astronomie' );
        $this->actionCreateSubject( 'Bio', 'Biologie' );
        $this->actionCreateSubject( 'Ch', 'Chemie' );
        $this->actionCreateSubject( 'De', 'Deutsch' );
        $this->actionCreateSubject( 'FöMa', 'Förderunterricht Mathematik' );
        $this->actionCreateSubject( 'GK', 'Gemeinschaftskunde/Rechtserziehung' );
        $this->actionCreateSubject( 'Geo', 'Geographie' );
        $this->actionCreateSubject( 'Ge', 'Geschichte' );
        $this->actionCreateSubject( 'Inf', 'Informatik' );
        $this->actionCreateSubject( 'KL', 'Klassenleiterstunde' );
        $this->actionCreateSubject( 'Ku', 'Kunst' );
        $this->actionCreateSubject( 'Pk', 'Künstlerisches Profil' );
        $this->actionCreateSubject( 'Ma', 'Mathematik' );
        $this->actionCreateSubject( 'Mu', 'Musik' );
        $this->actionCreateSubject( 'Ph', 'Physik' );
        $this->actionCreateSubject( 'Spo', 'Sport' );
        $this->actionCreateSubject( 'TuN', 'Technik und Natur' );
        $this->actionCreateSubject( 'TC', 'Technik/Computer' );
        $this->actionCreateSubject( 'VK', 'Vertiefungskurs' );
        $this->actionCreateSubject( 'WTH', 'Wirtschaft-Technik-Haushalt/Soziales' );

        $tblCategory = $this->actionCreateCategory( 'Religion' );

        $tblSubject = $this->actionCreateSubject( 'Eth', 'Ethik' );
        $this->actionAddSubjectCategory( $tblSubject, $tblCategory );
        $tblSubject = $this->actionCreateSubject( 'ReE', 'Religion evangelisch' );
        $this->actionAddSubjectCategory( $tblSubject, $tblCategory );

        $tblCategory = $this->actionCreateCategory( 'Fremdsprache' );

        $tblSubject = $this->actionCreateSubject( 'La', 'Latein' );
        $this->actionAddSubjectCategory( $tblSubject, $tblCategory );
        $tblSubject = $this->actionCreateSubject( 'En', 'Englisch' );
        $this->actionAddSubjectCategory( $tblSubject, $tblCategory );
        $tblSubject = $this->actionCreateSubject( 'Fr', 'Französisch' );
        $this->actionAddSubjectCategory( $tblSubject, $tblCategory );
        $tblSubject = $this->actionCreateSubject( 'Ru', 'Russisch' );
        $this->actionAddSubjectCategory( $tblSubject, $tblCategory );
        $tblSubject = $this->actionCreateSubject( 'Sor', 'Sorbisch' );
        $this->actionAddSubjectCategory( $tblSubject, $tblCategory );
        $tblSubject = $this->actionCreateSubject( 'DaZ', 'Deutsch als Zweitsprache' );
        $this->actionAddSubjectCategory( $tblSubject, $tblCategory );

        $tblCategory = $this->actionCreateCategory( 'Profil' );

        $tblSubject = $this->actionCreateSubject( 'Pg', 'Profil Geisteswissensch.' );
        $this->actionAddSubjectCategory( $tblSubject, $tblCategory );
        $tblSubject = $this->actionCreateSubject( 'Pn', 'Profil Naturwissenschaften' );
        $this->actionAddSubjectCategory( $tblSubject, $tblCategory );

        $tblCategory = $this->actionCreateCategory( 'Neigungskurs' );

        $tblSubject = $this->actionCreateSubject( 'Nk', 'Neigungskurs' );
        $this->actionAddSubjectCategory( $tblSubject, $tblCategory );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblLevel
     */
    public function entityLevelById( $Id )
    {

        return parent::entityLevelById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblGroup
     */
    public function entityGroupById( $Id )
    {

        return parent::entityGroupById( $Id );
    }

    /**
     * @param int $Id
     *
     * @return bool|TblSubjectGroup
     */
    public function entitySubjectGroupById( $Id )
    {

        return parent::entitySubjectGroupById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblSubject
     */
    public function entitySubjectById( $Id )
    {

        return parent::entitySubjectById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblCategory
     */
    public function entityCategoryById( $Id )
    {

        return parent::entityCategoryById( $Id );
    }

    /**
     * @param string $Name
     *
     * @return bool|TblCategory
     */
    public function entityCategoryByName( $Name )
    {

        return parent::entityCategoryByName( $Name );
    }

    /**
     * @return bool|TblSubject[]
     */
    public function entitySubjectAll()
    {

        return parent::entitySubjectAll();
    }

    /**
     * @param TblCategory $tblCategory
     *
     * @return bool|TblSubject[]
     */
    public function entitySubjectAllByCategory( TblCategory $tblCategory )
    {

        return parent::entitySubjectAllByCategory( $tblCategory );
    }

    /**
     * @return bool|TblLevel[]
     */
    public function entityLevelAll()
    {

        return parent::entityLevelAll();
    }

    /**
     * @return bool|TblGroup[]
     */
    public function entityGroupAll()
    {

        return parent::entityGroupAll();
    }

    /**
     * @return bool|TblTerm[]
     */
    public function entityTermAll()
    {

        return parent::entityTermAll();
    }

    /**
     * @param AbstractType $View
     * @param array        $Name
     * @param array        $FirstTerm
     * @param array        $SecondTerm
     * @param int          $Course
     *
     * @return AbstractType|Redirect
     */
    public function executeCreateTerm( AbstractType &$View, $Name, $FirstTerm, $SecondTerm, $Course )
    {

        if (
            null === $Name
            && null === $FirstTerm
            && null === $SecondTerm
        ) {
            return $View;
        }

        $Error = false;
        if (isset( $Name ) && empty( $Name )) {
            $View->setError( 'Name', 'Bitte geben Sie einen Namen für das Schuljahr an' );
            $Error = true;
        }
        if (isset( $Name ) && !empty( $Name )) {
            if ($this->entityTermByName( $Name )) {
                $View->setError( 'Name', 'Bitte geben Sie einen eindeutigen Namen für das Schuljahr ein' );
                $Error = true;
            }
        }
        if (isset( $FirstTerm['DateFrom'] ) && empty( $FirstTerm['DateFrom'] )) {
            $View->setError( 'FirstTerm[DateFrom]', 'Bitte geben Sie ein Start-Datum an' );
            $Error = true;
        }
        if (isset( $FirstTerm['DateTo'] ) && empty( $FirstTerm['DateTo'] )) {
            $View->setError( 'FirstTerm[DateTo]', 'Bitte geben Sie ein Ende-Datum an' );
            $Error = true;
        }
        if (isset( $SecondTerm['DateFrom'] ) && empty( $SecondTerm['DateFrom'] )) {
            $View->setError( 'SecondTerm[DateFrom]', 'Bitte geben Sie ein Start-Datum an' );
            $Error = true;
        }
        if (isset( $SecondTerm['DateTo'] ) && empty( $SecondTerm['DateTo'] )) {
            $View->setError( 'SecondTerm[DateTo]', 'Bitte geben Sie ein Ende-Datum an' );
            $Error = true;
        }
        if (isset( $Course ) && !empty( $Course )) {
            if (!Management::serviceCourse()->entityCourseById( $Course )) {
                $View->setError( 'Course', 'Bitte wählen Sie einen verfügbaren Bildungsgang' );
                $Error = true;
            }
        }

        if ($Error) {
            return $View;
        } else {
            $this->actionCreateTerm( $Name, $FirstTerm['DateFrom'], $FirstTerm['DateTo'], $SecondTerm['DateFrom'],
                $SecondTerm['DateTo'], Management::serviceCourse()->entityCourseById( $Course ) );
            return new Redirect( '/Sphere/Management/Period/SchoolYear', 0 );
        }
    }

    /**
     * @param AbstractType $View
     * @param array        $Name
     * @param array        $FirstTerm
     * @param array        $SecondTerm
     * @param int          $Course
     *
     * @return AbstractType|Redirect
     */
    public function executeChangeTerm( AbstractType &$View, $Id, $Name, $FirstTerm, $SecondTerm, $Course )
    {

        if (
            null === $Name
            && null === $FirstTerm
            && null === $SecondTerm
        ) {
            return $View;
        }

        $tblTerm = Management::serviceEducation()->entityTermById( $Id );

        $Error = false;
        if (isset( $Name ) && empty( $Name )) {
            $View->setError( 'Name', 'Bitte geben Sie einen Namen für das Schuljahr an' );
            $Error = true;
        }
        if (isset( $Name ) && !empty( $Name )) {
            if ($tblTerm->getName() != $Name && $this->entityTermByName( $Name )) {
                $View->setError( 'Name', 'Bitte geben Sie einen eindeutigen Namen für das Schuljahr ein' );
                $Error = true;
            }
        }
        if (isset( $FirstTerm['DateFrom'] ) && empty( $FirstTerm['DateFrom'] )) {
            $View->setError( 'FirstTerm[DateFrom]', 'Bitte geben Sie ein Start-Datum an' );
            $Error = true;
        }
        if (isset( $FirstTerm['DateTo'] ) && empty( $FirstTerm['DateTo'] )) {
            $View->setError( 'FirstTerm[DateTo]', 'Bitte geben Sie ein Ende-Datum an' );
            $Error = true;
        }
        if (isset( $SecondTerm['DateFrom'] ) && empty( $SecondTerm['DateFrom'] )) {
            $View->setError( 'SecondTerm[DateFrom]', 'Bitte geben Sie ein Start-Datum an' );
            $Error = true;
        }
        if (isset( $SecondTerm['DateTo'] ) && empty( $SecondTerm['DateTo'] )) {
            $View->setError( 'SecondTerm[DateTo]', 'Bitte geben Sie ein Ende-Datum an' );
            $Error = true;
        }
        if (isset( $Course ) && !empty( $Course )) {
            if (!Management::serviceCourse()->entityCourseById( $Course )) {
                $View->setError( 'Course', 'Bitte wählen Sie einen verfügbaren Bildungsgang' );
                $Error = true;
            }
        }

        if ($Error) {
            return $View;
        } else {
            $this->actionChangeTerm( $tblTerm, $Name, $FirstTerm['DateFrom'], $FirstTerm['DateTo'],
                $SecondTerm['DateFrom'],
                $SecondTerm['DateTo'], Management::serviceCourse()->entityCourseById( $Course ) );
            return new Redirect( '/Sphere/Management/Period/SchoolYear', 0 );
        }
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblTerm
     */
    public function entityTermById( $Id )
    {

        return parent::entityTermById( $Id );
    }

    /**
     * @param AbstractType $View
     * @param array        $Subject
     *
     * @return AbstractType|\KREDA\Sphere\Client\Frontend\Redirect
     */
    public function executeCreateSubject( AbstractType &$View, $Subject )
    {

        if (null === $Subject) {
            return $View;
        }

        $Error = false;
        if (isset( $Subject['Acronym'] ) && empty( $Subject['Acronym'] )) {
            $View->setError( 'Subject[Acronym]', 'Bitte geben Sie ein Kürzel an' );
            $Error = true;
        }
        if (isset( $Subject['Acronym'] ) && !empty( $Subject['Acronym'] )) {
            if ($this->entitySubjectByAcronym( $Subject['Acronym'] )) {
                $View->setError( 'Subject[Acronym]', 'Bitte geben Sie ein eindeutiges Kürzel ein' );
                $Error = true;
            }
        }
        if (isset( $Subject['Name'] ) && empty( $Subject['Name'] )) {
            $View->setError( 'Subject[Name]', 'Bitte geben Sie einen Namen an' );
            $Error = true;
        }

        if ($Error) {
            return $View;
        } else {
            $this->actionCreateSubject( $Subject['Acronym'], $Subject['Name'] );
            return new Redirect( '/Sphere/Management/Education/Subject', 0 );
        }
    }

    /**
     * @param string $Acronym
     *
     * @return bool|TblSubject
     */
    public function entitySubjectByAcronym( $Acronym )
    {

        return parent::entitySubjectByAcronym( $Acronym );
    }

    /**
     * @param AbstractType $View
     * @param array        $Level
     *
     * @return AbstractType|\KREDA\Sphere\Client\Frontend\Redirect
     */
    public function executeCreateLevel( AbstractType &$View, $Level )
    {

        if (null === $Level) {
            return $View;
        }

        $Error = false;
        if (isset( $Level['Name'] ) && empty( $Level['Name'] )) {
            $View->setError( 'Level[Name]', 'Bitte geben Sie einen Namen für die Klassenstufe an' );
            $Error = true;
        }
        if (isset( $Level['Name'] ) && !empty( $Level['Name'] )) {
            if ($this->entityLevelByName( $Level['Name'] )) {
                $View->setError( 'Level[Name]', 'Bitte geben Sie einen eindeutigen Namen für die Klassenstufe ein' );
                $Error = true;
            }
        }

        if ($Error) {
            return $View;
        } else {
            $this->actionCreateLevel( $Level['Name'], $Level['Description'] );
            return new Success( 'Die Klassenstufe wurde erfolgreich angelegt' )
            .new Redirect( '/Sphere/Management/Education/Group', 0 );
        }
    }

    /**
     * @param AbstractType $View
     * @param array        $Group
     *
     * @return AbstractType|\KREDA\Sphere\Client\Frontend\Redirect
     */
    public function executeCreateGroup( AbstractType &$View, $Group )
    {

        if (null === $Group) {
            return $View;
        }

        $Error = false;
        if (isset( $Group['Name'] ) && empty( $Group['Name'] )) {
            $View->setError( 'Group[Name]', 'Bitte geben Sie einen Namen für die Klassengruppe an' );
            $Error = true;
        }
        if (isset( $Group['Name'] ) && !empty( $Group['Name'] )) {
            if ($this->entityGroupByName( $Group['Name'] )) {
                $View->setError( 'Group[Name]', 'Bitte geben Sie einen eindeutigen Namen für die Klassengruppe ein' );
                $Error = true;
            }
        }

        if ($Error) {
            return $View;
        } else {
            $this->actionCreateGroup( $Group['Name'], $Group['Description'] );
            return new Success( 'Die Klassengruppe wurde erfolgreich angelegt' )
            .new Redirect( '/Sphere/Management/Education/Group', 0 );
        }
    }

    /**
     * @return bool|TblSubjectGroup[]
     */
    public function entitySubjectGroupAll()
    {

        return parent::entitySubjectGroupAll();
    }

}
