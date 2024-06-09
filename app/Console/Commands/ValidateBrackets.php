<?php

namespace App\Console\Commands;

use App\Functions\BracketValidator;
use Illuminate\Console\Command;

class ValidateBrackets extends Command
{
    protected $signature = 'validate:brackets {string}';
    protected $description = 'Validate the order of brackets in a string';

    public function handle()
    {
        $string = $this->argument('string');
        $isValid = BracketValidator::isValid($string);
        if ($isValid) {
            $this->info('A ordem dos parênteses é válida!');
        } else {
            $this->error('A ordem dos parênteses não é válida!');
        }
    }
}
