<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListCaseRequest;
use App\Models\ListCase;
use Illuminate\Http\Request;

class ListCaseController extends Controller
{
    public function apiIndex(string $type)
    {
        $lists = [];
        if (! isset($_GET['s'])) {
            $lists['my_lists'] = ListCase::latest()->paginate(20);
        } else {
            $lists['my_lists'] = ListCase::search($_GET['s'])->query(fn ($query) => $query->latest())->paginate(20);
        }
    }

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
    public function store(ListCaseRequest $request)
    {
        $item = ListCase::create($this->initForm($request));

        return response(['msg' => __('panel_messages.list', ['status' => __('panel_messages.attributes.created')]), 'item' => $item]);
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
    public function update(Request $request, int $id)
    {
        ListCase::find($id)->update($this->initForm($request));

        return response(['msg' => __('panel_messages.list', ['status' => __('panel_messages.attributes.created')]), 'item' => ListCase::find($id)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(mixed $id)
    {
        ListCase::whereIn('id', explode(',', $id))->delete();

        return response(['msg' => __('panel_messages.list', ['status' => __('panel_messages.attributes.deleted')])]);
    }

    public function duplicate(int $id)
    {
        $list = ListCase::with(['listItems'])->find($id);
        $newList = $list->replicate();
        $newList->created_at = $newList->updated_at = now();
        $newList->save();
        $getListItems = $list->listItems->toArray();
        $newList->listItems()->createMany($getListItems);

        return response(['msg' => __('panel_messages.list', ['status' => __('panel_messages.attributes.duplicated')]), 'item' => $newList]);
    }

    private function initForm(Request $request): array
    {
        $data = $request->all();
        if (! isset($data['id'])) {
            $data['author'] = auth()->id();
            $data['pvc'] = json_encode($data['pvc']);
            $data['stock'] = json_encode($data['stock']);
        }

        return $data;
    }
}
