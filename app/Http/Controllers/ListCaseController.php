<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListCaseRequest;
use App\Models\ListCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ListCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! isset($_GET['type'])) {
            return abort(Response::HTTP_BAD_REQUEST);
        }
        $type = $_GET['type'];

        return response(match ($type) {
            'my-lists' => ListCase::withCount('listItems')->with(['invoice', 'ticket'])->latest()->archived(false)->paginate(20),
            'inbox' => ListCase::withCount('listItems')->with(['author', 'invoice', 'ticket'])->inbox()->latest()->paginate(20),
            'archived' => ListCase::withCount('listItems')->with(['invoice', 'ticket'])->archived()->latest()->paginate(20),
            'deleted' => ListCase::withCount('listItems')->with(['invoice', 'ticket'])->onlyTrashed()->latest()->paginate(20),
            'searched' => ListCase::search($_GET['s'])->query(fn ($query) => $query->withCount('listItems')
                ->with(['author', 'invoice', 'ticket'])->latest())->paginate(20)
        });
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

    public function restore(int $id)
    {
        ListCase::withTrashed()->find($id)->restore();

        return response()->noContent();
    }

    public function send(Request $request, int $id)
    {
        ListCase::find($id)->update(['user_id' => $request->input('user_id')]);

        return response(['msg' => __('panel_messages.list', ['status' => __('panel_messages.attributes.sent')])]);
    }

    private function initForm(Request $request): array
    {
        $data = $request->all();
        if (! isset($data['id'])) {
            $data['author_id'] = auth()->id();
            $data['viewed'] = false;
            $data['pvc'] = json_encode($data['pvc']);
            $data['stock'] = json_encode($data['stock']);
        }

        return $data;
    }
}
