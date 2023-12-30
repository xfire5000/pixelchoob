<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    use PasswordValidationRules;

    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $users = User::with(['roles', 'addressInfos']);
        if (isset($_GET['s'])) {
            $users = $users->where('name', 'like', "%{$_GET['s']}%")->whereHas('addressInfos', fn ($query) => $query
                ->where('description', 'like', "%{$_GET['s']}%"));
        }
        if (isset($_GET['role'])) {
            $users = $users->whereHas('roles', fn ($query) => $query
                ->whereIn('name', explode(',', $_GET['role'])));
        }
        $users = $users->latest()->paginate(20);
        $roles = Role::get();
        $addressTypes = [['title' => __('validation.attributes.address'), 'value' => 'address'],
            ['title' => __('validation.attributes.phone'), 'value' => 'phone'],
            ['title' => __('validation.attributes.mobile'), 'value' => 'mobile']];

        return inertia('Users', compact(['users', 'roles', 'addressTypes']));
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
    public function store(UserRequest $request)
    {
        $request->validate(['password' => $this->passwordRules()]);
        $item = User::create($request->all());
        $item->assignRole(...$request->input('role'));
        $item['roles'] = Role::whereIn('name', $request->input('role'))->get();
        if ($request->hasAny('address_infos')) {
            foreach ($request->input('address_infos') as $req) {
                $item['address_infos'][] = $item->addressInfos()->create($req);
            }
        }

        return response(['msg' => __('panel_messages.user', ['status' => __('panel_messages.attributes.created')]), 'item' => $item]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, int $id)
    {
        if ($request->input('change_pass')) {
            $request->validate(['password' => $this->passwordRules()]);
        }
        $item = User::find($id);
        $item->update($request->all());
        $item->syncRoles($request->input('role'));
        foreach ($request->input('address_infos') as $address) {
            if (isset($address['id'])) {
                $item->addressInfos()->find($address['id'])->update($address);
            } else {
                $item->addressInfos()->create($address);
            }
        }

        return response(['msg' => __('panel_messages.user', ['status' => __('panel_messages.attributes.update')]), 'item' => User::with(['roles', 'addressInfos'])->find($id)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        User::destroy($id);

        return response(['msg' => __('panel_messages.user', ['status' => __('panel_messages.attributes.deleted')])]);
    }
}
