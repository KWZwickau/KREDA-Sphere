<?php
namespace Satooshi\Bundle\CoverallsBundle\Console;

use Satooshi\ProjectTestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * @covers Satooshi\Bundle\CoverallsBundle\Console\Application
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class ApplicationTest extends ProjectTestCase
{

    /**
     * @test
     */
    public function shouldExecuteCoverallsV1JobsCommand()
    {

        $this->makeProjectDir( null, $this->logsDir );
        $this->dumpCloverXml();

        $app = new Application( $this->rootDir, 'Coveralls API client for PHP', '1.0.0' );
        $app->setAutoExit( false ); // avoid to call exit() in Application

        // run
        $_SERVER['TRAVIS'] = true;
        $_SERVER['TRAVIS_JOB_ID'] = 'application_test';

        $tester = new ApplicationTester( $app );
        $actual = $tester->run(
            array(
                '--dry-run' => true,
                '--config'  => 'coveralls.yml',
            )
        );

        $this->assertEquals( 0, $actual );
    }

    protected function dumpCloverXml()
    {

        file_put_contents( $this->cloverXmlPath, $this->getCloverXml() );
    }

    protected function getCloverXml()
    {

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<coverage generated="1365848893">
  <project timestamp="1365848893">
    <file name="%s/test.php">
      <class name="TestFile" namespace="global">
        <metrics methods="1" coveredmethods="0" conditionals="0" coveredconditionals="0" statements="1" coveredstatements="0" elements="2" coveredelements="0"/>
      </class>
      <line num="5" type="method" name="__construct" crap="1" count="0"/>
      <line num="7" type="stmt" count="0"/>
    </file>
    <package name="Hoge">
      <file name="%s/test2.php">
        <class name="TestFile" namespace="Hoge">
          <metrics methods="1" coveredmethods="0" conditionals="0" coveredconditionals="0" statements="1" coveredstatements="0" elements="2" coveredelements="0"/>
        </class>
        <line num="6" type="method" name="__construct" crap="1" count="0"/>
        <line num="8" type="stmt" count="0"/>
      </file>
    </package>
  </project>
</coverage>
XML;
        return sprintf( $xml, $this->srcDir, $this->srcDir );
    }

    protected function setUp()
    {

        $this->projectDir = realpath( __DIR__.'/../../../..' );

        $this->setUpDir( $this->projectDir );
    }

    protected function tearDown()
    {

        $this->rmFile( $this->cloverXmlPath );
        $this->rmFile( $this->jsonPath );
        $this->rmDir( $this->logsDir );
        $this->rmDir( $this->buildDir );
    }
}