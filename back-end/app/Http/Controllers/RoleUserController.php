<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleUserController extends Controller
{
    public function update(Request $request, $id)
    {
        $role_user = DB::table('role_user')
            ->where('user_id', $id)
            ->update(['role_id' => $request->role_id]);
        $statusCode = 200;
        if (!$role_user)
            $statusCode = 404;
        return response()->json($role_user, $statusCode);
    }
}
