<?php

namespace Untek\Core\Kernel\Config;

use Untek\Core\Instance\Libs\Resolvers\ArgumentMetadataResolver;

class FileConfigLoader
{

    public function __construct(
        private ArgumentMetadataResolver $argumentMetadataResolver,
        private CallableConfigLoader $callableConfigLoader
    ) {
    }

    public function boot(string $file, array $availableArguments = []): void
    {
        $configuratorCallback = @include $file;
        if($configuratorCallback) {
            $this->callableConfigLoader->boot($configuratorCallback, $availableArguments);
        }
    }
}
