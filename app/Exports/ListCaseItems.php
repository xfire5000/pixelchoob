<?php

namespace App\Exports;

use App\Models\ListCase;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ListCaseItems implements FromView
{
    private $list_case_id;

    public function __construct(int $id)
    {
        $this->list_case_id = $id;
    }

    public function view(): View
    {
        $list_case = ListCase::find($this->list_case_id);
        $fourSections = array_reverse(['W2', 'W1', 'L2', 'L1']);
        $twoSections = array_reverse(['W', 'L']);

        return view('exports.ListCaseItems', compact(['list_case', 'fourSections', 'twoSections']));
    }
}
