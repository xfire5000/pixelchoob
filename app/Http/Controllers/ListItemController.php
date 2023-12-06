<?php

namespace App\Http\Controllers;

use App\Models\ListCase;
use App\Models\ListItem;
use Illuminate\Http\Request;

class ListItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $id)
    {
        $list_case = ListCase::withoutTrashed()->with('author')->findOrFail($id);
        $items = ListItem::where('list_case_id', $id)->orderBy('sortable')->get();
        $viewed = session()->get('viewed_post', []);
        if (! in_array($id, $viewed) and $list_case->author_id !== auth()->id()) {
            $list_case->update(['viewed' => true]);
            session()->push('viewed_post', $id);
        }

        return inertia('ListCaseItems', compact(['items', 'list_case']));
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
    public function show(ListItem $listItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ListItem $listItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ListItem $listItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListItem $listItem)
    {
        //
    }
}
