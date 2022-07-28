<?php

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\NamedFilter;
use ComposerUnused\ComposerUnused\Configuration\PatternFilter;
use Webmozart\Glob\Glob;

return static fn (Configuration $config): Configuration => $config
    ->addNamedFilter(NamedFilter::fromString('components/jquery'))
    ->addNamedFilter(NamedFilter::fromString('tatter/assets'))
    ->addNamedFilter(NamedFilter::fromString('twbs/bootstrap'))
    ->setAdditionalFilesFor('codeigniter4/framework', [
        ...Glob::glob(__DIR__ . '/vendor/codeigniter4/framework/system/Helpers/*.php'),
    ]);
