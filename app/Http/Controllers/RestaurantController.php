<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RestaurantController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Restaurant::all()->toArray(), 200);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        return response()->json(Restaurant::find($id)->toArray(), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $restaurant = new Restaurant();
        $restaurant->name = $request->name;
        $restaurant->email = $request->email;
        $restaurant->password = $request->password;


        $restaurant->postal_code = $request->postal_code;
        $restaurant->address = $request->address;
        $restaurant->city = $request->city;
        $restaurant->save();

        return response()->json(Restaurant::find($restaurant->id)->toArray(), 201);
    }
    public function update(Request $request, string $id): JsonResponse
    {
        $restaurant = Restaurant::find($id);
        $restaurant->name = $request->name;
        $restaurant->email = $request->email;
        $restaurant->password = $request->password;


        $restaurant->postal_code = $request->postal_code;
        $restaurant->address = $request->address;
        $restaurant->city = $request->city;
        $restaurant->save();

        return response()->json(Restaurant::find($restaurant->id)->toArray(), 200);
    }

    public function destroy(Request $request, string $id)
    {
        $user = Restaurant::find($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
