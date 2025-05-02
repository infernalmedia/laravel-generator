<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPhpSets(php83: true)
    ->withTypeCoverageLevel(20)
    ->withDeadCodeLevel(25)
    ->withCodeQualityLevel(19)
    ->withPreparedSets(codingStyle: true)
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSets([])
    ->withSkip([
        // __DIR__ . '/tests/Concierge/Unit/AvailabilityBuilderForUnitTesting.php',
    ])
    ->withSkip([]);
