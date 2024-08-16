<?php

namespace Untek\Core\Kernel\Config;

use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\Core\Instance\Libs\Resolvers\ArgumentMetadataResolver;

DeprecateHelper::hardThrow();

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
