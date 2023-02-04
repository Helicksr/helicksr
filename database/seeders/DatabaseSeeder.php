<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Lick;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()->count(8)->withPersonalTeam()->create();

        $teams = Team::factory()->count(5)->state(new Sequence(
            fn ($seq) => ['user_id' => $users->random()->id]
        ))->hasAttached(
            $users->random(random_int(0, $users->count())),
            [],
            'users',
        )->create([
            'personal_team' => false,
        ]);

        $tagsToInsert = [];

        for ($i = 0; $i < 500; $i++) {
            $author = $users->random();

            $sharedWithUsers = $users
                ->reject(fn ($user) => $user->id == $author->id)
                ->random(random_int(0, $users->count() - 1));

            $sharedWithTeams = $teams->random(random_int(0, $teams->count()));

            $createdLick = Lick::factory()
                ->for($author)
                ->hasAttached(
                    $sharedWithUsers,
                    [],
                    'usersSharedDirectly',
                )->hasAttached(
                    $sharedWithTeams,
                    [],
                    'teamsSharedDirectly',
                )->create();

            foreach ($createdLick->tags as $tag) {
                if (isset($tagsToInsert[$tag])) {
                    $tagsToInsert[$tag]['uses']++;
                } else {
                    $tagsToInsert[$tag] = [
                        'tag' => $tag,
                        'uses' => 1,
                    ];
                }
            };
        }

        DB::table('tags')->insert($tagsToInsert);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
