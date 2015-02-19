<?php
namespace KREDA\TestSuite\Tests\Client;

use KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BookIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BriefcaseIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BuildingIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CertificateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ClusterIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\CogWheelsIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DatabaseIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EducationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EyeOpenIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\FlashIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\HistoryIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\HomeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MapMarkerIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MoneyEuroIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MoneyIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\NameplateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\OffIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonKeyIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\QuestionIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RepeatIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ServerIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ShareIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\StatisticIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TagIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TagListIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TaskIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TileBigIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TileListIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TileSmallIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WarningIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WheelChairIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WrenchIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\YubiKeyIcon;

/**
 * Class IconTest
 *
 * @package KREDA\TestSuite\Tests\Client
 */
class IconTest extends \PHPUnit_Framework_TestCase
{

    public function testAbstractIcon()
    {

        /** @var \KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon $MockIcon */
        $MockIcon = $this->getMockForAbstractClass( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon' );

        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $MockIcon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $MockIcon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $MockIcon );
    }

    public function testIcon()
    {

        $this->checkAssertList( new BookIcon() );
        $this->checkAssertList( new BriefcaseIcon() );
        $this->checkAssertList( new BuildingIcon() );
        $this->checkAssertList( new CertificateIcon() );
        $this->checkAssertList( new ClusterIcon() );
        $this->checkAssertList( new CogIcon() );
        $this->checkAssertList( new CogWheelsIcon() );
        $this->checkAssertList( new ConversationIcon() );
        $this->checkAssertList( new DatabaseIcon() );
        $this->checkAssertList( new EducationIcon() );
        $this->checkAssertList( new EyeOpenIcon() );
        $this->checkAssertList( new FlashIcon() );
        $this->checkAssertList( new GroupIcon() );
        $this->checkAssertList( new HistoryIcon() );
        $this->checkAssertList( new HomeIcon() );
        $this->checkAssertList( new LockIcon() );
        $this->checkAssertList( new MapMarkerIcon() );
        $this->checkAssertList( new MoneyIcon() );
        $this->checkAssertList( new MoneyEuroIcon() );
        $this->checkAssertList( new NameplateIcon() );
        $this->checkAssertList( new OffIcon() );
        $this->checkAssertList( new PersonIcon() );
        $this->checkAssertList( new PersonKeyIcon() );
        $this->checkAssertList( new QuestionIcon() );
        $this->checkAssertList( new RepeatIcon() );
        $this->checkAssertList( new ServerIcon() );
        $this->checkAssertList( new ShareIcon() );
        $this->checkAssertList( new StatisticIcon() );
        $this->checkAssertList( new TagIcon() );
        $this->checkAssertList( new TagListIcon() );
        $this->checkAssertList( new TaskIcon() );
        $this->checkAssertList( new TileBigIcon() );
        $this->checkAssertList( new TileListIcon() );
        $this->checkAssertList( new TileSmallIcon() );
        $this->checkAssertList( new TimeIcon() );
        $this->checkAssertList( new WarningIcon() );
        $this->checkAssertList( new WheelChairIcon() );
        $this->checkAssertList( new WrenchIcon() );
        $this->checkAssertList( new YubiKeyIcon() );
    }

    /**
     * @param AbstractIcon $Icon
     */
    private function checkAssertList( AbstractIcon $Icon )
    {

        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );
    }
}
