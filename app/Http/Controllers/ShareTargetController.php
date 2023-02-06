<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class ShareTargetController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $inputLimit = $request->input('limit', 10);
        $searchTerm = $request->input('search');

        $usersQuery = User::limit($inputLimit > 10 ? 10 : $inputLimit)
            ->orderBy('updated_at', 'desc');
        
        $teamsQuery = Team::where('personal_team', false)
            ->limit($inputLimit > 10 ? 10 : $inputLimit)
            ->orderBy('updated_at', 'desc');

        if ($searchTerm) {
            $usersQuery->where('name', 'like', "%$searchTerm%")
                ->orWhere('email', 'like', "%$searchTerm%");
            
            $teamsQuery->where('name', 'like', "%$searchTerm%");
        }

        return [
            'users' => $usersQuery->get(),
            'teams' => $teamsQuery->get(),
        ];
    }
}
