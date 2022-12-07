<?php

namespace App\Exports;

use App\Models\Candidate;
use Maatwebsite\Excel\Concerns\FromCollection;

class CandidatessExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Candidate::all();
    }

    public function map($model): array
    {
        return [
            $model->name,
            $model->type,
            $model->short_link,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 120,
            'B' => 120,
            'C' => 120,
        ];
    }

}
