<?php

namespace App\Http\Controllers;

use App\Exports\ListCaseItems;
use App\Http\Requests\ListItemRequest;
use App\Models\ListCase;
use App\Models\ListItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ListItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $slug): \Inertia\Response
    {
        $my_id = auth()->id();
        $list_case = ListCase::withoutGlobalScopes()->withoutTrashed()
            ->with(['author', 'invoice', 'ticket'])->archived(false)->whereSlug($slug)
            ->where('author_id', $my_id)->orWhere('user_id', $my_id)->firstOrFail();
        $items = $list_case->listItems()->orderBy('sortable')->get();
        $viewed = session()->get('viewed_post', []);
        if (! in_array($list_case->id, $viewed) and $list_case->author_id !== $my_id) {
            $list_case->update(['viewed' => true]);
            session()->push('viewed_post', $list_case->id);
        }
        session()->put('list_case_id', $list_case->id);
        $invoice_prices = Setting::ofKey('invoices_price')->firstOrFail()->value;

        return inertia('ListCaseItems/index', compact(['items', 'list_case', 'invoice_prices']));
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

    public function export()
    {
        $id = session('list_case_id');
        $listCase = ListCase::find($id);
        $now = now()->format('Y-m-d_H-i-s');

        return Excel::download(new ListCaseItems($id), "$listCase->title-$now.xlsx");
    }

    private function initFormData(Request $request): array
    {
        $data = $request->all();
        $data['chamfer'] = json_encode($request->input('chamfer'));
        $data['pvc'] = json_encode($request->input('pvc'));
        $data['groove'] = json_encode($request->input('groove'));
        $data['gazor_hinge'] = json_encode($request->input('gazor_hinge'));
        $data['dimensions'] = json_encode($request->input('dimensions'));
        if (! isset($data['list_case_id'])) {
            $data['list_case_id'] = session('list_case_id');
        }

        return $data;
    }
}
