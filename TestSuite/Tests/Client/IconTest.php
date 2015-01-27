<?php
namespace KREDA\TestSuite\Tests\Client;

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

        $Icon = new BookIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new BriefcaseIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new BuildingIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new CertificateIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new ClusterIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new CogIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new CogWheelsIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new ConversationIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new DatabaseIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new EducationIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new EyeOpenIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new FlashIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new GroupIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new HomeIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new LockIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new MapMarkerIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new MoneyIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new MoneyEuroIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new NameplateIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new OffIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new PersonIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new PersonKeyIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new QuestionIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new RepeatIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new ServerIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new ShareIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );

        $Icon = new StatisticIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new TagIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new TagListIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new TaskIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new TileBigIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new TileListIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new TileSmallIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new TimeIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new WarningIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new WrenchIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );

        $Icon = new YubiKeyIcon();
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\Repository\AbstractIcon', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\Parameter\AbstractParameter', $Icon );
        $this->assertInstanceOf( 'KREDA\Sphere\Client\Component\IParameterInterface', $Icon );
        $this->assertInternalType( 'string', $Icon->getValue() );
        $this->assertInternalType( 'string', $Icon->__toString() );
    }

}
