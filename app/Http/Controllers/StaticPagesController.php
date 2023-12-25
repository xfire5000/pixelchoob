<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Inertia\Inertia;

class StaticPagesController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome');
    }

    public function contactForm(ContactRequest $request)
    {
        mail('xfire5000@gmail.com', $request->input('subject'), $request->input('description'), $request->input('name'));

        return response(['msg' => __('guest_messages.contact', ['status' => __('guest_messages.attributes.sent')])]);
    }
}
