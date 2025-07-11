<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Validation\Rule;
// use Illuminate\Support\Facades\Hash;

// class SettingsController extends Controller
// {
//     public function edit()
//     {
//         $user = Auth::user();
//         return view('settings.edit', compact('user'));
//     }

//     public function update(Request $request)
//     {
//         $user = Auth::user();

//         $validated = $request->validate([
//             'name' => ['required', 'string', 'max:255'],
//             'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
//             'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
//             'new_password' => ['nullable', 'confirmed', Password::defaults()],
//         ]);

//         $user->name = $validated['name'];
//         $user->email = $validated['email'];

//         if ($request->filled('new_password')) {
//             $user->password = Hash::make($validated['new_password']);
//         }

//         $user->save();

//         return redirect()->route('settings')->with('success', 'Settings updated successfully!');
//     }
// }
