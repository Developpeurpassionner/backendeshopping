<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

test('un utilisateur peut s\'inscrire', function () {
    $response = $this->post('/inscription', [
        'name' => 'Ibrahim',
        'firstname' => 'Ibrahim',
        'email' => 'datenaiss1995@gmail.com',
        'password' => 'Datenaiss1995',
    ]);

    $response->assertStatus(302); // redirection après inscription
    $this->assertDatabaseHas('users', [
        'email' => 'datenaiss1995@gmail.com',
    ]);
});
