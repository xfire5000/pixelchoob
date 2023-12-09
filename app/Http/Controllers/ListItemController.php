<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListItemRequest;
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

        return inertia('ListCaseItems/index', compact(['items', 'list_case']));
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
    public function store(ListItemRequest $request)
    {
        $item = ListItem::create($this->initFormData($request));

        return response(['msg' => __('panel_messages.list-item', ['status' => __('panel_messages.attributes.created')]), 'item' => $item]);
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
    public function update(ListItemRequest $request, int $id)
    {
        ListItem::find($id)->update($this->initFormData($request));

        return response(['msg' => __('panel_messages.list-item', ['status' => __('panel_messages.attributes.update')]), 'item' => ListItem::find($id)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ListItem $listItem)
    {
        $listItem->delete();

        return response(['msg' => __('panel_messages.list-item', ['status' => __('panel_messages.attributes.deleted')])]);
    }

    public function initFormData(Request $request): array
    {
        $data = $request->all();
        $data['chamfer'] = json_encode($request->input('chamfer'));
        $data['pvc'] = json_encode($request->input('pvc'));
        $data['groove'] = json_encode($request->input('groove'));
        $data['gazor_hinge'] = json_encode($request->input('gazor_hinge'));
        $data['dimensions'] = json_encode($request->input('dimensions'));

        return $data;
    }
}
