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
     * Test Login
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

        return response($response, 201);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            'status' => true,
            'users' => $users
        ]);
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
        $check_email = User::where('email', $request->email)->first();
        $check_username = User::where('username', $request->username)->first();

        if ($check_email) {
            return response()->json([
                'status' => false,
                'message' => "Duplicate Email!",
            ], 200);
        } else {
            if ($check_username) {
                return response()->json([
                    'status' => false,
                    'message' => "Duplicate Username!",
                ], 200);
            } else {
                $check_validation = Validator::make($request->all(), [
                    'email' => 'required|email:rfc,dns|unique:users,email',
                    'username' => 'required|unique:users,username|min:4|max:16',
                    'password' => 'required|min:8',
                    'password_confirmation' => 'required|same:password',
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'gender' => 'required|max:1',
                    'address' => 'required',
                    'telephone' => 'required|max:14'
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
        $check_avail = User::find($request->id);

        if ($check_avail) {
            $check_validation = Validator::make($request->all(), [
                'password' => 'required|min:8',
                'password_confirmation' => 'required|same:password',
                'first_name' => 'required',
                'last_name' => 'required',
                'gender' => 'required|max:1',
                'address' => 'required',
                'telephone' => 'required|max:14'
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
                'message' => "User Unavailable!",
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
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
                'message' => "User Unavailable!",
            ], 200);
        }
    }
}
