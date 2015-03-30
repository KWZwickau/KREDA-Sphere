<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Service\Education\Entity\TblCategory;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblLevel;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubjectGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblTerm;
use KREDA\Sphere\Application\Management\Service\Education\EntityAction;
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

}
