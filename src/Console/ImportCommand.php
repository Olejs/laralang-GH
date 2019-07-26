<?php

namespace Thomasvvugt\LaraLang\Console;

use Illuminate\Console\Command;
use Thomasvvugt\LaraLang\Manager;
use Symfony\Component\Console\Input\InputOption;

class ImportCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'laralang:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Laravel language files into the LaraLang tables.';

    /** @var \Thomasvvugt\LaraLang\Manager */
    protected $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $replace = $this->option('replace');
        $counter = $this->manager->importTranslations($replace);
        $this->info('Successfully imported '.$counter.' translations into the database!');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['replace', 'R', InputOption::VALUE_NONE, 'Replace existing entries in the database.'],
        ];
    }
}