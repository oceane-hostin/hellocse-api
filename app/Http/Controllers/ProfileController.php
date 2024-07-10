<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles = ProfileResource::collection(
            Profile::all()->where('status', '=', StatusEnum::ACTIVE->value)
        );

        return response()->json($profiles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
