<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /* What functions need to be managed by this controller?
    1. Get specific user information. - Done
    2. Get information about all users - Done
    3. Create, Update, Delete Functions for Users - Done
    4. CRUD for User Profiles
    */

    // Getting information about a specific user.
    public function user(): Collection
    {
        return User::find(request('id'));
    }

    // Getting information about all users.
    public function users(): Collection
    {
        return User::all();
    }

    // Creating a User
    public function create(Request $request): RedirectResponse
    {
        // Never trust the user, always validate input.
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
        ]);
        /*
         * Try to create the user and if success direct to the user information page with success message.
         * If failure throw exception and redirect back to previous page with exception message flashed to session.
         */
        try {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);

            return redirect('/users/'.User::where('email', $request->email)->first()->id)
                ->with('success', 'User created successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    // Updating a user
    public function update(Request $request): RedirectResponse
    {
        // Never trust the user, always validate input.
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,'.$request->id,
            'password' => 'required|string',
        ]);
        /*
         * Try to update the user, if success redirect to user information with success message.
         * If failure redirect to previous page with exception message flashed to session.
         */
        try {
            $user = User::find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect('/users/'.User::where('email', $request->email)->first()->id)
                ->with('success', 'User updated successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    // Deleting a user
    public function delete(Request $request): RedirectResponse
    {
        // Never trust input, even when hidden from the user.
        $request->validate(['id' => 'required|integer']);
        /*
         * Try to delete the user, if success redirect to users with success message.
         * If failure redirect to previous page with exception message flashed to session.
         */
        try {
            User::find($request->id)->delete();

            return redirect('/users')->with('success', 'User deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

    }
}
