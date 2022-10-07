<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\SeededTest;

class GateTest extends SeededTest
{
    use RefreshDatabase;
    public function testModifyServer()
    {
        $randomUser = factory(User::class)->create();
        $this->actingAs($randomUser);
        $this->assertFalse(Gate::allows('modify-server', $this->server));

        $randomUser->assignRole('admin');
        $this->assertTrue(Gate::allows('modify-server', $this->server));

        $this->actingAs($this->server->ownedBy);
        $this->assertTrue(Gate::allows('modify-server', $this->server));

    }
}
