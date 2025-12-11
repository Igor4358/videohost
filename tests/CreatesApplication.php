<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Boot the testing environment traits.
     *
     * @return void
     */
    protected function setUpTraits()
    {
        parent::setUpTraits();

        // Автоматически запускаем миграции перед тестами
        if (method_exists($this, 'runMigrations')) {
            $this->runMigrations();
        }
    }

    /**
     * Run migrations for testing.
     *
     * @return void
     */
    protected function runMigrations()
    {
        // Используем SQLite в памяти для тестов
        if ($this->isUsingSqlite()) {
            $this->createTestDatabase();
        }

        // Запускаем миграции
        \Artisan::call('migrate:fresh');

        // Можно добавить сиды если нужно
        // \Artisan::call('db:seed');
    }

    /**
     * Check if we are using SQLite for testing.
     *
     * @return bool
     */
    protected function isUsingSqlite(): bool
    {
        return config('database.default') === 'sqlite';
    }

    /**
     * Create SQLite test database.
     *
     * @return void
     */
    protected function createTestDatabase()
    {
        $database = config('database.connections.sqlite.database');

        if ($database === ':memory:') {
            return; // Уже используем память
        }

        // Создаем директорию если нужно
        $dir = dirname($database);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Создаем файл базы данных
        if (!file_exists($database)) {
            touch($database);
        }
    }

    /**
     * Clean up after tests.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        // Очищаем базу данных после тестов
        if (method_exists($this, 'refreshDatabase')) {
            \Artisan::call('migrate:reset');
        }

        parent::tearDown();
    }
}
