<?php

namespace Untek\Core\Kernel\Kernel;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Core\Kernel\Kernel\KernelInterface;
use LogicException;

abstract class BaseKernel implements KernelInterface
{

    private ContainerInterface $container;
    protected bool $debug = false;
    protected float $startTime;

    /**
     * @return ContainerInterface
     * @throws LogicException
     */
    public function getContainer(): ContainerInterface
    {
        if (!isset($this->container)) {
            throw new LogicException('Cannot retrieve the container from a non-booted kernel.');
        }
        return $this->container;
    }

    /**
     * @return ContainerConfiguratorInterface
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function getContainerConfigurator(): ContainerConfiguratorInterface
    {
        return $this
            ->getContainer()
            ->get(ContainerConfiguratorInterface::class);
    }

    protected function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    protected function initializeDebug(): void
    {
        if (!$this->debug) {
            return;
        }
        $this->startTime = microtime(true);
        $this->setErrorVisible($this->debug);
    }

    protected function setErrorVisible(bool $isDebug): void
    {
        $level = $isDebug ? E_ALL : E_PARSE | E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR;
        if ($isDebug) {
            error_reporting($level);
            ini_set('display_errors', '1');
        } else {
            error_reporting($level);
            ini_set('display_errors', '0');
        }
    }

    protected function initializeShellVerbosity()
    {
        if ($this->debug && !isset($_ENV['SHELL_VERBOSITY']) && !isset($_SERVER['SHELL_VERBOSITY'])) {
            putenv('SHELL_VERBOSITY=3');
            $_ENV['SHELL_VERBOSITY'] = 3;
            $_SERVER['SHELL_VERBOSITY'] = 3;
        }
    }
}
