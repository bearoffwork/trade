<?php

namespace App\Database;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar;
use Illuminate\Support\Fluent;

class PatchedSQLiteGrammar extends SQLiteGrammar
{
    public function __construct()
    {
        $this->modifiers[] = 'Check';
    }

    public function modifyCheck(Blueprint $blueprint, Fluent $column): ?string
    {
        if (!is_null($check = $column->check)) {
            return " check ({$this->getValue($check)})";
        }

        return null;
    }
}
