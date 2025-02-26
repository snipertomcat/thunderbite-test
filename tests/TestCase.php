<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate');
        $this->artisan('db:seed');
    }

    protected function tearDown(): void
    {
        Schema::disableForeignKeyConstraints();
        $this->artisan('migrate:reset');
        Schema::enableForeignKeyConstraints();

        restore_error_handler();
        restore_exception_handler();
    }
}
