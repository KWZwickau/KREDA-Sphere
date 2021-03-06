<?php
namespace Satooshi\Component\System;

/**
 * System command.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class SystemCommand
{

    /**
     * Command name or path.
     *
     * @var string
     */
    protected $commandPath;

    // API

    /**
     * Execute command.
     *
     * @return array
     */
    public function execute()
    {

        $command = $this->createCommand();

        return $this->executeCommand( $command );
    }

    // internal method

    /**
     * Create command.
     *
     * @param string $args Command arguments.
     *
     * @return string
     */
    protected function createCommand( $args = null )
    {

        if ($args === null) {
            return $this->commandPath;
        }

        // escapeshellarg($args) ?
        return sprintf( '%s %s', $this->commandPath, $args );
    }

    /**
     * Execute command.
     *
     * @param string $command
     *
     * @return array
     *
     * @throws \RuntimeException
     */
    protected function executeCommand( $command )
    {

        exec( $command, $result, $returnValue );

        if ($returnValue === 0) {
            return $result;
        }

        throw new \RuntimeException( sprintf( 'Failed to execute command: %s', $command ), $returnValue );
    }

    // accessor

    /**
     * Return command path.
     *
     * @return string
     */
    public function getCommandPath()
    {

        return $this->commandPath;
    }

    /**
     * Set command path.
     *
     * @param string $commandPath Command name or path.
     *
     * @return void
     */
    public function setCommandPath( $commandPath )
    {

        $this->commandPath = $commandPath;
    }
}
