<?php

namespace Tests\Feature;

use App\Models\Lick;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShareLickTest extends TestCase
{
    use RefreshDatabase;

    public function testLickCanBeSharedDirectlyToUser()
    {
        // arrange
        // create source user, target user, lick to be shared
        $sourceUser = User::factory()->create();
        $targetUser = User::factory()->create();
        $lickToBeShared = Lick::factory()->for($sourceUser)->create();

        $this->actingAs($sourceUser);

        //act
        // call endpoint with target user id
        $response = $this->post(route('shared.create', $lickToBeShared), [
            'share_target_users' => [$targetUser->id],
            'share_target_teams' => [],
        ]);

        // assert
        $response->assertStatus(302);

        $sharedLicks = $targetUser->fresh(['licksSharedDirectly'])->licksSharedDirectly;

        $this->assertCount(1, $sharedLicks);
        $this->assertEquals($lickToBeShared->id, $sharedLicks->first()->id);
        $this->assertEquals($lickToBeShared->title, $sharedLicks->first()->title);
    }

    public function testLickCanBeSharedDirectlyToTeam()
    {
        // arrange
        // set source user, target team and target user
        $sourceUser = User::factory()->create();
        $lickToBeShared = Lick::factory()->for($sourceUser)->create();

        $targetUser = User::factory()->create();
        $targetTeam = Team::factory()
            ->hasAttached($targetUser, [], 'users')
            ->create(['personal_team' => false]);

        $this->actingAs($sourceUser);

        // act
        // call endpoint with target user id
        $response = $this->post(route('shared.create', $lickToBeShared), [
            'share_target_users' => [],
            'share_target_teams' => [$targetTeam->id],
        ]);

        // assert
        $response->assertStatus(302);

        $sharedLicks = $targetTeam->fresh(['licksSharedDirectly'])->licksSharedDirectly;

        $this->assertCount(1, $sharedLicks);
        $this->assertEquals($lickToBeShared->id, $sharedLicks->first()->id);
        $this->assertEquals($lickToBeShared->title, $sharedLicks->first()->title);
    }

    public function testLickCanBeSharedToAlreadySharedUser()
    {
        $userAlreadyShared = User::factory()->create();
        $sourceUser = User::factory()->create();

        $lickToBeShared = Lick::factory()->for($sourceUser)->create();

        $this->actingAs($sourceUser);

        //act
        // call endpoint with target user id
        $this->post(route('shared.create', $lickToBeShared), [
            'share_target_users' => [$userAlreadyShared->id],
            'share_target_teams' => [],
        ]);

        // call again
        $response = $this->post(route('shared.create', $lickToBeShared), [
            'share_target_users' => [$userAlreadyShared->id],
            'share_target_teams' => [],
        ]);

        // assert
        $response->assertStatus(302);

        $sharedLicks = $userAlreadyShared->fresh(['licksSharedDirectly'])->licksSharedDirectly;

        $usersShared = $lickToBeShared->fresh(['usersSharedDirectly'])->usersSharedDirectly;

        $this->assertCount(1, $usersShared); // check only 1 user
        $this->assertEquals($lickToBeShared->id, $sharedLicks->first()->id);
        $this->assertEquals($lickToBeShared->title, $sharedLicks->first()->title);
    }

    public function testLickCanBeSharedToAlreadySharedTeam()
    {
        // arrange
        // set source user, target team and target user
        $sourceUser = User::factory()->create();
        $lickToBeShared = Lick::factory()->for($sourceUser)->create();

        $targetUser = User::factory()->create();
        $targetTeam = Team::factory()
            ->hasAttached($targetUser, [], 'users')
            ->create(['personal_team' => false]);

        $this->actingAs($sourceUser);

        //act
        // call endpoint with target user id
        $this->post(route('shared.create', $lickToBeShared), [
            'share_target_users' => [],
            'share_target_teams' => [$targetTeam->id],
        ]);

        // call again
        $responseShareTeam = $this->post(route('shared.create', $lickToBeShared), [
            'share_target_users' => [],
            'share_target_teams' => [$targetTeam->id],
        ]);

        // share with user already on team
        $responseShareUser = $this->post(route('shared.create', $lickToBeShared), [
            'share_target_users' => [$targetUser->id],
            'share_target_teams' => [],
        ]);

        // assert
        $responseShareTeam->assertStatus(302);
        $responseShareUser->assertStatus(302);

        $sharedLicks = $targetTeam->fresh(['licksSharedDirectly'])->licksSharedDirectly;
        $usersShared = $lickToBeShared->fresh(['usersSharedDirectly'])->usersSharedDirectly;
        $teamsShared = $lickToBeShared->fresh(['teamsSharedDirectly'])->teamsSharedDirectly;

        $this->assertCount(1, $usersShared); // check only 1 user
        $this->assertCount(1, $teamsShared); // check only 1 team
        $this->assertEquals($lickToBeShared->id, $sharedLicks->first()->id);
        $this->assertEquals($lickToBeShared->title, $sharedLicks->first()->title);
    }
}
