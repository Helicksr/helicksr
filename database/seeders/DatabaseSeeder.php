<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Lick;
use App\Models\User;
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

        $tagsToInsert = [];

        for ($i = 0; $i < 500; $i++) {
            $createdLick = Lick::factory()->for($users->random())->create();
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
