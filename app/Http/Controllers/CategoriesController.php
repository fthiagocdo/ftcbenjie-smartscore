<?php

namespace App\Http\Controllers;

use Helper;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoriesExport;
use App\Category;
use App\Group;
use App\Competitor;
use App\Competition;

class CategoriesController extends Controller
{
    public function add(Request $request)
    {
        if(auth()->user()->can('add-categories')){
            session(['category' => null]);
            session(['page' => 'competition']);
            return view('categories.add');
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function save(Request $request)
    {
        if(auth()->user()->can('add-categories')){
            try{
                $data = $request->all();

                $category = new Category();
                $category->name = $data['name'];
                
                if($this->validation($data)) {
                    $category->save();

                    Alert::success('Info', 'Dados cadastrados com sucesso!')
                        ->autoClose(10000);
                    return redirect()->intended('/competition');
                } else {
                    session(['category' => $category]);
                    return view('categories.add');
                }
            } catch(Exception $e) {
                Log::error('CategoriesController.save: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 1001)!')
                    ->autoClose(10000);
                
                return redirect()->intended('/competition');
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function edit($id)
    {
        if(auth()->user()->can('edit-categories')){
            $category = Category::find($id);
            
            session(['category' => $category]);
            session(['page' => 'competition']);
            return view('categories.edit');
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->can('edit-categories')){
            try{
                $data = $request->all();

                $category = Category::find($id);
                $category->name = $data['name'];
                
                if($this->validation($data)) {
                    $category->update();

                    Alert::success('Info', 'Dados atualizados com sucesso!')
                        ->autoClose(10000);
                    return redirect()->intended('/competition');
                } else {
                    session(['category' => $category]);
                    return redirect()->intended('/categories/edit/'.$id);
                }
            } catch(Exception $e) {
                Log::error('CategoriesController.update: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 1002)!')
                    ->autoClose(10000);
                
                return redirect()->intended('/competition');
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/competition');
        }
    }

    public function delete($id)
    {
        if(auth()->user()->can('delete-categories')){
            try{
                $category = Category::find($id);

                if($category->started) {
                    Alert::question(
                        $title = 'Exlcuir Categoria',
                        $text = 'Esta categoria está em andamento. Deseja continuar?'
                    )
                    ->showCancelButton($btnText = '<a href="/categories/confirmdelete/'.$id.'">Sim</a>')
                    ->showConfirmButton($btnText = '<a href="#">Não</a>')
                    ->showCloseButton()
                    ->autoClose(false);
                    
                    session(['page' => 'competition']);
                    return redirect()->back();
                } else {
                    Alert::question(
                        $title = 'Exlcuir Categoria',
                        $text = 'Esta categoria será excluída. Deseja continuar?'
                    )
                    ->showCancelButton($btnText = '<a href="/categories/confirmdelete/'.$id.'">Sim</a>')
                    ->showConfirmButton($btnText = '<a href="#">Não</a>')
                    ->showCloseButton()
                    ->autoClose(false);
                    
                    session(['page' => 'competition']);
                    return redirect()->back();
                }
            } catch(Exception $e) {
                Log::error('CompetitionsController.restart: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 1003)!')
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

    public function confirmDelete($id)
    {
        if(auth()->user()->can('delete-categories')) {
            try {
                $category = Category::find($id);

                if($category->competitors()->count() > 0) {
                    Alert::error('Erro', 'Esta categoria possui competidores cadastrados!')
                        ->autoClose(10000);
                    return redirect()->intended('/competition');
                } else {
                    $category->delete();

                    Alert::success('Info', 'Registro excluído com sucesso!')
                        ->autoClose(10000);
                    return redirect()->intended('/competition');
                }
            } catch(Exception $e) {
                Log::error('CategoriesController.delete: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 1004)!')
                    ->autoClose(10000);
                return redirect()->back();
            }
        } else {
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function start($id)
    {
        if(auth()->user()->can('edit-categories')){
            try{
                if(Competition::all()->count() == 0) {
                    Alert::info('Competição não configurada', 'É necessário configurar o campeonato antes de iniciar as categorias.')
                        ->autoClose(10000);
                    
                    session(['page' => 'competition']);
                    return redirect()->back();
                } else if(Category::where('started', '=', true)->get()->first()) {
                    Alert::info('Categoria em andamento', 'Conclua ou exclua a categoria em andamento antes de iniciar a próxima. ')
                        ->autoClose(10000);
                
                    session(['page' => 'competition']);
                    return redirect()->back();
                } else {
                    Alert::question(
                        $title = 'Iniciar Categoria',
                        $text = 'Após esta ação, os competidores desta categoria estarão aptos para receber pontuação dos juízes. Deseja Continuar?'
                    )
                        ->showCancelButton($btnText = '<a href="/categories/confirmstart/'.$id.'">Sim</a>')
                        ->showConfirmButton($btnText = '<a href="#">Não</a>')
                        ->showCloseButton()
                        ->autoClose(false);
                    
                    session(['page' => 'competition']);
                    return redirect()->back();
                }
            } catch(Exception $e) {
                Log::error('CategoriesController.start: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 1005)!')
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

    public function confirmStart($id)
    {
        if(auth()->user()->can('edit-categories')){
            try{
                $category = Category::find($id);
                $category->started = true;
                $category->update();

                Helper::setVariable('actual_lap_number', 1);
                Helper::setVariable('actual_category', $category->id);
                Helper::setVariable('actual_group', $category->groups()->get()->first()->id);
                Helper::setVariable('quantity_groups', $category->groups()->count());
                    
                Alert::success('Info', 'Categoria iniciada com sucesso!')
                    ->autoClose(10000);
                return redirect()->intended('/categories/competitors/'.$id);
            } catch(Exception $e) {
                Log::error('CategoriesController.start: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 1006)!')
                    ->autoClose(10000);
                
                return redirect()->back();
            }
        } else {
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function export($id) 
    {
        if(auth()->user()->can('list-categories')){
            try{
                $category = Category::find($id);
                $fileName = strtolower(time().'_'.str_replace(' ', '_', $category->name).'_list.xlsx');
                return Excel::download(new CategoriesExport($category->id), $fileName);
            } catch(Exception $e) {
                Log::error('CategoriesController.export: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 1007)!')
                    ->autoClose(10000);
                return redirect()->back();
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function competitors($category_id)
    {
        if(auth()->user()->can('edit-categories')){
            try {
                $category = Category::find($category_id);
                $registers = $category->competitors()->orderBy('order')->get();
                $actual_competitor = null;
                
                if($category->groups()->count() > 0) {
                    $group_id = request('group');
                    if(!isset($group_id)) {
                        $group_id = $category->groups()->first()->id;
                    }
                    
                    $registers = Group::find($group_id)->competitors()->orderBy('order')->get();

                    $actual_competitor = Helper::getVariable('actual_competitor');
                    if($actual_competitor == null && $category->started && !$category->finished) {
                        $actual_competitor = Group::find($group_id)->competitors()->get()->first()->id;
                        Helper::setVariable('actual_competitor', $actual_competitor);
                    }
                } else {
                    $group_id = null;
                }
                
                session(['page' => 'competition']);
                return view('categories.competitors')
                    ->with('category', $category)
                    ->with('registers', $registers)
                    ->with('actual_competitor', $actual_competitor)
                    ->with('group_id', $group_id);
            } catch(Exception $e) {
                Log::error('CategoriesController.competitors: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 1008)!')
                    ->autoClose(10000);
                return redirect()->back();
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function releaseCompetitor($competitor_id)
    {
        if(auth()->user()->can('edit-categories')){
            try{
                $competitor = Competitor::find($competitor_id);
                if(isset($competitor)) {
                    $competitor->released = true;
                    $competitor->update();
                }

                Alert::success('Info', 'Competidor '.$competitor->first_name.' '.$competitor->last_name.' liberado para pontuação!')
                        ->autoClose(10000);
                session(['page' => 'competition']);
                return redirect()->intended('/categories/competitors/'.
                    $competitor->categories()->get()->first()->id.
                    '?group='.$competitor->groups()->get()->first()->id);
            } catch(Exception $e) {
                Log::error('CategoriesController.releaseCompetitor: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 1009)!')
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
        
        if(!isset($data['name'])) {
            Alert::error('Erro', 'O campo Nome deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        }

        return $valid;
    }
}
