<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller {
    public function store(Request $request) {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'       => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'staff',
        ]);

        return back()
            ->with('success', 'Staff account created for ' . $request->first_name . ' ' . $request->last_name . '.')
            ->with('open_tab', 'staff');
    }

    public function destroy(User $user) {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['staff_error' => 'You cannot delete your own account.'])->with('open_tab', 'staff');
        }
        $user->delete();
        return back()
            ->with('success', 'Staff account removed.')
            ->with('open_tab', 'staff');
    }
}