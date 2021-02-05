<?php

namespace App\Http\Controllers;

use Exception;
use Helper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Competitor;
use App\Judge;
use App\Competition;
use App\Category;
use App\Group;
use App\Score;
use App\PartialTotalScore;

class ScoreController extends Controller
{
    public function index(Request $request)
    {
        if(auth()->user()->can('add-score')){
            $actual_competitor = Competitor::find(Helper::getVariable('actual_competitor'));
            
            //Verifica se há um competidor liberado para pontuação ou se este juiz já deu pontuação para o competidor na volta atual
            if(!isset($actual_competitor) || !$actual_competitor->released || $this->hasScore()) {
                Alert::info('Info', 'Aguarde o próximo competidor ser liberado!')
                    ->autoClose(10000);
                return redirect()->intended('/');
            //Se não, habilita o competidor para receber pontuação
            } else {
                session(['competitor' => $actual_competitor]);
                session(['lap_number' => Helper::getVariable('actual_lap_number')]);
                session(['page' => 'score']);
                return view('score');
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function save(Request $request)
    {
        if(auth()->user()->can('add-score')){
            try{
                $data = $request->all();
                
                if($this->validation($data, false)) {
                    $score = new Score();
                    $score->competitor_id = Helper::getVariable('actual_competitor');
                    $score->judge_id = $judge = $this->getJudge()->id;
                    $score->lap_number = Helper::getVariable('actual_lap_number');
                    $score->score = $data['score'];
                    $score->save();

                    $this->finalizeScore();

                    Alert::success('Info', 'Pontuação cadastrada com sucesso!')
                        ->autoClose(10000);
                    return redirect()->intended('/');
                } else {
                    return redirect()->back();
                }
            } catch(Exception $e) {
                Log::error('ScoreController.save: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 601)!')
                    ->autoClose(10000);
                
                return redirect()->back();
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    private function validation($data) {
        $valid = true;

        if(!isset($data['score'])) {
            Alert::error('Erro', 'Pontuação deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if(floatval($data['score']) > 10) {
            Alert::error('Erro', 'A pontuação máxima permitida é 10.0!')
                ->autoClose(10000);
            $valid = false;
        }

        return $valid;
    }

    private function hasScore() {
        $competitor_id = Helper::getVariable('actual_competitor');
        $judge = $this->getJudge();
        $actual_lap_number = Helper::getVariable('actual_lap_number');
        $score = Score::where('competitor_id', '=', $competitor_id)
                        ->where('judge_id', '=', $judge->id)
                        ->where('lap_number', '=', $actual_lap_number)
                        ->get();
        
        if($score->count() == 0) {
            return false;
        } else {
            return true;
        }
    }

    private function getJudge() {
        return Judge::where('user_id', '=', Auth::user()->id)->first();
    }

    private function finalizeScore() {
        try{
            $competition = Competition::orderBy('id')->get()->first();
            $competitor_id = Helper::getVariable('actual_competitor');
            $actual_lap_number = Helper::getVariable('actual_lap_number');
            $actual_lap = Score::where('competitor_id', '=', $competitor_id)
                            ->where('lap_number', '=', $actual_lap_number)
                            ->get();
            
            $judges = Judge::all();
            if($judges->count() == $actual_lap->count()) {
                $competitor = Competitor::find($competitor_id);
                $competitor->released = false;
                $competitor->update();

                $total = 0;
                foreach ($actual_lap as $lap) {
                    $total += $lap->score;
                }
                $total = round($total / $judges->count(), 2);
                
                $partial_total_score = new PartialTotalScore();
                $partial_total_score->competitor_id = $competitor->id;
                $partial_total_score->lap_number = $actual_lap_number;
                $partial_total_score->score = $total;
                $partial_total_score->save();

                $this->setNextCompetitor();
            }
        } catch(Exception $e) {
            Log::error('ScoreController.finalizeScore: '.$e->getMessage());
            Alert::error('Erro', 'Erro ao executar ação (Código: 602)!')
                ->autoClose(10000);
            
            return redirect()->back();
        }
    }

    private function setNextCompetitor() {
        try{
            $competition = Competition::orderBy('id')->get()->first();
            $actual_category = Category::find(Helper::getVariable('actual_category'));
            $actual_group = Group::find(Helper::getVariable('actual_group'));
            $actual_competitor = Competitor::find(Helper::getVariable('actual_competitor'));
            $quantity_groups = Helper::getVariable('quantity_groups');
            $actual_lap_number = Helper::getVariable('actual_lap_number');

            //Procura o próximo na sequência da lista de competidores da bateria
            $nextCompetitor = $actual_group->competitors()->where('order', '=', $actual_competitor->order+1)->first();

            //Verifica se o próximo competidor da lista existe
            if(isset($nextCompetitor)) {
                Helper::setVariable('actual_competitor', $nextCompetitor->id);
                return;
            //Se não existir, reinicia a bateria para a próxima volta (se houver)
            } else {
                if($competition->quantity_laps > $actual_lap_number) {
                //Se sim, retorna o primeiro competidor da bateria
                    Helper::setVariable('actual_competitor', $actual_group->competitors()->orderBy('order')->first()->id);
                    Helper::setVariable('actual_lap_number', $actual_lap_number+1);
                    return;
                //Se não houver mais voltas, passa para a próxima bateria (se houver)
                } else {
                    Helper::setVariable('actual_lap_number', '1');
                    
                    //Procura o próximo na sequência da lista de baterias da categoria
                    $nextGroup = Group::where('category_id', '=', $actual_category->id)->where('order', '=', $actual_group->order+1)->first();

                    //Verifica se o próximo grupo da lista existe
                    if(isset($nextGroup)) {
                        Log::info('entrou 4');
                        Helper::setVariable('actual_group', $nextGroup->id);
                        Helper::setVariable('actual_competitor', $nextGroup->competitors()->orderBy('order')->first()->id);
                        return;
                    }
                }
            }

            //Se não encontrar nenhum competidor elegível, a competição é encerrada.
            $actual_category->started = false;
            $actual_category->finished = true;
            $actual_category->update();

            DB::table('variables')->delete();
            return;
        } catch(Exception $e) {
            Log::error('ScoreController.setNextCompetitor: '.$e->getMessage());
            Alert::error('Erro', 'Erro ao executar ação (Código: 603)!')
                ->autoClose(10000);
            
            return redirect()->back();
        }
    }

    //Verifica se o competidor informado tem pontuação dada por todos os juízes na volta atual
    private function hasAllLapScore($competitor_id) {
        try{
            $judges = Judge::all();
            $lap_number = Helper::getVariable('actual_lap_number');
            $score = Score::where('competitor_id', '=', $competitor_id)
                        ->where('lap_number', '=', $lap_number)
                        ->get();

            if($score->count() == $judges->count()) {
                return true;
            } else {
                return false;
            }
        } catch(Exception $e) {
            Log::error('ScoreController.hasAllLapScore: '.$e->getMessage());
            Alert::error('Erro', 'Erro ao executar ação (Código: 604)!')
                ->autoClose(10000);
            
            return redirect()->back();
        }
    }
}
