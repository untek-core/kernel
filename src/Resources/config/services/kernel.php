<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Untek\Core\Instance\Libs\Resolvers\ArgumentMetadataResolver;
use Untek\Core\Kernel\Config\CallableConfigLoader;
use Untek\Core\Kernel\Config\FileConfigLoader;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();
    $parameters = $configurator->parameters();

    $services->set(FileConfigLoader::class, FileConfigLoader::class)
        ->args(
            [
                service(ArgumentMetadataResolver::class),
                service(CallableConfigLoader::class),
            ]
        );
};