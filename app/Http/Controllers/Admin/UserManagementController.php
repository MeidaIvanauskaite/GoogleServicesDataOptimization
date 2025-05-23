<?php
    namespace App\Http\Controllers\Admin;
    use App\Http\Controllers\Controller;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;

    class UserManagementController extends Controller {
        public function index() {
            $users = User::where('id', '!=', Auth::id())->get();
            return view('admin.users.index', compact('users'));
        }

        public function updateRole(Request $request, User $user) {
            $request->validate([
                'role' => 'required|in:admin,viewer',
            ]);

            $user->role = $request->role;
            $user->save();
            return back()->with('success', 'User role updated.');
        }

        public function destroy(User $user) {
            $user->delete();
            return back()->with('success', 'User deleted.');
        }

        public function store(Request $request) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:viewer,admin',
                'password' => 'required|string|min:6',
            ]);

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
            ]);

            return back()->with('success', 'User created successfully.');
        }
    }
    
