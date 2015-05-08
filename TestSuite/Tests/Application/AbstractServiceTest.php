<?php
namespace KREDA\TestSuite\Tests\Application;

/**
 * Class AbstractServiceTest
 *
 * @package KREDA\TestSuite\Tests\Application
 */
abstract class AbstractServiceTest extends \PHPUnit_Framework_TestCase implements IAbstractTest
{

    public function testAbstractService()
    {

        /** @var \KREDA\Sphere\Common\AbstractService $MockService */
        $MockService = $this->getMockForAbstractClass( 'KREDA\Sphere\Common\AbstractService' );
        $this->assertInstanceOf( 'KREDA\Sphere\Common\AbstractExtension', $MockService );
        $this->assertInstanceOf( 'KREDA\Sphere\IServiceInterface', $MockService );
    }

    /**
     * @param string $Service
     */
    protected function checkMethodName( $Service )
    {

        $Name = '__construct|get(Api|ConsumerSuffix|DatabaseHandler)|setDatabaseHandler|setupDatabase(Schema|Content)';
        $Prefix = 'entity|checkIs|getTable|execute(Create|Destroy|Add|Remove|Change|Action)|action(Create|Destroy|Add|Remove|Change|Action)|extension|list|table|count';
        $this->checkNamePattern( 'KREDA\Sphere\Application'.$Service, '!^(('.$Name.')|('.$Prefix.')[a-zA-Z]+)$!',
            \ReflectionMethod::IS_PUBLIC );
    }

    /**
     * @param string $Class
     * @param string $Pattern
     * @param int    $Realm
     */
    private function checkNamePattern( $Class, $Pattern, $Realm = \ReflectionMethod::IS_PUBLIC )
    {

        $Class = new \ReflectionClass( $Class );
        $MethodList = $Class->getMethods( $Realm );
        /** @var \ReflectionMethod $Method */
        foreach ($MethodList as $Method) {
            $this->assertEquals( 1, preg_match( $Pattern, $Method->getShortName(), $Result ),
                $Class->getName().'::'.$Method->getShortName()."\n".' -> '.$Pattern );
        }
    }
}
