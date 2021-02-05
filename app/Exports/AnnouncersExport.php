<?php

namespace App\Exports;

use App\Announcer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AnnouncersExport implements FromCollection, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $listAnnouncers = new collection();
        
        $titles = array(
            'first_name' => 'Nome',
            'last_name' => 'Sobrenome',
            'nickname' => 'Apelido',
            'phone' => 'Telefone',
            'email' => 'Email',
            'sponsors' => 'Patrocinadores',
        );
        $listAnnouncers->push($titles);

        $announcers = Announcer::all();
        foreach ($announcers as $element) {
            $announcer = array(
                'first_name' => $element->first_name,
                'last_name' => $element->last_name,
                'nickname' => $element->nickname,
                'phone' => $element->phone,
                'email' => $element->email,
                'sponsors' => $element->sponsors,
            );
            $listAnnouncers->push($announcer);
        }

        return $listAnnouncers;
    }
}
