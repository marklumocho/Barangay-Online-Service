<?php
namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\BarangayResident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller {
    public function index(Request $request) {
        $user = Auth::user();

        if ($user && $user->role === 'staff') {
            $search        = $request->get('search');
            $applications  = Application::latest()->get();
            $approvedCount = Application::where('status', 'approved')->count();
            $readyCount    = Application::where('status', 'ready_to_pickup')->count();
            $residentCount = BarangayResident::count();

            $residents = BarangayResident::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($search) . '%'])
                      ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($search) . '%'])
                      ->orWhereRaw('LOWER(CONCAT(first_name, " ", last_name)) LIKE ?', ['%' . strtolower($search) . '%']);
                });
            })->latest()->get();

            $staffAccounts = User::where('role', 'staff')->latest()->get();
            $staffCount    = $staffAccounts->count();

            return view('home', compact(
                'applications', 'approvedCount', 'readyCount',
                'residents', 'residentCount', 'staffAccounts', 'staffCount', 'search'
            ));
        }

        return view('home');
    }

    public function store(Request $request) {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'middle_initial' => '',
            'last_name'      => 'required|string|max:100',
            'service_type'   => 'required|string',
            'purpose'        => 'required|string|max:255',
            'notes'          => 'nullable|string',
        ]);

        $isResident = BarangayResident::whereRaw('LOWER(first_name) = ?', [strtolower($request->first_name)])
            ->whereRaw('LOWER(last_name) = ?', [strtolower($request->last_name)])
            ->exists();

        if (!$isResident) {
            return back()
                ->withInput()
                ->withErrors(['not_resident' => 'You are not a registered resident of this barangay. Please visit the barangay hall for verification.']);
        }

        $resident = BarangayResident::whereRaw('LOWER(first_name) = ?', [strtolower($request->first_name)])
            ->whereRaw('LOWER(last_name) = ?', [strtolower($request->last_name)])
            ->first();

        $fullName = trim($request->first_name . ' ' . strtoupper($request->middle_initial) . '. ' . $request->last_name);

        Application::create([
            'user_id'       => Auth::id(),
            'resident_name' => $fullName,
            'resident_id'   => $resident->resident_id,
            'document_type' => $request->service_type,
            'purpose'       => $request->purpose,
            'notes'         => $request->notes,
            'status'        => 'approved',
        ]);

        return back()->with('success', 'Your application has been submitted and automatically approved. Please wait for the Ready to Pick Up notice.');
    }

    public function markReady(Application $application) {
        $application->update(['status' => 'ready_to_pickup']);
        return back()->with('success', 'Marked as Ready to Pick Up.');
    }

    public function destroy(Application $application) {
        $application->delete();
        return back()->with('success', 'Application deleted.');
    }
}