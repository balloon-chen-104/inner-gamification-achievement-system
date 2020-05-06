<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as BaseTestCase;

use App\User;
use App\Group;
use App\Bulletin;
use App\Category;
use App\Task;
use App\Setting;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */

    // 在 laradock 裡面安裝並執行 dusk
    // resource: https://gist.github.com/bonsi/f59805f74b277bf0cf01a9b19cf9ca2c
    // bonsi/laravel-dusk-docker.md

    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
            '--headless',
            '--window-size=1920,1080',
        ]);

        return RemoteWebDriver::create(
            'http://selenium:4444/wd/hub', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    // custom methods
    public function user()
    {
        return factory(User::class)->create();
    }

    public function group($creator_id = 0)
    {
        $group = factory(Group::class)->create([
            'creator_id' => 1
        ]);
        if($creator_id != 0){
            $group->creator_id = $creator_id;
            $group->save();
        }

        return $group;
    }

    public function bulletin($type, $content, $user_id, $group_id)
    {
        if($type != 'flash_message' && $type != 'announcement'){
            return false;
        }

        $bulletin = new Bulletin;
        $bulletin->type = $type;
        $bulletin->content = $content;
        $bulletin->user_id = $user_id;
        $bulletin->group_id = $group_id;
        $bulletin->save();

        return $bulletin;
    }

    public function category($group_id = 0)
    {
        $category = factory(Category::class)->create([
            'group_id' => Group::first()->id
        ]);
        if($group_id != 0){
            $category->group_id = $group_id;
            $category->save();
        }
        return $category;
    }

    public function task($category_id = 0, $creator_id = 0, $days = 0)
    {
        $task = factory(Task::class)->create([
            'category_id' => Category::first()->id,
            'creator_id' => $this->user()->id
        ]);

        if($category_id != 0){
            $task->category_id = $category_id;
            $task->save();
        }
        if($creator_id != 0){
            $task->creator_id = $creator_id;
            $task->save();
        }

        $task->expired_at = date_add(date_create($task->created_at), date_interval_create_from_date_string(($days + 2) . ' days'));
        $task->save();

        return $task;
    }

    public function setting($group_id = 0)
    {
        $setting = Setting::create([
            'cycle' => 30,
            'started_at' => date('Y-m-d'),
            'group_id' => '1'
        ]);

        if($group_id != 0){
            $setting->group_id = $group_id;
            $setting->save();
        }

        return $setting;
    }


    // Copy from trait RefreshDatabase
    
    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    protected function refreshDatabase()
    {
        $this->usingInMemoryDatabase()
                        ? $this->refreshInMemoryDatabase()
                        : $this->refreshTestDatabase();
    }

    /**
     * Determine if an in-memory database is being used.
     *
     * @return bool
     */
    protected function usingInMemoryDatabase()
    {
        $default = config('database.default');

        return config("database.connections.$default.database") === ':memory:';
    }

    /**
     * Refresh the in-memory database.
     *
     * @return void
     */
    protected function refreshInMemoryDatabase()
    {
        $this->artisan('migrate');

        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * Refresh a conventional test database.
     *
     * @return void
     */
    protected function refreshTestDatabase()
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('migrate:fresh', [
                '--drop-views' => $this->shouldDropViews(),
                '--drop-types' => $this->shouldDropTypes(),
            ]);

            $this->app[Kernel::class]->setArtisan(null);

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }

    /**
     * Begin a database transaction on the testing database.
     *
     * @return void
     */
    public function beginDatabaseTransaction()
    {
        $database = $this->app->make('db');

        foreach ($this->connectionsToTransact() as $name) {
            $connection = $database->connection($name);
            $dispatcher = $connection->getEventDispatcher();

            $connection->unsetEventDispatcher();
            $connection->beginTransaction();
            $connection->setEventDispatcher($dispatcher);
        }

        $this->beforeApplicationDestroyed(function () use ($database) {
            foreach ($this->connectionsToTransact() as $name) {
                $connection = $database->connection($name);
                $dispatcher = $connection->getEventDispatcher();

                $connection->unsetEventDispatcher();
                $connection->rollback();
                $connection->setEventDispatcher($dispatcher);
                $connection->disconnect();
            }
        });
    }

    /**
     * The database connections that should have transactions.
     *
     * @return array
     */
    protected function connectionsToTransact()
    {
        return property_exists($this, 'connectionsToTransact')
                            ? $this->connectionsToTransact : [null];
    }

    /**
     * Determine if views should be dropped when refreshing the database.
     *
     * @return bool
     */
    protected function shouldDropViews()
    {
        return property_exists($this, 'dropViews')
                            ? $this->dropViews : false;
    }

    /**
     * Determine if types should be dropped when refreshing the database.
     *
     * @return bool
     */
    protected function shouldDropTypes()
    {
        return property_exists($this, 'dropTypes')
                            ? $this->dropTypes : false;
    }
}
