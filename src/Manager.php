<?php

namespace Thomasvvugt\LaraLang;

use Illuminate\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Thomasvvugt\LaraLang\Exceptions\InvalidConfiguration;
use Thomasvvugt\LaraLang\ImportLoaderManager;

class Manager
{

  private $importLoader;

  public function __construct(Application $app, Filesystem $files, Dispatcher $events)
  {
    $this->app = $app;
    $this->files = $files;
    $this->events = $events;
    $this->config = $app['config']['laralang'];
    $this->importLoader = new ImportLoaderManager($app['files'], $app['path.lang']);
  }

  public function importTranslations($replace = false)
  {
    $counter = 0;
    foreach ($this->files->directories($this->app['path.lang']) as $langPath) {
      $locale = basename($langPath);
      foreach ($this->files->allfiles($langPath) as $file) {
        $info = pathinfo($file);
        $group = $info['filename'];
        if (in_array($group, $this->config['exclude_groups'])) {
          continue;
        }
        $translations = $this->importLoader->load($locale, $group);
        if ($translations && is_array($translations)) {
          foreach (array_dot($translations) as $key => $value) {
            $importedTranslation = $this->importTranslation($key, $value, $locale, $group, $replace);
            $counter += $importedTranslation ? 1 : 0;
          }
        }
      }
    }
    foreach ($this->files->files($this->app['path.lang']) as $jsonTranslationFile) {
      if (strpos($jsonTranslationFile, '.json') === false) {
        continue;
      }
      $locale = basename($jsonTranslationFile, '.json');
      $group = self::JSON_GROUP;
      $translations = $this->importLoader->load($locale, '*', '*'); // Retrieves JSON entries of the given locale only
      if ($translations && is_array($translations)) {
        foreach ($translations as $key => $value) {
          $importedTranslation = $this->importTranslation($key, $value, $locale, $group, $replace);
          $counter += $importedTranslation ? 1 : 0;
        }
      }
    }
    return $counter;
  }

  public function importTranslation($key, $value, $locale, $group, $replace = false) {
    // process only string values
    if (is_array($value)) {
      return false;
    }
    $value = (string)$value;

    $translation_model = config('laralang.models.translation');
    if (! is_a(new $translation_model, Translation::class)) {
      throw InvalidConfiguration::invalidModel($translation_model);
    }
    $group_model = config('laralang.models.group');
    if (! is_a(new $group_model, Group::class)) {
      throw InvalidConfiguration::invalidModel($group_model);
    }
    $namespace_model = config('laralang.models.namespace');
    if (! is_a(new $namespace_model, Namespc::class)) {
      throw InvalidConfiguration::invalidModel($namespace_model);
    }
    $language_model = config('laralang.models.language');
    if (! is_a(new $language_model, Language::class)) {
      throw InvalidConfiguration::invalidModel($language_model);
    }

    $language = $language_model::getFromName($locale);
    if(!isset($language)) {
      $language = new $language_model;
      $language->name = $locale;
      $language->save();
    }

    $group_thing = $group_model::getFromName($group);
    if(!isset($group_thing->id)) {
      $group_thing = new $group_model;
      $group_thing->name = $group;
      $group_thing->save();
    }

    $group_id = null;

    if(isset($group_thing->id)) {
      $group_id = $group_thing->id;
    }

    $translation = $translation_model::firstOrNew(array(
      'language_id' => $language->id,
      'group_id' => $group_id,
      'key' => $key
    ));

    // Only replace when empty, or explicitly told so
    if ($replace || !$translation->value) {
      $translation->value = $value;
    }
    $translation->save();
    return true;
  }
}