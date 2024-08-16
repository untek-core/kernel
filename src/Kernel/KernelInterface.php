<?php

namespace Untek\Core\Kernel\Kernel;

use Psr\Container\ContainerInterface;
use Untek\Core\Instance\Libs\Resolvers\ArgumentMetadataResolver;

\Untek\Core\Code\Helpers\DeprecateHelper::hardThrow();

interface KernelInterface
{

    public function boot(): void;

    public function getContainer(): ContainerInterface;

    public function terminate(): void;
}
