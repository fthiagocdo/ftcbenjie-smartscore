<?php

namespace App\Exports;

use Helper;
use App\Competition;
use App\Category;
use App\Competitor;
use App\Judge;
use App\PartialTotalScore;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Log;

class CategoriesExport implements FromCollection, ShouldAutoSize
{
    protected $categoryId; 

    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $listCategory = new collection();
        $category = Category::find($this->categoryId);
        $competition = Competition::orderBy('id')->first();

        $firstLine = array(
            'category' => 'CATEGORIA:',
            'name' => $category->name,
        );
        $listCategory->push($firstLine);
        $listCategory->push(array(''));

        if($category->finished) {
            $competitors = new Collection();
            foreach ($category->competitors()->get() as $competitor) {
                $total_lap = PartialTotalScore::where('competitor_id', '=', $competitor->id)->orderBy('lap_number')->get();
                $competitor->total_average = Helper::calculateTotal($total_lap, $competition->score_type);
                $competitors->push($competitor);
            }
            
            $count = 1;
            foreach ($competitors->sortByDesc('total_average') as $competitor) {
                $listCategory->push(array(
                    'competitor_label' => 'COMPETIDOR:',
                    'competitor' => $competitor->first_name.' '.$competitor->last_name
                ));
                $listCategory->push(array(
                    'nickname_label' => 'APELIDO:',
                    'nickname' => $competitor->nickname,
                ));
                $listCategory->push(array(
                    'position_label' => 'CLASSIFICAÇÃO:',
                    'position' => ($count++).'º',
                ));
                
                $lineJudges = array();
                array_push($lineJudges, '');
                array_push($lineJudges, '');
                foreach (Judge::all() as $index=>$judge) {
                    array_push($lineJudges, 'JUIZ '.($index+1));
                }
                array_push($lineJudges, 'TOTAL VOLTA');
                $listCategory->push($lineJudges);

                $actual_lap_number = 1;            
                $line = array();
                array_push($line, 'VOLTA '.$actual_lap_number);
                array_push($line, '');
                
                foreach ($competitor->score()->orderBy('lap_number')->get() as $score) {
                    if($score->lap_number != $actual_lap_number) {
                        array_push($line, $competitor->partial_total_score()->where('lap_number', '=', $actual_lap_number)->first()->score);
                        $listCategory->push($line);
                        $actual_lap_number = $score->lap_number;
                        
                        $line = array();
                        array_push($line, 'VOLTA '.$actual_lap_number);
                        array_push($line, '');
                    }
                    
                    array_push($line, $score->score);
                }

                array_push($line, $competitor->partial_total_score()->where('lap_number', '=', $actual_lap_number)->first()->score);
                $listCategory->push($line);
                $listCategory->push(array(
                    'label' => 'TOTAL GERAL:',
                    'score' => $competitor->total_average,
                ));
                $listCategory->push(array(''));
            }
        } else {
            foreach ($category->groups()->get() as $batery_number=>$group) {
                $firstLine = array(
                    'label' => 'Bateria '.($batery_number+1)
                );
                $listCategory->push($firstLine);

                foreach ($group->competitors()->orderBy('order')->get() as $index=>$competitor) {
                    $listCategory->push(array(
                        'competitor_label' => ($index+1).'º',
                        'competitor' => $competitor->first_name.' '.$competitor->last_name
                    ));
                    $listCategory->push(array(
                        'nickname_label' => 'APELIDO:',
                        'nickname' => $competitor->nickname,
                    ));
                    $listCategory->push(array(
                        'sponsors_label' => 'PATROCINADORES:',
                        'sponsors' => $competitor->sponsors,
                    ));
                }

                $listCategory->push(array(''));
            }
        }

        

        return $listCategory;
    }
}
