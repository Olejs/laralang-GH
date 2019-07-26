<?php

namespace Thomasvvugt\LaraLang;

use Illuminate\Translation\FileLoader;
use Thomasvvugt\LaraLang\TranslationLoaders\TranslationLoader;

class ImportLoaderManager extends FileLoader
{
    /**
     * Load the messages for the given locale.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     *
     * @return array
     */
    public function load($locale, $group, $namespace = null): array
    {
        return parent::load($locale, $group, $namespace);
    }

}