<?php

namespace App\Exports;

use App\Models\Candidate;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class CandidatessExport implements FromQuery, WithMapping, WithColumnWidths
{
    public function query()
    {
        return Candidate::query();
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
            'A' => 60,
            'B' => 40,
            'C' => 20,
        ];
    }
}
