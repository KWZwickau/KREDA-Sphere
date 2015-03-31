<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Service\Education\Entity\TblCategory;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblLevel;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubjectGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblTerm;
use KREDA\Sphere\Application\Management\Service\Education\EntityAction;
use KREDA\Sphere\Client\Frontend\Redirect;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Common\Frontend\Form\AbstractForm;

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
        $this->actionCreateSubject( 'DaZ', 'Deutsch als Zweitsprache' );
        $this->actionCreateSubject( 'En', 'Englisch' );
        $this->actionCreateSubject( 'Eth', 'Ethik' );
        $this->actionCreateSubject( 'Fr', 'Französisch' );
        $this->actionCreateSubject( 'FöMa', 'Förderunterricht Mathematik' );
        $this->actionCreateSubject( 'GK', 'Gemeinschaftskunde/Rechtserziehung' );
        $this->actionCreateSubject( 'Geo', 'Geographie' );
        $this->actionCreateSubject( 'Ge', 'Geschichte' );
        $this->actionCreateSubject( 'Inf', 'Informatik' );
        $this->actionCreateSubject( 'KL', 'Klassenleiterstunde' );
        $this->actionCreateSubject( 'Ku', 'Kunst' );
        $this->actionCreateSubject( 'Pk', 'Künstlerisches Profil' );
        $this->actionCreateSubject( 'La', 'Latein' );
        $this->actionCreateSubject( 'Ma', 'Mathematik' );
        $this->actionCreateSubject( 'Mu', 'Musik' );
        $this->actionCreateSubject( 'Nk', 'Neigungskurs' );
        $this->actionCreateSubject( 'Ph', 'Physik' );
        $this->actionCreateSubject( 'Pg', 'Profil Geisteswissensch.' );
        $this->actionCreateSubject( 'Pn', 'Profil Naturwissenschaften' );
        $this->actionCreateSubject( 'ReE', 'Religion evangelisch' );
        $this->actionCreateSubject( 'Ru', 'Russisch' );
        $this->actionCreateSubject( 'Sor', 'Sorbisch' );
        $this->actionCreateSubject( 'Spo', 'Sport' );
        $this->actionCreateSubject( 'TuN', 'Technik und Natur' );
        $this->actionCreateSubject( 'TC', 'Technik/Computer' );
        $this->actionCreateSubject( 'VK', 'Vertiefungskurs' );
        $this->actionCreateSubject( 'WTH', 'Wirtschaft-Technik-Haushalt/Soziales' );
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
     * @return bool|TblSubject[]
     */
    public function entitySubjectAll()
    {

        return parent::entitySubjectAll();
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
     * @param AbstractForm $View
     * @param array        $Term
     *
     * @return AbstractForm|Redirect
     */
    public function executeCreateTerm( AbstractForm &$View, $Term )
    {

        if (null === $Term) {
            return $View;
        }

        $Error = false;
        if (isset( $Term['Name'] ) && empty( $Term['Name'] )) {
            $View->setError( 'Term[Name]', 'Bitte geben Sie einen Namen für das Halbjahr an' );
            $Error = true;
        }
        if (isset( $Term['Name'] ) && !empty( $Term['Name'] )) {
            if ($this->entityTermByName( $Term['Name'] )) {
                $View->setError( 'Term[Name]', 'Bitte geben Sie einen eindeutigen Namen für das Halbjahr ein' );
                $Error = true;
            }
        }
        if (isset( $Term['DateFrom'] ) && empty( $Term['DateFrom'] )) {
            $View->setError( 'Term[DateFrom]', 'Bitte geben Sie ein Start-Datum an' );
            $Error = true;
        }
        if (isset( $Term['DateTo'] ) && empty( $Term['DateTo'] )) {
            $View->setError( 'Term[DateTo]', 'Bitte geben Sie ein Ende-Datum an' );
            $Error = true;
        }

        if ($Error) {
            return $View;
        } else {
            $this->actionCreateTerm( $Term['Name'], $Term['DateFrom'], $Term['DateTo'] );
            return new Redirect( '/Sphere/Management/Education/Setup', 0 );
        }
    }

    /**
     * @param AbstractForm $View
     * @param array        $Subject
     *
     * @return AbstractForm|\KREDA\Sphere\Client\Frontend\Redirect
     */
    public function executeCreateSubject( AbstractForm &$View, $Subject )
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
            return new Redirect( '/Sphere/Management/Education/Setup', 0 );
        }
    }

    /**
     * @param AbstractForm $View
     * @param array        $Level
     *
     * @return AbstractForm|\KREDA\Sphere\Client\Frontend\Redirect
     */
    public function executeCreateLevel( AbstractForm &$View, $Level )
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
            return new Redirect( '/Sphere/Management/Education/Setup', 0 );
        }
    }

    /**
     * @param AbstractForm $View
     * @param array        $Group
     *
     * @return AbstractForm|\KREDA\Sphere\Client\Frontend\Redirect
     */
    public function executeCreateGroup( AbstractForm &$View, $Group )
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
            return new Redirect( '/Sphere/Management/Education/Setup', 0 );
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
