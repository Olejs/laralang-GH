<?php

namespace Thomasvvugt\LaraLang;

use Illuminate\Translation\FileLoader;
use Thomasvvugt\LaraLang\TranslationLoaders\TranslationLoader;

class TranslationLoaderManager extends FileLoader
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
        // Load from Database
        return $this->getTranslationsFromTranslationLoaders($locale, $group, $namespace);
    }

    protected function getTranslationsFromTranslationLoaders($locale, $group, $namespace = null)
    {
        return collect(config('laralang.translation_loaders'))
            ->map(function (string $className) {
                return app($className);
            })
            ->mapWithKeys(function (TranslationLoader $translationLoader) use ($locale, $group, $namespace) {
                return $translationLoader->loadTranslations($locale, $group, $namespace);
            })
            ->toArray();
    }
}