<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddressInfo;
use Illuminate\Http\Request;

class UserAddressInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = ['address' => ['title' => __('validation.attributes.address'), 'value' => 'address'],
            'phone' => ['title' => __('validation.attributes.phone'), 'value' => 'phone'],
            'mobile' => ['title' => __('validation.attributes.mobile'), 'value' => 'mobile']];

        return response(['items' => UserAddressInfo::myItems()->get(), 'types' => $types]);
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
    public function store(UserAddressRequest $request)
    {
        $item = UserAddressInfo::create($this->initFormData($request));

        return response(['msg' => __('panel_messages.'.(string) $request->input('type'), ['status' => __('panel_messages.attributes.created')]), 'item' => $item]);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserAddressInfo $userAddressInfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserAddressInfo $userAddressInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserAddressRequest $request, int $id)
    {
        UserAddressInfo::find($id)->update($this->initFormData($request));

        return response(['msg' => __('panel_messages.'.(string) $request->input('type'), ['status' => __('panel_messages.attributes.update')]), 'item' => UserAddressInfo::find($id)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserAddressInfo $userAddressInfo)
    {
        $message = __('panel_messages.'.$userAddressInfo->type, ['status' => __('panel_messages.attributes.deleted')]);
        $userAddressInfo->delete();

        return response(['msg' => $message]);
    }

    private function initFormData(Request $request): array
    {
        $data = $request->all();
        $data['user_id'] = auth()->id();
        if ($data['isShow'] == 1) {
            UserAddressInfo::myItems()->update(['isShow' => false]);
        }

        return $data;
    }
}
