<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class DriverTest extends TestCase
{
    public function test_sqlite_driver_exists()
    {
        $drivers = \PDO::getAvailableDrivers();
        dump($drivers);
        $this->assertTrue(in_array('sqlite', $drivers), 'SQLite driver not found');
    }
}
