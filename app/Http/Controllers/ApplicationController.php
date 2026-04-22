<?php
namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\BarangayResident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            'first_name'     => 'required|string|max:100|',
            'middle_name'    => 'nullable|string|max:100|',
            'last_name'      => 'required|string|max:100|',
            'service_type'   => 'required|string|in:Barangay Clearance,Certificate of Indigency,Certificate of Residency,Business Permit',
            'purpose'        => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // Must match account name
        $nameMatchesAccount =
            strtolower($request->first_name) === strtolower($user->first_name) &&
            strtolower($request->last_name)  === strtolower($user->last_name);

        if (!$nameMatchesAccount) {
            return back()->withInput()->withErrors([
                'not_resident' => 'The name you entered does not match your registered account name. You can only request documents for yourself.'
            ]);
        }

        // Must be in barangay registry
        $registryResident = BarangayResident::whereRaw('LOWER(first_name) = ?', [strtolower($request->first_name)])
            ->whereRaw('LOWER(last_name) = ?', [strtolower($request->last_name)])
            ->first();

        if (!$registryResident) {
            return back()->withInput()->withErrors([
                'not_resident' => 'You are not a registered resident of this barangay. Please visit the barangay hall for verification.'
            ]);
        }

        // Check for existing application for the same document type
        $existingApp = Application::where('user_id', $user->id)
            ->where('document_type', $request->service_type)
            ->whereIn('status', ['approved', 'ready_to_pickup', 'picked_up'])
            ->latest()
            ->first();

        if ($existingApp) {
            // Block if still approved (waiting for staff action)
            if ($existingApp->status === 'approved') {
                return back()->withInput()->withErrors([
                    'duplicate_app' => 'You already have a pending "' . $request->service_type . '" application. Please wait for staff to process it.'
                ]);
            }

            // Block if ready to pick up but not yet collected
            if ($existingApp->status === 'ready_to_pickup') {
                return back()->withInput()->withErrors([
                    'duplicate_app' => 'Your "' . $request->service_type . '" is ready to pick up. Please collect it at the barangay hall first.'
                ]);
            }
            //Mark as missed if ready to pick up for 2 days
            if ($existingApp->status === 'ready_to_pickup' && $existingApp->updated_at->lt(Carbon::now()->subDay())) {
                $existingApp->update(['status' => 'missed']);
                return back()->withInput()->withErrors([
                    'duplicate_app' => 'Your previous "' . $request->service_type . '" application was marked as missed. Please submit a new application.'
                ]);
            }

            // Block if picked up less than 1 week ago
            if ($existingApp->status === 'picked_up' && $existingApp->picked_up_at) {
                $oneWeekAfterPickup = Carbon::parse($existingApp->picked_up_at)->addWeek();
                if (Carbon::now()->lt($oneWeekAfterPickup)) {
                    $availableOn = $oneWeekAfterPickup->format('F d, Y');
                    return back()->withInput()->withErrors([
                        'duplicate_app' => 'You can request "' . $request->service_type . '" again on ' . $availableOn . ' (one week after your last pickup).'
                    ]);
                }
            }
        }

        $fullName = trim($request->first_name . ' ' . strtoupper($request->middle_name) . '. ' . $request->last_name);

        Application::create([
            'user_id'       => $user->id,
            'resident_name' => $fullName,
            'resident_id'   => $registryResident->resident_id,
            'document_type' => $request->service_type,
            'purpose'       => $request->purpose,
            'status'        => 'approved',
        ]);

        return back()->with('success', 'Your application has been submitted. Please wait for staff to mark it Ready to Pick Up within 2 days, or it will be automatically declined.');
    }

    public function markReady(Application $application) {
        $application->update(['status' => 'ready_to_pickup']);
        return back()->with('success', 'Marked as Ready to Pick Up.');
    }

    public function markPickedUp(Application $application) {
        $application->update([
            'status'       => 'picked_up',
            'picked_up_at' => Carbon::now(),
        ]);
        return back()->with('success', 'Document marked as Picked Up. Resident may request again after one week.');
    }

    public function destroy(Application $application) {
        $application->delete();
        return back()->with('success', 'Application deleted.');
    }
}