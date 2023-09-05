<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 

class AuthController extends Controller
{

    public function show(){
        return " All users are shown here";
        }
        
        public function update(Request $request , $id){
        return " All updates on users are done here";
        } 

        public function register(Request $request)
        {
            $data = $request->validate([
                'username' => 'required|unique:users',
                'email' => 'required|unique:users',
                'password' => 'required|confirmed',
                'role' => 'in:admin,moderator,vendor', 
            ]);
            
    
            $userData = array_merge($data, ['role' => $data['role'] ?? 'admin']);
            $userData['approved'] = ($userData['role'] === 'moderator');
    
            $user = User::create($userData);
            $token = $user->createToken('my-token')->plainTextToken;
    
            return response()->json([
                'token' => $token,
                'Type' => 'Bearer'
            ]);
        }
    
        public function login(Request $request)
        {
            $fields = $request->validate([
                'username' => 'required',
                'password' => 'required',
            ]);
    
            $user = User::where('username', $fields['username'])->first();
    
            if (!$user || !Hash::check($fields['password'], $user->password)) {
                return response(['message' => 'Wrong credentials']);
            }
    
            if (!$user->approved) {
                return response()->json(['message' => 'Your account is pending approval.'], 403);
            }
    
            $token = $user->createToken('my-token')->plainTextToken;
    
            return response()->json([
                'token' => $token,
                'Type' => 'Bearer',
                'role' => $user->role 
            ]);
        }


    public function getModerators()
{
    $moderators = User::where('role', 'moderator')->get();
    return response()->json($moderators);
    return view('moderators', compact('moderators'));
}

public function getUsersForApproval()
{
    $users = User::where('approved', false)->get();
    return response()->json($users);
}

public function toggleUserApproval($id)
{
    $user = User::findOrFail($id);
    $user->approved = !$user->approved;
    $user->save();

    return response()->json(['message' => 'User approval status updated.']);
}



}
