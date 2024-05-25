<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\JobSeeker;
use App\Models\FileJobSeeker;

class JobSeekerController extends Controller
{
    // Menampilkan form registrasi job seeker
    public function showRegistrationForm()
    {
        return view('jobseeker.register');
    }

    // Menyimpan data job seeker
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:5',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
        ], [
            'password.min' => 'Password must be at least 5 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'job_seeker',
        ]);

        // Create job seeker
        $jobSeeker = JobSeeker::create([
            'user_id' => $user->id,
            'job_seeker_name' => $request->name, // You can adjust this according to your form fields
            // Add additional job seeker fields here
        ]);

        // Redirect to login with success message
        return redirect()->route('loginJobSeeker')->with('success', 'Job Seeker registered successfully. Please login.');
    }



    public function showProfile()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil data job seeker berdasarkan user_id
        $jobSeeker = JobSeeker::where('user_id', $user->id)->first();

        // Kirim data ke view
        return view('jobseeker.profile', compact('jobSeeker'));
    }

    public function updateProfile(Request $request)
    {
        // Validasi input
        $request->validate([
            'job_seeker_name' => 'required|string|max:255',
            'job_seeker_phone' => 'nullable|string|max:255',
            'job_seeker_address' => 'nullable|string|max:255',
            'job_seeker_resume' => 'nullable|string',
        ]);
    
        // Ambil data job seeker berdasarkan user_id
        $jobSeeker = JobSeeker::where('user_id', Auth::id())->first();
    
        // Perbarui data job seeker
        $jobSeeker->job_seeker_name = $request->job_seeker_name;
        $jobSeeker->job_seeker_phone = $request->job_seeker_phone;
        $jobSeeker->job_seeker_address = $request->job_seeker_address;
        $jobSeeker->job_seeker_resume = $request->job_seeker_resume;
        $jobSeeker->save();
    
        // Redirect kembali ke halaman profil dengan pesan sukses
        return redirect()->route('jobseeker.profile')->with('success', 'Profile updated successfully.');
    }

     
}
