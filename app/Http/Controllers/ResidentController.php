<?php
namespace App\Http\Controllers;

use App\Models\BarangayResident;
use Illuminate\Http\Request;

class ResidentController extends Controller {
    public function store(Request $request) {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'middle_initial' => 'nullable|string|max:1',
            'address'        => 'required|string|max:255',
        ]);

        // Check for duplicate full name
        $exists = BarangayResident::whereRaw('LOWER(first_name) = ?', [strtolower($request->first_name)])
            ->whereRaw('LOWER(last_name) = ?', [strtolower($request->last_name)])
            ->whereRaw('LOWER(COALESCE(middle_initial, "")) = ?', [strtolower($request->middle_initial ?? '')])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['duplicate_resident' => 'A resident already exists in the registry.'])
                ->with('open_tab', 'registry');
        }

       $year = date('Y');
       $latestResident = BarangayResident::latest('id')->first();
       $nextNumber = $latestResident ? $latestResident->id + 1 : 1;
       $resId = 'RES-' . $year . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
       
       BarangayResident::create([
        'first_name'     => $request->first_name,
        'last_name'      => $request->last_name,
        'middle_initial' => $request->middle_initial,
        'address'        => $request->address,
        'resident_id'    => $resId,
    ]);

    return back()
        ->with('success', "Resident {$request->first_name} {$request->last_name} added with ID: {$resId}")
        ->with('open_tab', 'registry');
}

    public function destroy(BarangayResident $barangayResident) {
        $barangayResident->delete();
        return back()
            ->with('success', 'Resident removed from registry.')
            ->with('open_tab', 'registry');
    }
}