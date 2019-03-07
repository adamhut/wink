<?php
namespace adamhut\Wink\Tests;

use adamhut\Wink\WinkServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;


class TestCase extends BaseTestCase
{
    protected function setUp() :void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../database/factories');

        //$this->loadMigrationsFrom(__DIR__ . '/../src/Migrations');

        include_once __DIR__. '/../src/Migrations/2018_10_30_000000_create_tables.php';
        include_once __DIR__. '/../src/Migrations/2018_11_16_000000_add_meta_fields.php';

        (new \CreateTables())->up();
        (new \AddMetaFields())->up();
    }



    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {

        return [
            WinkServiceProvider::class,
        ];
    }


    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('wink.database_connection','default');
        $app['config']->set('database.default', 'default');
        $app['config']->set('database.connections.default', [
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);
    }
}

