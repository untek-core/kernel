<?php

namespace Untek\Core\Kernel\Bundle;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Core\Instance\Libs\Resolvers\ArgumentMetadataResolver;
use Untek\Framework\Console\Symfony4\Interfaces\CommandConfiguratorInterface;

abstract class BaseBundle  implements BundleInterface
{
    public function build(ContainerBuilder $containerBuilder)
    {
    }

    protected function importServices(ContainerBuilder $containerBuilder, mixed $resource)
    {
        $fileLocator = new FileLocator(__DIR__);
        $loader = new PhpFileLoader($containerBuilder, $fileLocator);
        $loader->load($resource);
    }

    /*protected function load(ContainerBuilder $containerBuilder, mixed $resource)
    {
        $fileLocator = new FileLocator(__DIR__);
        $loader = new PhpFileLoader($containerBuilder, $fileLocator);
        $loader->load($resource);
    }*/

    /*protected function configureContainerServices(string $configFile): void
    {
        $containerBuilder = $this->container->get(ContainerBuilder::class);
        $fileLocator = new FileLocator(__DIR__);
        $loader = new PhpFileLoader($containerBuilder, $fileLocator);
        $loader->load($configFile);
    }*/

    /*protected function configureFromPhpFile(string $fileName, array $availableArguments = []): void
    {
        if (!file_exists($fileName)) {
            throw new \Exception('Config file not exists!');
        }
        $configuratorCallback = include $fileName;
        $this->configureFromCallable($configuratorCallback, $availableArguments);
    }*/

    /*protected function configureFromMethod(string $method, array $availableArguments = []): void
    {
        $configuratorCallback = [$this, $method];
        $this->configureFromCallable($configuratorCallback, $availableArguments);
    }*/

    /*protected function configureFromCallable($configuratorCallback, array $availableArguments = []): void
    {
        $argumentMetadataResolver = new ArgumentMetadataResolver($this->getContainer());
        if (is_callable($configuratorCallback)) {
            $parameters = $argumentMetadataResolver->resolve($configuratorCallback, $availableArguments);
            call_user_func_array($configuratorCallback, $parameters);
        }
    }*/
}
