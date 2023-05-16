<?php

namespace Untek\Core\Kernel\Kernel;

use Psr\Container\ContainerInterface;
use Untek\Core\Instance\Libs\Resolvers\ArgumentMetadataResolver;

interface KernelInterface
{

    public function boot(): void;

    public function getContainer(): ContainerInterface;
}
