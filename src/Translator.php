<?php 

namespace Thomasvvugt\LaraLang;

use Illuminate\Translation\Translator as BaseTranslator;
use Illuminate\Events\Dispatcher;

class Translator extends BaseTranslator {
    /** @var  Dispatcher */
    protected $events;

    /**
     * Get the translation for the given key.
     *
     * @param  string  $key
     * @param  array   $replace
     * @param  string  $locale
     * @return string
     */
    public function get($key, array $replace = array(), $locale = null, $fallback = true)
    {
        // Get from database or filesystem
        $result = parent::get($key, $replace, $locale, false);
        if($result === $key)
        {
            // The key for that language did not exist
            // Get from database or filesystem with fallback
            $result = parent::get($key, $replace, $locale, $fallback);
        }
        return $result;
    }

}