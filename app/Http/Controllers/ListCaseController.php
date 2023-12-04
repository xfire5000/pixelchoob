<?php

namespace App\Http\Controllers;

use App\Models\ListCase;
use Illuminate\Http\Request;

class ListCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('ListCase');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ListCase $listCase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListCase $listCase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListCase $listCase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListCase $listCase)
    {
        //
    }
}
