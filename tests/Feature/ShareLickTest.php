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

    public function test_lick_can_be_shared_directly_to_user()
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

    public function test_lick_can_be_shared_directly_to_team()
    {
        // act
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
}
