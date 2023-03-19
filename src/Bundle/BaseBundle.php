<?php

namespace Untek\Core\Kernel\Bundle;

use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Core\Container\Traits\ContainerAttributeTrait;
use Untek\Core\Instance\Libs\Resolvers\ArgumentMetadataResolver;
use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;

abstract class BaseBundle implements BundleInterface
{

    use ContainerAttributeTrait;

    public function dependecies(): array {
        return [];
    }
    
    protected function isCli(): bool
    {
        return php_sapi_name() == 'cli' && $this->container->has(CommandConfiguratorInterface::class);
    }

    protected function registerConsoleCommand($command) {
        $commandConfigurator = $this->container->get(CommandConfiguratorInterface::class);
        if(is_string($command)) {
            $commandConfigurator->registerCommandClass($command);
        } elseif (is_object($command)) {
            $commandConfigurator->registerCommandInstance($command);
        }
    }

    protected function getContainerConfigurator(): ContainerConfiguratorInterface
    {
        return $this->container->get(ContainerConfiguratorInterface::class);
    }

    protected function configureFromPhpFile(string $fileName, array $availableArguments = []): void
    {
        if(!file_exists($fileName)) {
            throw new \Exception('Config file not exists!');
        }
        $configuratorCallback = include $fileName;
        $this->configureFromCallable($configuratorCallback, $availableArguments);
    }

    protected function configureFromMethod(string $method, array $availableArguments = []): void
    {
        $configuratorCallback = [$this, $method];
        $this->configureFromCallable($configuratorCallback, $availableArguments);
    }

    protected function configureFromCallable($configuratorCallback, array $availableArguments = []): void
    {
        $argumentMetadataResolver = new ArgumentMetadataResolver($this->getContainer());
        if (is_callable($configuratorCallback)) {
            $parameters = $argumentMetadataResolver->resolve($configuratorCallback, $availableArguments);
            call_user_func_array($configuratorCallback, $parameters);
        }
    }
}
