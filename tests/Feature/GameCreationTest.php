<?php

use App\Models\User;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;

it('creates or retrieves a game when accessing a campaign', function () {
    // Act as the user and access the campaign link
    get('/test-campaign-1?a=account&segment=low')->assertStatus(200);

    // Assert a game record exists in the database
    assertDatabaseHas('games', [
        'account' => 'account',
        'segment' => 'low',
    ]);
});
