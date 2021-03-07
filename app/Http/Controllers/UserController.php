<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Country;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with('skills', 'country')->get();
        return $user;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {
        $country = Country::firstOrCreate(['name' => $request->name]);
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'country_id' => $country->id,
        ]);
        $skill = Skill::firstOrCreate(['title' => $request->title]);
        $user->skills()->attach($skill, array('level' => $request->level));
        return response()->json([
            "success" => true,
            "message" => "یوزر با موفقیت ذخیره شد.",
            "data" => User::with('skills', 'country')->whereId($user->id)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users,email,' . $user->id,
            'level' => Rule::in(['basic', 'intermediate', 'advanced']),

        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        $user->update($request->all());
        if ($request->has('name')) {
            $country = Country::updateOrCreate(
                ['name' => $request->name]
            );
            $user->country()->associate($country->id);
            $user->save();
        }
        if ($request->has('title') && $request->has('level')) {
            $skill = Skill::updateOrCreate(
                ['title' => $request->title]
            );
            $user->skills()->detach();
            $user->skills()->attach($skill->id, ['level' => $request->level]);
        }
        return response()->json([
            "success" => true,
            "message" => "یوزر با موفقیت آپدیت شد.",
            "data" => User::with('skills', 'country')->whereId($user->id)->get(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        if (isset($user)) {
            $user->delete();
            return response()->json([
                "success" => true,
                "message" => "یوزر با موفقیت پاک شد.",
            ]);
        }
    }
}
