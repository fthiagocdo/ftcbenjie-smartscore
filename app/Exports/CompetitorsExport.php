<?php

namespace App\Exports;

use App\Competitor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CompetitorsExport implements FromCollection, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $listCompetitors = new collection();
        
        $titles = array(
            'first_name' => 'Nome',
            'last_name' => 'Sobrenome',
            'nickname' => 'Apelido',
            'phone' => 'Telefone',
            'email' => 'Email',
            'sponsors' => 'Patrocinadores',
        );
        $listCompetitors->push($titles);

        $competitors = Competitor::all();
        foreach ($competitors as $element) {
            $competitor = array(
                'first_name' => $element->first_name,
                'last_name' => $element->last_name,
                'nickname' => $element->nickname,
                'phone' => $element->phone,
                'email' => $element->email,
                'sponsors' => $element->sponsors,
            );
            $listCompetitors->push($competitor);
        }

        return $listCompetitors;
    }
}
