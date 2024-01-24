<?php

namespace Untek\Core\Kernel\Kernel;

use Forecast\Map\Modules\Mq\Infrastructure\Enums\EventEnum;
use LogicException;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Untek\Core\Kernel\Events\KernelTerminatedEvent;

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

    public function terminate(): void
    {
        $dispatcher = $this->getEventDispatcher();
        if ($dispatcher) {
            $dispatcher->dispatch(new KernelTerminatedEvent(), EventEnum::KERNEL_TERMINATED);
        }
    }

    protected function getEventDispatcher(): ?EventDispatcherInterface
    {
        if ($this->getContainer()->has(EventDispatcherInterface::class)) {
            /** @var EventDispatcherInterface $dispatcher */
            $dispatcher = $this->getContainer()->get(EventDispatcherInterface::class);
            return $dispatcher;
        }
        return null;
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

    protected function initializeShellVerbosity(): void
    {
        if ($this->debug && !isset($_ENV['SHELL_VERBOSITY']) && !isset($_SERVER['SHELL_VERBOSITY'])) {
            putenv('SHELL_VERBOSITY=3');
            $_ENV['SHELL_VERBOSITY'] = 3;
            $_SERVER['SHELL_VERBOSITY'] = 3;
        }
    }
}
