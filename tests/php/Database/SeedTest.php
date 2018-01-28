<?php

namespace Tests\Database\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class SeedTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Check if the database has four user types.
     *
     * @return void
     */
    public function testThereAreTwoUserTypes()
    {
        $userTypes = DB::select('SELECT * FROM user_types');

        $this->assertEquals(count($userTypes), 2);
    }
}
