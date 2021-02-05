<?php

namespace App\Http\Controllers;

use Helper;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CompetitorsExport;
use App\Competitor;
use App\Competition;
use App\Category;

class CompetitorsController extends Controller
{
    public function index(Request $request)
    {
        if(auth()->user()->can('list-competitors')){
            $registers = Competitor::orderBy('first_name')->get();

            session(['page' => 'competitors']);
            return view('competitors.list')
                ->with('editable', $this::isEditable())
                ->with('registers', $registers);
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function add(Request $request)
    {
        if(auth()->user()->can('add-competitors')){
            if(Helper::isRandomOrderCompetition()) {
                return redirect()->back();
            } else {
                $categories = Category::all();

                session(['competitor' => null]);
                session(['page' => 'competitors']);
                return view('competitors.add')
                    ->with('categories', $categories);
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function save(Request $request)
    {
        if(auth()->user()->can('add-competitors')){
            if(Helper::isRandomOrderCompetition()) {
                return redirect()->back();
            } else {
                try{
                    $data = $request->all();

                    $competitors = Competitor::orderBy('order')->get();
                    if($competitors->count() > 0) {
                        $order = $competitors->last()->order + 1;
                    } else {
                        $order = 1;
                    }
                    
                    $competitor = new Competitor();
                    $competitor->first_name = $data['first_name'];
                    $competitor->last_name = $data['last_name'];
                    $competitor->nickname = $data['nickname'];
                    $competitor->phone = $data['phone'];
                    $competitor->email = $data['email'];
                    $competitor->sponsors = $data['sponsors'];
                    $competitor->order = $order;
                    
                    if($request->file('image_competitor')){
                        $file = $request->file('image_competitor');
                        $diretorio = 'img/competitors/';
                        $extensao = $file->guessClientExtension();
                        $nomeArquivo = '_img_'.$competitor->email.'_'.time().'.'.$extensao;
                        $file->move($diretorio, $nomeArquivo);
                        $competitor->photo = $diretorio.'/'.$nomeArquivo;
                    } else {
                        $competitor->photo = 'img/competitor.jpg';
                    }
                    
                    if($this->validation($data)) {
                        $competitor->save();
                        $competitor->addCategory($data['category']);

                        $category = Category::find($data['category']); 
                        if($category->groups()->count() > 0) {
                            $category->groups()->get()->last()->addCompetitor($competitor->id);
                        }

                        Alert::success('Info', 'Dados cadastrados com sucesso!')
                            ->autoClose(10000);
                        return redirect()->intended('/competitors');
                    } else {
                        $categories = Category::all();

                        session(['competitor' => $competitor]);
                        return view('competitors.add')
                            ->with('categories', $categories);
                    }
                } catch(Exception $e) {
                    Log::error('CompetitorsController.save: '.$e->getMessage());
                    Alert::error('Erro', 'Erro ao executar ação (Código: 201)!')
                        ->autoClose(10000);
                    
                    return redirect()->intended('/competitors');
                }
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function edit($id)
    {
        if(auth()->user()->can('edit-competitors')){
            $competitor = Competitor::find($id);
            $categories = Category::all();
            
            session(['competitor' => $competitor]);
            session(['page' => 'competitors']);
            return view('competitors.edit')
                ->with('categories', $categories);
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->can('edit-competitors')){
            try{
                $data = $request->all();

                $competitor = Competitor::find($id);
                $competitor->first_name = $data['first_name'];
                $competitor->last_name = $data['last_name'];
                $competitor->nickname = $data['nickname'];
                $competitor->phone = $data['phone'];
                $competitor->email = $data['email'];
                $competitor->sponsors = $data['sponsors'];
                
                if($request->file('image_competitor')){
                    $file = $request->file('image_competitor');
                    $diretorio = 'img/competitors/';
                    $extensao = $file->guessClientExtension();
                    $nomeArquivo = '_img_'.$competitor->email.'_'.time().'.'.$extensao;
                    $file->move($diretorio, $nomeArquivo);
                    $competitor->photo = $diretorio.'/'.$nomeArquivo;
                } 
                
                if($this->validation($data, $id)) {
                    $competitor->update();

                    if(!$competitor->hasCategory($data['category'])) {
                        $competitor->deleteAllCategories();
                        $competitor->addCategory($data['category']);

                        $category = Category::find($data['category']); 
                        if($category->groups()->count() > 0) {
                            $competitor->deleteAllGroups();
                            $category->groups()->get()->last()->addCompetitor($competitor->id);
                        }
                    } else if(!$competitor->hasGroup($data['group'])) {
                        $competitor->deleteAllGroups();
                        $competitor->addGroup($data['group']);
                    }

                    Alert::success('Info', 'Dados atualizados com sucesso!')
                        ->autoClose(10000);
                    return redirect()->intended('/competitors');
                } else {
                    $categories = Category::all();

                    session(['competitor' => $competitor]);
                    return redirect()->intended('/competitors/edit/'.$id)
                        ->with('categories', $categories);
                }
            } catch(Exception $e) {
                Log::error('CompetitorsController.update: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 202)!')
                    ->autoClose(10000);
                
                return redirect()->intended('/competitors');
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function delete($id)
    {
        if(auth()->user()->can('delete-competitors')){
            if(!$this::isEditable()) { 
                Alert::error('Erro', 'A competição já foi iniciada! Não é possível incluir ou alterar dados de competidores.')
                    ->autoClose(10000);
                return redirect()->back();
            } else {
                try{
                    $competitor = Competitor::find($id);
                    $competitor->delete();

                    Alert::success('Info', 'Registro excluído com sucesso!')
                        ->autoClose(10000);
                    return redirect()->intended('/competitors');
                } catch(Exception $e) {
                    Log::error('CompetitorsController.delete: '.$e->getMessage());
                    Alert::error('Erro', 'Erro ao executar ação (Código: 203)!')
                        ->autoClose(10000);
                    return redirect()->back();
                }
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function export() 
    {
        if(auth()->user()->can('list-competitors')){
            try{
                return Excel::download(new CompetitorsExport, time().'_competitors_list.xlsx');
            } catch(Exception $e) {
                Log::error('CompetitorsController.export: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 104)!')
                    ->autoClose(10000);
                return redirect()->back();
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    private function validation($data, $id = null) {
        $valid = true;
        $competitor = Competitor::where('email', '=', $data['email'])->first();
        
        if(!$this::isEditable()) { 
            Alert::error('Erro', 'A competição já foi iniciada! Não é possível incluir ou alterar dados de competidores.')
                ->autoClose(10000);
            $valid = false;
        } else if($id == null && !isset($data['image_competitor'])) { 
            Alert::error('Erro', 'O campo Foto deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if(!isset($data['first_name'])) {
            Alert::error('Erro', 'O campo Nome deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if(!isset($data['last_name'])) {
            Alert::error('Erro', 'O campo Sobrenome deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if(!isset($data['nickname'])) {
            Alert::error('Erro', 'O campo Apelido deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if(!isset($data['phone'])) {
            Alert::error('Erro', 'O campo Telefone deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if(!isset($data['category'])) {
            Alert::error('Erro', 'O campo Categoria deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if(!isset($data['email'])) {
            Alert::error('Erro', 'O campo Email deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            Alert::error('Erro', 'Email inválido!')
                ->autoClose(10000);
            $valid = false;
        //Add
        } else if(isset($data['email']) && $competitor != null && $id == null && $competitor->email == $data['email']) {
            Alert::error('Erro', 'Email já cadastrado!')
                ->autoClose(10000);
            $valid = false;
        //Edit
        } else if(isset($data['email']) && $competitor != null && $competitor->id != $id && $competitor->email == $data['email']) {
            Alert::error('Erro', 'Email já cadastrado!')
                ->autoClose(10000);
            $valid = false;
        }

        return $valid;
    }

    private function isEditable() {
        $editable = true;
        
        $competition = Competition::orderBy('id')->get()->first();
        if(isset($competition) && $competition->started) {
            $editable = false;
        }

        return $editable;
    }
}
