<?php

namespace App\Http\Controllers;

use App\Models\OrderFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderFeatureController extends Controller
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
        $request->validate([
            'feature_id' => 'required|integer|max:20',

        ]);

        $ownerId = Auth::id();
        OrderFeature::create([
            'owner_id' => $ownerId,
            'feature_id' => $request->input('feature_id'),
        ]);

        return response()->json([
            'result' => 'Feature orderd successfully'
        ]);
        //
    }
    /**
     * Display the specified resource.
     */
    public function show(OrderFeature $orderFeature)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderFeature $orderFeature)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderFeature $orderFeature)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderFeature $orderFeature)
    {
        //
    }
}
