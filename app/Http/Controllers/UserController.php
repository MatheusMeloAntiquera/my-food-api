<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(User::all()->toArray(), 200);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        return response()->json(User::find($id)->toArray(), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        return response()->json(User::find($user->id)->toArray(), 201);
    }
    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        return response()->json(User::find($user->id)->toArray(), 200);
    }

    public function destroy(Request $request, string $id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
