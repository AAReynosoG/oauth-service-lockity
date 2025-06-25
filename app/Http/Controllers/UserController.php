<?php

namespace App\Http\Controllers;

use App\Rules\EmailValidation;
use App\Rules\FullNameValidation;

use App\Rules\PasswordValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'first_name' => ['sometimes', new FullNameValidation],
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
