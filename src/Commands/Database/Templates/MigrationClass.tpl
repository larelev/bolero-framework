<?php

use Doctrine\DBAL\Schema\AbstractSchemaManager;

class Migration_<Version />
{
    public function __construct(private readonly AbstractSchemaManager $schema)
    {
    }

    public function up(): void
    {
        echo __METHOD__ . " executed!" . PHP_EOL;
    }

    public function down(): void
    {
        echo __METHOD__ . " executed!" . PHP_EOL;
    }
};