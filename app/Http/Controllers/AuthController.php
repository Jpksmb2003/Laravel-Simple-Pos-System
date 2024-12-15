<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
// Users Management
public function index()
{
    $users = User::all();
    return view('users', compact('users'));
}

public function ShowEditForm($id)
{
    $users = User::findOrFail($id);
    return view('users_form', compact('users'));
}


public function update(Request $request, $id)
{
    // Validate the input data, ensuring email is unique except for the current user
    $request->validate([
        'email' => 'required|string|email|unique:users,email,' . $id,
        'name' => 'required|string|max:255',
        'role' => 'required|string|max:255',
    ]);

    // Find and update the user
    $users = User::findOrFail($id);
    $this->save($users, $request);

    return redirect()->route('user.index')->with('success', 'User <strong>"' . $users->name . '"</strong> has been updated successfully.');
}

public function save($users, $value)
{
    // Update the user details
    $users->email = $value->email;
    $users->name = $value->name;
    $users->role = $value->role; // Fixed this to store the role instead of "color"
    $users->save();
}

public function destroy($id)
{
    $users = User::find($id);

    if ($users) {
        $users->delete();
        return redirect()->route('user.index')->with('error', 'User <strong>"' . $users->name . '"</strong> deleted successfully.');
    } else {
        return redirect()->route('user.index')->with('error', 'User not found.');
    }
}



    // Authenthication
    public function showLoginForm()
    {
        return view('auth.login'); // Ensure this path is correct
    }
    
    public function showResetForm()
    {
        return view('auth.password');
    }

    public function login(Request $request)
    {
        // Validate the input data
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // Redirect to intended page or dashboard
            return redirect()->intended('dashboard'); // Adjust this route as necessary
        }

        // Redirect back with an error message
        return back()->withErrors([
            'email' => 'Invalid email or password. Please try again.',

        ])->withInput($request->only('email', 'remember'));
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:saler,manager,admin',
        ]);

        // Create the user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,  // Save the selected role
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }


}
