<?php

namespace App\Exports;

use App\Judge;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class JudgesExport implements FromCollection, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $listJudges = new collection();
        
        $titles = array(
            'name' => 'Nome',
            'email' => 'Email',
        );
        $listJudges->push($titles);

        $judges = Judge::all();
        foreach ($judges as $element) {
            $judge = array(
                'name' => $element->user()->first()->name,
                'email' => $element->user()->first()->email,
            );
            $listJudges->push($judge);
        }

        return $listJudges;
    }
}
