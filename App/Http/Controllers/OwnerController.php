<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          // Validate the Request
          $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'restaurant_name' => 'required|string',

        ]);

        // After validation, proceed with user creation
        $section = Owner::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'restaurant_name' => $request->input('restaurant_name'),

        ]);

        return response()->json([
            'message' => "Owner Created Successfully"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Owner $owner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Owner $owner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Owner $owner)
    {
        //
    }
    public function updateRestaurant(Request $request)
    {
        $request->validate([
            'restaurant_name' => 'required|string|max:255',

        ]);

        $ownerId = Auth::id();
        $owner = Owner::findOrFail($ownerId);
        $owner->restaurant_name = $request->restaurant_name;
        $owner->save();
        return response()->json([
            'result' => 'Restaursnt updated successfully',
            'restaurant' => $owner->restaurant_name
   ]);
}
    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|string|max:255',

        ]);
        $ownerId = Auth::id();
        $owner = Owner::findOrFail($ownerId);
        $owner->logo = $request->logo;
        $owner->save();
        return response()->json([
            'result' => 'Logo updated successfully',
            'Logo' => $owner->logo
   ]);
}
public function updateMainColor(Request $request)
{
        $request->validate([
            'main_color' => 'required|string|max:255',

        ]);

        $ownerId = Auth::id();
        $owner = Owner::findOrFail($ownerId);
        $owner->main_color = $request->main_color;
        $owner->save();
        return response()->json([
            'result' => 'Main Color updated successfully',
            'main_color' => $owner->main_color
        ]);
        //
    }
    public function updateSecondColor(Request $request)
    {
        $request->validate([
            'second_color' => 'required|string|max:255',

        ]);

        $ownerId = Auth::id();
        $owner = Owner::findOrFail($ownerId);
        $owner->second_color = $request->second_color;
        $owner->save();
        return response()->json([
            'result' => 'Second Color updated successfully',
            'second_color' => $owner->second_color
        ]);
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Owner $owner)
    {
        //
    }


    public function customerStatistics(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = Carbon::parse($request->input('date'));

        $usersOnDate = User::whereDate('created_at', $date)->get();
        $usersInWeek = User::whereBetween('created_at', [
            $date->startOfWeek(), $date->endOfWeek()
        ])->get();

        $usersInMonth = User::whereMonth('created_at', $date->month)
                            ->whereYear('created_at', $date->year)
                            ->get();

        return [
            'on_date' => $usersOnDate,
            'in_week' => $usersInWeek,
            'in_month' => $usersInMonth,
        ];
    }

}
