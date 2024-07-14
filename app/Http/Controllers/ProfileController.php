<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\ProfilePostRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        if(!Auth("sanctum")->check()) {
            $profiles = ProfileResource::collection(
                Profile::all()->where('status', '=', StatusEnum::ACTIVE->value)
            );
        } else {
            $profiles = ProfileResource::collection(Profile::all());
        }

        return response()->json($profiles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfilePostRequest $request) : JsonResponse
    {
        // Request validation
        $validatedData = $request->validated();

        // Creation of new profile
        $profileRequestData =
        [
            'firstname' => $validatedData['firstname'],
            'lastname' => $validatedData['lastname']
        ];

        if (array_key_exists('image', $validatedData)) {
            $profileRequestData['image'] = $validatedData['image'];
        }

        // Field is not required, will be awaiting if not specified
        if (!empty($validatedData->status)) {
            $profileRequestData['status'] = $validatedData['status'];
        }

        $profile = Profile::create($profileRequestData);

        return response()->json(new ProfileResource($profile), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) : JsonResponse
    {
        // Get profile if exist
        try {
            $profile = Profile::query()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "error" => "This profile doesn't exist"
            ], Response::HTTP_NOT_FOUND);
        }

        // Access without authenticate is only allowed if profile is active
        if($profile->status == StatusEnum::ACTIVE->value
            || Auth("sanctum")->check()
        ){
            return response()->json(new ProfileResource($profile));
        }

        return response()->json([
            "error" => "You don't have the right to access to this profile"
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfilePostRequest $request, string $id) : JsonResponse
    {
        // Request validation
        $validatedData = $request->validated();

        // Check if profile exist
        try {
            $profile = Profile::query()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "error" => "This profile doesn't exist"
            ], Response::HTTP_NOT_FOUND);
        }

        $profile->update($validatedData);

        return response()->json(new ProfileResource($profile));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) : JsonResponse
    {
        // Check if profile exist
        try {
            $profile = Profile::query()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "error" => "This profile doesn't exist"
            ], Response::HTTP_NOT_FOUND);
        }

        $profile->delete();

        return response()->json([
            "message" => "Profile deleted successfully"
        ]);
    }
}
