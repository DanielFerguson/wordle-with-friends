<?php

use App\Models\Group;
use App\Models\User;

test('a user can join a group', function () {
    $group = Group::factory()->make();
    $user = User::factory()->make();

    $group->members->add($user);

    $this->assertCount(1, $group->members);
});
