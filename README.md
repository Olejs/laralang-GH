# Laravel Localization Manager

This package allows you to import, load and manage all your localization inside your database. No configuration is needed out-of-the-box. Just follow the installation steps, and get started right away!
You can even mix using language files and the database. If a translation is present in both a file and the database, the database version will be returned.

This package is based on [Laravel 5 Translation Manager](https://github.com/barryvdh/laravel-translation-manager) and [Laravel Translation Loader](https://github.com/spatie/laravel-translation-loader)

## Installation

You can install the package via composer:

``` bash
composer require "thomasvvugt/laralang":"*"
```

In `config/app.php` you should add, but not replace, the Service Provider.

```php
'providers' => [
  ...
  Thomasvvugt\LaraLang\TranslationServiceProvider::class,
]
``` 

You must publish and run the migrations to create the LaraLang tables:

```bash
php artisan vendor:publish --provider="Thomasvvugt\LaraLang\TranslationServiceProvider" --tag="migrations"
php artisan migrate
```

You could also publish the configuration file by this command. Feel free to edit this package to your needs.

```bash
php artisan vendor:publish --provider="Thomasvvugt\LaraLang\TranslationServiceProvider" --tag="config"
```


## Usage

You can easily add your translations to the database by importing them using a simple command. By default, translations that do already exist in the database will not be replaced. The --replace or -R option, replaces all translations in the database.

```bash
php artisan laralang:import (-R)
```

To add a language to the database, simply use something like this.

```php
use Thomasvvugt\LaraLang\Language;

$language = new Language;
$language->slug = "nl";
$language->save();
```

And maybe add a translation to that language?

```php
use Thomasvvugt\LaraLang\Translation;

$translation = new Translation;
$translation->language_id = $language->id;
$translation->group = "validation";
$translation->key = "required";
$translation->value = "Dit is een verplicht veld.";
$translation->save();
```

See, it works!

```php
app()->setLocale('en');
trans('validation.required'); // returns 'This is a required field'

app()->setLocale('nl');
trans('validation.required'); // returns 'Dit is een verplicht veld'
```

## Configuration

```php
return [

    /*
     * Translations and languages will be stored in the database, if you want
     * to have custom table names for these models, you sure can.
     */
    'tables' => [
        'translations' => 'translations',
        'languages' => 'languages'
    ],

    /**
     * Exclude specific groups from Laravel Translation Manager. 
     * This is useful if, for example, you want to avoid editing the official Laravel language files.
     *
     * @type array
     *
     *  array(
     *      'pagination',
     *      'reminders',
     *      'validation',
     *  )
     */
    'exclude_groups' => array(),

    /*
     * Translations will be fetched by these loaders, make sure they implement
     * the Thomasvvugt\LaraLang\TranslationLoaders\TranslationLoader-interface.
     */
    'translation_loaders' => [
        Thomasvvugt\LaraLang\TranslationLoaders\DatabaseLoader::class,
    ],

    /*
     * Translation line, and their languages are stored in these models. You
     * are free to edit these and make your own. Do make sure that you extend
     * these models.
     */
    'models' => [
    	'translation' => Thomasvvugt\LaraLang\Translation::class,
    	'language' => Thomasvvugt\LaraLang\Language::class
    ]
];
```


## Translation providers and models

This package comes with a translation provider that can read translations from the database using models. Feel free to make you own translation provider, for example a yaml-file or a csv-file.
A translation provider can be any class that implements the `Thomasvvugt\LaraLang\TranslationLoaders\TranslationLoader`-interface. It contains only one method:

```php 
namespace Thomasvvugt\LaraLang\TranslationLoaders;

interface TranslationLoader
{
    /**
     * Returns all translations for the given locale and group.
     *
     * @param string $locale
     * @param string $group
     *
     * @return array
     */
    public function loadTranslations(string $locale, string $group): array;
}
```

Also, feel free to edit your Translation and Language models, but make sure to implement them.
You can edit the Translation Loader and Models inside the configuration file.

## Credits

- [Laravel 5 Translation Manager](https://github.com/barryvdh/laravel-translation-manager)
- [Laravel Translation Loader](https://github.com/spatie/laravel-translation-loader)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.