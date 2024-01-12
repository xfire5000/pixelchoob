<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = User::find(auth()->id());
        if (! isset($_GET['s'])) {
            $contacts = $contacts->contacts()->latest()->paginate(20);
        } else {
            $contacts = $contacts->contacts()->where('name', 'like', "%{$_GET['s']}%")
                ->orWhereHas('addressInfos', fn ($query) => $query->where('isShow', true)
                    ->where('description', 'like', "%{$_GET['s']}%"))
                ->orderBy('name')->paginate(20);
        }

        return response($contacts);
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
        User::find(auth()->id())->contacts()->sync($request->input('user_ids'), false);

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $haveContacts = User::find(auth()->id())->contacts()->whereNot('id', $id)->pluck('id');
        User::find(auth()->id())->contacts()->sync($haveContacts);

        return response(['msg' => __('panel_messages.contact', ['status' => __('panel_messages.attributes.deleted')])]);
    }

    public function usersIndex()
    {
        $haveContacts = User::find(auth()->id())->contacts()->pluck('id');

        return response(User::with(['addressInfos' => fn ($query) => $query->where('isShow', true)])
            ->whereNotIn('id', $haveContacts)
            ->where('name', 'like', "%{$_GET['search']}%")
            ->whereHas('roles', fn ($query) => $query->where('name', explode(',', $_GET['type'])))
            ->orWhereHas('addressInfos', fn ($query) => $query->where('isShow', true)
                ->where('description', 'like', "%{$_GET['search']}%"))->paginate(20));
    }
}
