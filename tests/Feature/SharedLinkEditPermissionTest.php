<?php

use App\Models\Category;
use App\Models\Link;
use App\Models\Role;
use App\Models\User;

test('shared user can update a link when permission is edition even if they are viewer', function () {
    $viewerRole = Role::create(['name' => 'viewer']);

    $owner = User::factory()->create();
    $recipient = User::factory()->create();
    $recipient->roles()->attach($viewerRole->id);

    $category = Category::create([
        'name' => 'Owner Category',
        'user_id' => $owner->id,
    ]);

    $link = Link::create([
        'title' => 'Old title',
        'url' => 'https://example.com/old',
        'category_id' => $category->id,
    ]);

    $link->sharedUsers()->attach($recipient->id, ['permission' => 'edition']);

    $response = $this->actingAs($recipient)->patch(route('links.update', $link), [
        'title' => 'New title',
        'url' => 'https://example.com/new',
        'tags' => 'alpha,beta',
    ]);

    $response->assertRedirect(route('links.index', absolute: false));

    $link->refresh();
    expect($link->title)->toBe('New title');
    expect($link->url)->toBe('https://example.com/new');
});

test('shared user cannot update a link when permission is lecture', function () {
    $viewerRole = Role::create(['name' => 'viewer']);

    $owner = User::factory()->create();
    $recipient = User::factory()->create();
    $recipient->roles()->attach($viewerRole->id);

    $category = Category::create([
        'name' => 'Owner Category',
        'user_id' => $owner->id,
    ]);

    $link = Link::create([
        'title' => 'Old title',
        'url' => 'https://example.com/old-2',
        'category_id' => $category->id,
    ]);

    $link->sharedUsers()->attach($recipient->id, ['permission' => 'lecture']);

    $response = $this->actingAs($recipient)->patch(route('links.update', $link), [
        'title' => 'New title',
        'url' => 'https://example.com/new-2',
        'tags' => 'alpha',
    ]);

    $response->assertForbidden();
});

