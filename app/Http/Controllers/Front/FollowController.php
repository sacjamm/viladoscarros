<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Follower;
use Auth;

class FollowController extends Controller {

    public function follow(Request $request) {
        $current_auth_user_id = 0;
        if (Auth::user()) {
            $current_auth_user_id = Auth::user()->id;
        }
      
        // Verificar se o usuário está logado
        if ($current_auth_user_id <= 0) {
            return response()->json(['message' => 'Você precisa estar logado para seguir'], 401);
        }

        $user_id = $request->input('user_id');  // ID do usuário que será seguido
        $follower_id = $current_auth_user_id;  // ID do usuário que está seguindo
        // Verificar se o usuário já segue este usuário
        $existingFollow = Follower::where('user_id', $user_id)
                ->where('follower_id', $follower_id)
                ->first();

        if ($existingFollow) {
            // Se já segue, desfazer o follow
            $existingFollow->delete();
            return response()->json(['status' => 'unfollowed', 'message' => 'Você deixou de seguir este usuário'], 200);
        }

        // Caso contrário, criar o relacionamento de follow
        Follower::create([
            'user_id' => $user_id,
            'follower_id' => $follower_id,
        ]);

        return response()->json(['status' => 'following', 'message' => 'Você está seguindo este usuário agora'], 200);
    }
}
