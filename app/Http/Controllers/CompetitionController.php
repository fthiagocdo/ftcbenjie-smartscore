<?php

namespace App\Http\Controllers;

use Helper;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Competition;
use App\Category;
use App\Group;
use App\Competitor;
use App\Judge;
use App\Score;

class CompetitionController extends Controller
{
    public function index()
    {
        if(auth()->user()->can('edit-competition')){
            try {
                $registers = Category::all();
                $groups = Group::all();

                session(['page' => 'competition']);
                return view('competition.list')
                    ->with('registers', $registers)
                    ->with('groups', $groups);
            } catch(Exception $e) {
                Log::error('CompetitionsController.index: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 301)!')
                    ->autoClose(10000);
                
                session(['page' => 'competition']);
                return redirect()->back();
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function restart()
    {
        if(auth()->user()->can('edit-competition')){
            try{
                Alert::question(
                        $title = 'Reiniciar Competição',
                        $text = 'Esta ação irá apagar todos os dados da competição, dos competidores, categorias e pontuação. Deseja continuar?'
                    )
                    ->showCancelButton($btnText = '<a href="/competition/confirmrestart">Sim</a>')
                    ->showConfirmButton($btnText = '<a href="#">Não</a>')
                    ->showCloseButton()
                    ->autoClose(false);
                    
                session(['page' => 'competition']);
                return redirect()->back();
            } catch(Exception $e) {
                Log::error('CompetitionsController.restart: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 302)!')
                    ->autoClose(10000);
                
                session(['competition' => $competition]);
                return redirect()->back();
            }
        } else {
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function confirmRestart()
    {
        if(auth()->user()->can('edit-competition')){
            try{
                DB::table('scores')->delete();
                DB::table('partial_total_scores')->delete();
                DB::table('variables')->delete();
                DB::table('competitors')->delete();
                DB::table('categories')->delete();
                DB::table('competitions')->delete();
                
                Alert::success('Info', 'A competição foi reiniciada!')
                    ->autoClose(10000);
                session(['page' => 'competition']);
                return redirect()->intended('/competition');
            } catch(Exception $e) {
                Log::error('CompetitionsController.confirmRestart: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 303)!')
                    ->autoClose(10000);
                
                session(['competition' => $competition]);
                return redirect()->back();
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function orderGroups()
    {
        if(auth()->user()->can('edit-competition')){
            try{
                if(!$this::canOrderGroups()) {
                    Alert::info('Competidores/Categorias não cadastrados', 'É necessário haver competidores em todas as categorias cadastradas para criar baterias.')
                        ->autoClose(10000);
                    
                    return redirect()->back();
                } else {
                    Alert::question(
                        $title = 'Criar Baterias',
                        $text = 'Como os competidores serão distribuídos nas baterias a ser criadas?'
                    )
                        ->showCancelButton($btnText = '<a href="/competition/ordergroups/insertingorder">Ordem de Inscrição</a>')
                        ->showConfirmButton($btnText = '<a href="/competition/ordergroups/randomorder">Ordem Aleatória</a>')
                        ->showCloseButton()
                        ->autoClose(false);
                    
                    session(['page' => 'competition']);
                    return redirect()->back();
                }
            } catch(Exception $e) {
                Log::error('CompetitionsController.orderGroups: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 304)!')
                    ->autoClose(10000);
                
                return redirect()->back();
            }
        } else {
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function insertingOrder()
    {
        if(auth()->user()->can('edit-competition')){
            try{
                $categories = Category::all();
                if($categories->count() > 0) {
                    foreach ($categories as $category) {
                        $this->createGroups($category);
                        $this->sortCompetitors($category, $category->competitors()->orderBy('id')->get());
                    }

                    Alert::success('Info', 'Baterias criadas com sucesso!')
                        ->autoClose(10000);
                    return redirect()->intended('/competition');
                } else {
                    Alert::error('Erro', 'Categorias não cadastradas!')
                        ->autoClose(10000);

                    session(['page' => 'competition']);
                    return redirect()->back();
                }
            } catch(Exception $e) {
                Log::error('CompetitionController.insertingOrder: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 305)!')
                    ->autoClose(10000);
                
                session(['page' => 'competition']);
                return redirect()->back();
            }
        } else {
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function randomOrder()
    {
        if(auth()->user()->can('edit-competition')){
            try{
                $categories = Category::all();
                if($categories->count() > 0) {
                    foreach ($categories as $category) {
                        $this->createGroups($category);
                        $this->sortCompetitors($category, $category->competitors()->get()->shuffle());
                    }

                    Alert::success('Info', 'Baterias criadas com sucesso!')
                        ->autoClose(10000);
                    return redirect()->intended('/competition');
                } else {
                    Alert::error('Erro', 'Categorias não cadastradas!')
                        ->autoClose(10000);

                    session(['page' => 'competition']);
                    return redirect()->back();
                }
            } catch(Exception $e) {
                Log::error('CompetitionController.randomOrder: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 306)!')
                    ->autoClose(10000);
                
                session(['page' => 'competition']);
                return redirect()->back();
            }
        } else {
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function settings()
    {
        if(auth()->user()->can('edit-competition')){
            try {
                $competition = Competition::orderBy('id')->get()->first();

                session(['competition' => $competition]);
                session(['page' => 'competition']);
                return view('competition.settings');
            } catch(Exception $e) {
                Log::error('CompetitionsController.settings: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 307)!')
                    ->autoClose(10000);
                
                session(['competition' => $competition]);
                return redirect()->back();
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function saveSettings(Request $request)
    {
        if(auth()->user()->can('edit-competition')){
            try {
                if(!$this::isEditable()) { 
                    Alert::error('Erro', 'A competição já foi iniciada! Não é possível incluir ou alterar dados de competidores.')
                        ->autoClose(10000);
                    return redirect()->back();
                } else {
                    $data = $request->all();
                    
                    $competition = Competition::orderBy('id')->get()->first();
                    if(!isset($competition)) {
                        $competition = new Competition();
                    }

                    $competition->competition_name = $data['competition_name'];
                    $competition->quantity_laps = $data['quantity_laps'];
                    $competition->score_type = $data['score_type'];
                    
                    if($this->validation($data)) {
                        $competition->save();

                        session(['competition_name' => $competition->competition_name]);
                        session(['page' => 'competition']);
                        Alert::success('Info', 'Dados cadastrados com sucesso!')
                            ->autoClose(10000);
                        return redirect()->intended('/competition');
                    } else {
                        session(['competition' => $competition]);
                        return view('competition.settings');
                    }
                }
            } catch(Exception $e) {
                Log::error('CompetitionsController.saveSettings: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 308)!')
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
        
        if(Category::where('started', '=', true)->count() > 0 || Category::where('finished', '=', true)->count() > 0) {
            Alert::error('Erro', 'A competição já foi iniciada! Não é possível alterar os dados de configuração.')
                ->autoClose(10000);
            $valid = false;
        } else if(!isset($data['competition_name'])) {
            Alert::error('Erro', 'O campo Nome da Bateria deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if(!isset($data['quantity_laps'])) {
            Alert::error('Erro', 'O campo Quantidade de Voltas deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        }

        return $valid;
    }

    private function createGroups($category) {
        try {
            $total_competitors = $category->competitors()->count();
            $quantity_competitors_per_group = 8;
            $quantity_groups = intval($total_competitors/$quantity_competitors_per_group);
            $rest_of_competitors = $total_competitors - ($quantity_competitors_per_group * $quantity_groups);
            
            if($quantity_groups == 0) {
                if($total_competitors <= 2) {
                    $quantity_groups = 1;
                } else {
                    $quantity_groups = 2;
                }
                $quantity_competitors_per_group = intval($total_competitors / $quantity_groups);
                $rest_of_competitors = $total_competitors - ($quantity_competitors_per_group * $quantity_groups);
                
                for ($i=1; $i <= $quantity_groups ; $i++) { 
                    $group = new Group();
                    $group->category_id = $category->id;
                    $group->order = $i;
                    $group->quantity_competitors = $quantity_competitors_per_group;
                    if($i == 1) {
                        $group->quantity_competitors += $rest_of_competitors;
                    }
                    $group->save();
                }
            } else {
                //Verifica se o restante de competidores deixados de fora de um grupo
                //é suficiente para criar um grupo com pelo menos metade da quantidade de um grupo completo
                Log::info('$rest_of_competitors '.$rest_of_competitors);
                Log::info('ceil($quantity_competitors_per_group/2) '.ceil($quantity_competitors_per_group/2));
                while($rest_of_competitors > 0 && $rest_of_competitors < ceil($quantity_competitors_per_group/2)) {
                    $quantity_competitors_per_group--;
                    $quantity_groups = intval($total_competitors/$quantity_competitors_per_group);
                    $rest_of_competitors = $total_competitors - ($quantity_competitors_per_group * $quantity_groups);
                    Log::info('$quantity_competitors_per_group '.$quantity_competitors_per_group);
                    Log::info('$quantity_groups '.$quantity_groups);
                    Log::info('$rest_of_competitors '.$rest_of_competitors);
                }

                Log::info('$quantity_groups '.$quantity_groups);

                for ($i=1; $i <= $quantity_groups; $i++) { 
                    $group = new Group();
                    $group->category_id = $category->id;;
                    $group->order = $i;
                    $group->quantity_competitors = $quantity_competitors_per_group;
                    $group->save();
                }

                if($rest_of_competitors > 0) {
                    $group = new Group();
                    $group->category_id = $category->id;;
                    $group->order = $quantity_groups + 1;
                    $group->quantity_competitors = $rest_of_competitors;
                    $group->save();
                }
            }

            foreach($category->groups()->get() as $group) {
                if($group->quantity_competitors == 1) {
                    $group->delete();

                    $new_group = Group::all()->first();
                    $new_group->quantity_competitors++;
                    $new_group->update();
                }
            }
        } catch(Exception $e) {
            Log::error('CompetitionsController.createGroups: '.$e->getMessage());
            Alert::error('Erro', 'Erro ao executar ação (Código: 309)!')
                ->autoClose(10000);
            
            return redirect()->back();
        }
    }

    private function sortCompetitors($category, $competitors) {
        try {
            $count = 0;
            foreach ($category->groups()->get() as $group) {
                //Adiciona os competidores no grupo
                for ($i=0; $i < $group->quantity_competitors; $i++) { 
                    $group->addCompetitor($competitors[$count]['id']);
                    $count++;
                }

                //Atualiza o competidor com a ordem dele no grupo
                $order = 1;
                foreach($group->competitors()->get() as $competitor) {
                    $competitor->order = $order;
                    $competitor->update();
                    $order++;
                }
            }
        } catch(Exception $e) {
            Log::error('CompetitionsController.sortCompetitors: '.$e->getMessage());
            Alert::error('Erro', 'Erro ao executar ação (Código: 310)!')
                ->autoClose(10000);
            
            return redirect()->back();
        }
    }

    private function isEditable() {
        $editable = true;
        
        $competition = Competition::orderBy('id')->get()->first();
        if(isset($competition) && $competition->started) {
            $editable = false;
        }

        return $editable;
    }

    private function canOrderGroups() {
        if(Category::all()->count() == 0) {
            return false;
        } else {
            foreach (Category::all() as $category) {
                if($category->competitors()->count() == 0) {
                    return false;
                }
            }
        }

        return true;
    }
}
