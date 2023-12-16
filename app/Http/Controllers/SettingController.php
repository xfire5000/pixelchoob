<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SettingController extends Controller
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
        if (! isset($_GET['type'])) {
            return abort(Response::HTTP_NOT_FOUND);
        }
        Setting::ofKey($_GET['type'])->first()->update([
            'value' => json_encode($request->all()),
        ]);

        return response(['msg' => __('panel_messages.settings', ['status' => __('panel_messages.attributes.saved')])]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $type)
    {
        return response(Setting::ofKey($type)->firstOrFail()->value);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
