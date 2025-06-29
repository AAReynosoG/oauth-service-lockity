<?php

namespace App\Http\Controllers;

use App\Rules\EmailValidation;
use App\Rules\FullNameValidation;

use App\Rules\PasswordValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function me(Request $request) {

        $user = $request->user();

        $userRoles = DB::table("locker_user_roles")
            ->leftJoin("lockers", "lockers.id", "locker_user_roles.locker_id")
            ->leftJoin("areas", "areas.id", "lockers.area_id")
            ->leftJoin("organizations", "organizations.id", "areas.organization_id")
            ->where("locker_user_roles.user_id", $user->id)
            ->select(
                "locker_user_roles.role",
                "lockers.serial_number as locker_serial_number",
                "areas.name as area_name",
                "organizations.name as organization_name"
            )
            ->get();

        $data = $user;
        $data->roles = $userRoles;

        return response()->json([
            'success' => true,
            'message' => 'User data retrieved successfully',
            'data' => $data
        ]);
    }
    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', new FullNameValidation],
            'last_name' => ['sometimes', new FullNameValidation],
            'second_last_name' => ['sometimes', new FullNameValidation],
            'email' => [
                'sometimes',
                new EmailValidation,
                Rule::unique('users')->ignore($user->id)
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(["success" => false, "message" => "Validation error", "errors" => $validator->errors()], 422);
        }

        $user->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => $user,
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'current_password' => ['required'],
            'new_password' => ['required', new PasswordValidation],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation error",
                "errors" => $validator->errors()
            ], 422);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                "success" => false,
                "message" => "Validation error",
                "errors" => [
                    'current_password' => ['The current password is incorrect.']
                ]
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([], 204);
    }
}
