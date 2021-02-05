<?php

namespace App\Http\Controllers;

use Exception;
use Helper;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Competition;
use App\Competitor;
use App\Category;
use App\Judge;
use App\Score;
use App\PartialTotalScore;
use App\Variables;

class IndividualController extends Controller
{
    public function individual($competitor_id)
    {
        try{
            $competition = Competition::orderBy('id')->first();
            $judges = Judge::all();
            $competitor = Competitor::find($competitor_id);
            $categories = Category::all();
            
            if($categories->count() == 0 || $categories->first()->groups()->count() == 0 ) {
                Alert::info('Info', 'Por favor, aguarde a competição iniciar.')
                    ->autoClose(10000);
            
                session(['page' => 'home']);
                return redirect()->back();
            } else {
                $category_id = request('category_id');
                if($categories->count() == 0) {
                    $category_id = null;
                    $group_id = null;
                } else if(!isset($category_id)) {
                    if(isset($competitor)) {
                        $category_id = $competitor->categories()->first()->id;
                    } else {
                        $category_id = Category::first()->id;
                        $competitor = Category::find($category_id)->competitors()->orderBy('order')->first(); 
                    }
                }  else {
                    $competitor = Category::find($category_id)->competitors()->orderBy('order')->first();
                }

                //Verifica se o competidor a ser mostrado é o competidor da volta.
                //Se sim, mostra a pontuação da volta
                //Se não, mostra a pontuação da última volta realizada
                $actual_competitor = Helper::getVariable('actual_competitor');
                if($competitor->id == $actual_competitor && !$competition->finished) {
                    $actual_lap_number = Helper::getVariable('actual_lap_number');
                } else {
                    $last_lap = Score::where('competitor_id', '=', $competitor->id)->orderBy('lap_number')->get()->last();    
                    if(isset($last_lap)) {
                        $actual_lap_number = $last_lap->lap_number;
                    } else {
                        $actual_lap_number = 1;
                    }
                }
                
                $actual_lap = Score::where('competitor_id', '=', $competitor->id)
                                ->where('lap_number', '=', $actual_lap_number)
                                ->orderBy('judge_id')->get();
                
                //Apenas calcula o total se todos os juizes tiverem dado nota
                $total = 0;
                if($actual_lap->count() == $judges->count()) {
                    foreach ($actual_lap as $lap) {
                        $total += $lap->score;
                    }
                    $total = round($total / $judges->count(), 2);
                } else {
                    while($actual_lap->count() < $judges->count()) {
                        $register = (Object)[];
                        $register->score = 0;
                        $actual_lap->push($register);
                    }
                }
                
                $registers = $this::buildListAllCompetitors($competition, Category::find($category_id));

                $total_lap = PartialTotalScore::where('competitor_id', '=', $competitor->id)->orderBy('lap_number')->get();
                $total_average = 0.0;
                if(isset($total_lap)) {
                    $total_average = Helper::calculateTotal($total_lap, $competition->score_type);
                }

                session(['competition_name' => $competition->competition_name]);
                session(['page' => 'home']);
                return view('home.individual')
                    ->with('competitor', $competitor)
                    ->with('judges', $judges)
                    ->with('actual_lap_number', $actual_lap_number)
                    ->with('actual_lap', $actual_lap)
                    ->with('total', $total)
                    ->with('total_average', $total_average)
                    ->with('registers', $registers->sortByDesc('total_average'))
                    ->with('categories', $categories)
                    ->with('category_id', $category_id);
            }
        } catch(Exception $e) {
            Log::error('IndividualController.individual: '.$e->getMessage());
            Alert::error('Erro', 'Erro ao executar ação (Código: 401)!')
                ->autoClose(10000);

            return redirect()->back();
        }
    }

    private function buildListAllCompetitors($competition, $category) {
        $registers = new Collection();

        $score_type = $competition->score_type;
        $competitors = $category->competitors()->get();
        foreach ($competitors as $competitor) {
            $register = (Object)[];
            $register->id = $competitor->id;
            $register->photo = $competitor->photo;
            $register->name = $competitor->first_name.' '.$competitor->last_name;
            $register->nickname = $competitor->nickname;
            $register->total_lap = PartialTotalScore::where('competitor_id', '=', $competitor->id)->orderBy('lap_number')->get();
            $register->total_average = Helper::calculateTotal($register->total_lap, $score_type);
            $registers->push($register);
        }

        return $registers;
    }
}
