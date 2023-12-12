<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * User Login
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    /**
     * User Register
     */
    public function register(Request $request)
    {
        $check_validation_email_username = Validator::make($request->all(), [
            'email' => 'required|email:rfc,dns|unique:users,email',
            'username' => 'required|unique:users,username|min:4|max:16',
        ]);

        if ($check_validation_email_username->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation not fulfilled!',
                'errors' => $check_validation_email_username->errors(),
            ], 422);
        } else {
            $check_email = User::where('email', $request->email)->first();
            $check_username = User::where('username', $request->username)->first();

            if ($check_email) {
                return response()->json([
                    'status' => false,
                    'message' => "Duplicate Email!",
                ], 409);
            } else {
                if ($check_username) {
                    return response()->json([
                        'status' => false,
                        'message' => "Duplicate Username!",
                    ], 409);
                } else {
                    $check_validation = Validator::make($request->all(), [
                        'password' => 'required|min:8',
                        'password_confirmation' => 'required|same:password',
                        'first_name' => ['required', 'string'],
                        'last_name' => ['required', 'string'],
                        'gender' => ['required', 'string', 'max:1'],
                        'address' => ['required', 'string'],
                        'telephone' => ['required', 'string', 'max:14']
                    ]);

                    if ($check_validation->fails()) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Validation not fulfilled!',
                            'errors' => $check_validation->errors(),
                        ], 422);
                    } else {
                        $user = User::create([
                            'email' => $request->email,
                            'username' => $request->username,
                            'password' => Hash::make($request->password),
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'gender' => $request->gender,
                            'address' => $request->address,
                            'telephone' =>  $request->telephone,
                        ]);

                        $user_login = User::where('email', $request->email)->first();

                        $token = $user->createToken('my-app-token')->plainTextToken;

                        return response()->json([
                            'status' => true,
                            'message' => "User Created successfully!",
                            'user' => $user_login,
                            'token' => $token
                        ], 200);
                    }
                }
            }
        }
    }

    /**
     * User Logout
     */
    public function logout(Request $request)
    {
        $token_delete = $request->user()->currentAccessToken()->delete();
        if ($token_delete === null) {
            return response()->json([
                'status' => false,
                'message' => 'User not login yet!',
            ], 401);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'User Successfully Logged Out',
            ], 200);
        }
    }

    /**
     * Get Login Profile
     */
    public function profile(Request $request)
    {
        $user_login = $request->user();
        if ($user_login && $user_login->currentAccessToken()) {
            $accessToken = $user_login->currentAccessToken()->token;
            return response()->json([
                'status' => true,
                'users' => $user_login
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }
    }

    /**
     * Login Profile Update
     */
    public function profile_update(Request $request)
    {
        $user_login = $request->user();
        if ($user_login && $user_login->currentAccessToken()) {
            $accessToken = $user_login->currentAccessToken()->token;

            $check_avail = User::find($user_login->id);

            if ($check_avail) {
                $check_validation = Validator::make($request->all(), [
                    'first_name' => ['required', 'string'],
                    'last_name' => ['required', 'string'],
                    'gender' => ['required', 'string', 'max:1'],
                    'address' => ['required', 'string'],
                    'telephone' => ['required', 'string', 'max:14']
                ]);

                if ($check_validation->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation not fulfilled!',
                        'errors' => $check_validation->errors(),
                    ], 422);
                } else {
                    $users = User::where('id', $user_login->id)
                        ->update([
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'gender' => $request->gender,
                            'address' => $request->address,
                            'telephone' =>  $request->telephone,
                        ]);

                    return response()->json([
                        'status' => true,
                        'message' => "User Update successfully!",
                        'users' => $users
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "User not Found!",
                ], 404);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }
    }

    public function password_update(Request $request)
    {
        $user_login = $request->user();
        if ($user_login && $user_login->currentAccessToken()) {
            $accessToken = $user_login->currentAccessToken()->token;

            $check_avail = User::find($user_login->id);

            if ($check_avail) {
                $check_validation = Validator::make($request->all(), [
                    'password' => 'required|min:8',
                    'password_confirmation' => 'required|same:password',
                ]);

                if ($check_validation->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation not fulfilled!',
                        'errors' => $check_validation->errors(),
                    ], 422);
                } else {
                    $users = User::where('id', $user_login->id)
                        ->update([
                            'password' => Hash::make($request->password),
                        ]);

                    return response()->json([
                        'status' => true,
                        'message' => "Password Update successfully!",
                        'users' => $users
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "User not Found!",
                ], 404);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_login = $request->user();
        if ($user_login && $user_login->currentAccessToken()) {
            $accessToken = $user_login->currentAccessToken()->token;

            if ($user_login->is_admin === '1') {
                $users = User::all();
                if ($users) {
                    return response()->json([
                        'status' => true,
                        'users' => $users
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'User not found!',
                    ], 422);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not admin',
                ], 401);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_login = $request->user();
        if ($user_login && $user_login->currentAccessToken()) {
            $accessToken = $user_login->currentAccessToken()->token;

            if ($user_login->is_admin === '1') {
                $check_validation_email_username = Validator::make($request->all(), [
                    'email' => 'required|email:rfc,dns|unique:users,email',
                    'username' => 'required|unique:users,username|min:4|max:16',
                ]);

                if ($check_validation_email_username->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation not fulfilled!',
                        'errors' => $check_validation_email_username->errors(),
                    ], 422);
                } else {
                    $check_email = User::where('email', $request->email)->first();
                    $check_username = User::where('username', $request->username)->first();

                    if ($check_email) {
                        return response()->json([
                            'status' => false,
                            'message' => "Duplicate Email!",
                        ], 409);
                    } else {
                        if ($check_username) {
                            return response()->json([
                                'status' => false,
                                'message' => "Duplicate Username!",
                            ], 409);
                        } else {
                            $check_validation = Validator::make($request->all(), [
                                'password' => 'required|min:8',
                                'password_confirmation' => 'required|same:password',
                                'first_name' => ['required', 'string'],
                                'last_name' => ['required', 'string'],
                                'gender' => ['required', 'string', 'max:1'],
                                'address' => ['required', 'string'],
                                'telephone' => ['required', 'string', 'max:14']
                            ]);

                            if ($check_validation->fails()) {
                                return response()->json([
                                    'status' => false,
                                    'message' => 'Validation not fulfilled!',
                                    'errors' => $check_validation->errors(),
                                ], 422);
                            } else {
                                $user = User::create([
                                    'email' => $request->email,
                                    'username' => $request->username,
                                    'password' => Hash::make($request->password),
                                    'first_name' => $request->first_name,
                                    'last_name' => $request->last_name,
                                    'gender' => $request->gender,
                                    'address' => $request->address,
                                    'telephone' =>  $request->telephone,
                                ]);

                                return response()->json([
                                    'status' => true,
                                    'message' => "User Created successfully!",
                                    'user' => $user
                                ], 200);
                            }
                        }
                    }
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not admin',
                ], 401);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user_login = $request->user();
        if ($user_login && $user_login->currentAccessToken()) {
            $accessToken = $user_login->currentAccessToken()->token;

            if ($user_login->is_admin === '1') {
                $check_validation_user_id = Validator::make($request->route()->parameters(), [
                    'id' => ['required', 'integer'],
                ]);

                if ($check_validation_user_id->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation not fulfilled!',
                        'errors' => $check_validation_user_id->errors(),
                    ], 422);
                } else {
                    $check_avail = User::find($request->id);

                    if ($check_avail) {
                        $check_validation = Validator::make($request->all(), [
                            'password' => 'required|min:8',
                            'password_confirmation' => 'required|same:password',
                            'first_name' => ['required', 'string'],
                            'last_name' => ['required', 'string'],
                            'gender' => ['required', 'string', 'max:1'],
                            'address' => ['required', 'string'],
                            'telephone' => ['required', 'string', 'max:14']
                        ]);

                        if ($check_validation->fails()) {
                            return response()->json([
                                'status' => false,
                                'message' => 'Validation not fulfilled!',
                                'errors' => $check_validation->errors(),
                            ], 422);
                        } else {
                            $users = User::where('id', $request->id)
                                ->update([
                                    'password' => Hash::make($request->password),
                                    'first_name' => $request->first_name,
                                    'last_name' => $request->last_name,
                                    'gender' => $request->gender,
                                    'address' => $request->address,
                                    'telephone' =>  $request->telephone,
                                ]);

                            return response()->json([
                                'status' => true,
                                'message' => "User Update successfully!",
                                'users' => $users
                            ], 200);
                        }
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => "User not Found!",
                        ], 404);
                    }
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not admin',
                ], 401);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user_login = $request->user();
        if ($user_login && $user_login->currentAccessToken()) {
            $accessToken = $user_login->currentAccessToken()->token;

            if ($user_login->is_admin === '1') {
                $check_validation_user_id = Validator::make($request->route()->parameters(), [
                    'id' => ['required', 'integer'],
                ]);

                if ($check_validation_user_id->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validation not fulfilled!',
                        'errors' => $check_validation_user_id->errors(),
                    ], 422);
                } else {
                    $check_avail = User::find($request->id);

                    if ($check_avail) {
                        $users = User::where('id', $request->id)
                            ->delete();

                        return response()->json([
                            'status' => true,
                            'message' => "User Deleted successfully!",
                            'users' => $users
                        ], 200);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => "User not Found!",
                        ], 404);
                    }
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not admin',
                ], 401);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'User not logged in',
            ], 401);
        }
    }
}
