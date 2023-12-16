<?php

namespace App\Http\Controllers;

use App\Models\ListCase;

class DashboardController extends Controller
{
    public function index()
    {
        $my_lists = ListCase::withCount('listItems')->archived(false);
        $my_inboxes = ListCase::withCount('listItems')->with(['author'])->inbox();
        $counter = [
            $my_inboxes->viewed(false)->count(), // new inboxes
            $my_lists->count(), // my-list-cases
            ListCase::hasInvoice()->count(), // invoices
        ];
        $list_cases = $my_lists->latest()->take(10)->get();
        $inbox = $my_inboxes->latest()->take(10)->get();

        return inertia('Dashboard', compact(['inbox', 'list_cases', 'counter']));
    }
}
