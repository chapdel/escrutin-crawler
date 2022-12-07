<?php

namespace App\Http\Livewire;

use App\Exports\CandidatessExport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class GenerateExcell extends Component
{
    public function generate()
    {
        return Excel::download(new CandidatessExport, 'candidates.xlsx');
    }
    public function render()
    {
        return view('livewire.generate-excell');
    }
}
