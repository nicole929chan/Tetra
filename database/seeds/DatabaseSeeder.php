<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$user = factory(App\User::class)->create();
    	$project = factory(App\Project::class)->create();
    	\DB::table('project_user')->insert(['project_id' => $project->id, 'user_id' => $user->id]);

    	$room = factory(App\Room::class)->create(['project_id' => $project->id]);
    	$selection = factory(App\Selection::class)->create(['room_id' => $room->id]);
    	$version = factory(App\Version::class)->create(['room_id' => $room->id]);

    	$room->selection = $selection->id;
    	$room->version = $version->id;
    	$room->status = 1;
    	$room->save();

    	$comment = factory(App\Comment::class)->create([
    		'creator_id' => $user->id,
    		'version_id' => $version->id
    	]);

    	factory(App\Reply::class)->create([
    		'repliable_type' => get_class($comment),
    		'repliable_id' => $comment->id
    	]);

    	factory(App\Comment::class)->create([
    		'creator_id' => $user->id,
    		'version_id' => $version->id
    	]);

    }
}
