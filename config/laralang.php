<?php
return [

    /*
     * Translations and languages will be stored in the database, if you want
     * to have custom table names for these models, you sure can.
     */
    'tables' => [
        'translations' => 'translations',
        'groups' => 'groups',
        'namespaces' => 'namespaces',
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
      'group' => Thomasvvugt\LaraLang\Group::class,
      'namespace' => Thomasvvugt\LaraLang\Namespc::class,
    	'language' => Thomasvvugt\LaraLang\Language::class
    ]
];