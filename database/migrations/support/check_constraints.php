<?php

use Illuminate\Support\Facades\Schema;

if (! function_exists('g3_check_constraints')) {
    /**
     * SQLite 3.40 cannot ADD CONSTRAINT. Inject the same checks into CREATE TABLE.
     * MySQL keeps ALTER TABLE ADD CONSTRAINT.
     *
     * @param  array<string, array<string, string>>  $checks
     */
    function g3_check_constraints(array $checks, Closure $schema): void
    {
        $connection = Schema::getConnection();

        if ($connection->getDriverName() === 'sqlite') {
            $connection->beforeExecuting(function (string &$query) use ($checks): void {
                foreach ($checks as $table => $constraints) {
                    if (! str_starts_with($query, 'create table "'.$table.'"')) {
                        continue;
                    }

                    $suffix = '';

                    foreach ($constraints as $name => $expression) {
                        $suffix .= ', constraint "'.$name.'" check ('.$expression.')';
                    }

                    $query = preg_replace('/\)\s*$/', $suffix.')', $query, 1) ?? $query;
                }
            });

            $schema();

            return;
        }

        $schema();

        foreach ($checks as $table => $constraints) {
            foreach ($constraints as $name => $expression) {
                $connection->statement(
                    'ALTER TABLE '.$table.' ADD CONSTRAINT '.$name.' CHECK ('.$expression.')',
                );
            }
        }
    }
}
