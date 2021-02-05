<?php

namespace App\Http\Controllers;

use Helper;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AnnouncersExport;
use App\Announcer;
use App\Competition;

class AnnouncersController extends Controller
{
    public function index(Request $request)
    {
        if(auth()->user()->can('list-announcers')){
            $registers = Announcer::all();

            session(['page' => 'announcers']);
            return view('announcers.list')
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
        if(auth()->user()->can('add-announcers')){
            if(Helper::isRandomOrderCompetition()) {
                return redirect()->back();
            } else {
                session(['announcer' => null]);
                session(['page' => 'announcers']);
                return view('announcers.add');
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function save(Request $request)
    {
        if(auth()->user()->can('add-announcers')){
            if(Helper::isRandomOrderCompetition()) {
                return redirect()->back();
            } else {
                try{
                    $data = $request->all();
                    
                    $announcer = new Announcer();
                    $announcer->first_name = $data['first_name'];
                    $announcer->last_name = $data['last_name'];
                    $announcer->nickname = $data['nickname'];
                    $announcer->phone = $data['phone'];
                    $announcer->email = $data['email'];
                    $announcer->sponsors = $data['sponsors'];
                    
                    if($request->file('image_announcer')){
                        $file = $request->file('image_announcer');
                        $diretorio = 'img/announcers/';
                        $extensao = $file->guessClientExtension();
                        $nomeArquivo = '_img_'.$announcer->email.'_'.time().'.'.$extensao;
                        $file->move($diretorio, $nomeArquivo);
                        $announcer->photo = $diretorio.'/'.$nomeArquivo;
                    } else {
                        $announcer->photo = 'img/announcer.jpg';
                    }
                    
                    if($this->validation($data)) {
                        $announcer->save();

                        Alert::success('Info', 'Dados cadastrados com sucesso!')
                            ->autoClose(10000);
                        return redirect()->intended('/announcers');
                    } else {
                        session(['announcer' => $announcer]);
                        return view('announcers.add');
                    }
                } catch(Exception $e) {
                    Log::error('AnnouncersController.save: '.$e->getMessage());
                    Alert::error('Erro', 'Erro ao executar ação (Código: 201)!')
                        ->autoClose(10000);
                    
                    return view('announcers.list');
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
        if(auth()->user()->can('edit-announcers')){
            $announcer = Announcer::find($id);
            
            session(['announcer' => $announcer]);
            session(['page' => 'announcers']);
            return view('announcers.edit');
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->can('edit-announcers')){
            try{
                $data = $request->all();

                $announcer = Announcer::find($id);
                $announcer->first_name = $data['first_name'];
                $announcer->last_name = $data['last_name'];
                $announcer->nickname = $data['nickname'];
                $announcer->phone = $data['phone'];
                $announcer->email = $data['email'];
                $announcer->sponsors = $data['sponsors'];
                
                if($request->file('image_announcer')){
                    $file = $request->file('image_announcer');
                    $diretorio = 'img/announcers/';
                    $extensao = $file->guessClientExtension();
                    $nomeArquivo = '_img_'.$announcer->email.'_'.time().'.'.$extensao;
                    $file->move($diretorio, $nomeArquivo);
                    $announcer->photo = $diretorio.'/'.$nomeArquivo;
                } 
                
                if($this->validation($data, $id)) {
                    $announcer->update();

                    Alert::success('Info', 'Dados atualizados com sucesso!')
                        ->autoClose(10000);
                    return redirect()->intended('/announcers');
                } else {
                    session(['announcer' => $announcer]);
                    return redirect()->intended('/announcers/edit/'.$id);
                }
            } catch(Exception $e) {
                Log::error('AnnouncersController.update: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 202)!')
                    ->autoClose(10000);
                
                return view('announcers.list');
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function delete($id)
    {
        if(auth()->user()->can('delete-announcers')){
            if(!$this::isEditable()) { 
                Alert::error('Erro', 'A competição já foi iniciada! Não é possível incluir ou alterar dados de competidores.')
                    ->autoClose(10000);
                return redirect()->back();
            } else {
                try{
                    $announcer = Announcer::find($id);
                    $announcer->delete();

                    Alert::success('Info', 'Registro excluído com sucesso!')
                        ->autoClose(10000);
                    return redirect()->intended('/announcers');
                } catch(Exception $e) {
                    Log::error('AnnouncersController.delete: '.$e->getMessage());
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
        if(auth()->user()->can('list-announcers')){
            try{
                return Excel::download(new AnnouncersExport, time().'_announcers_list.xlsx');
            } catch(Exception $e) {
                Log::error('AnnouncersController.export: '.$e->getMessage());
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
        $announcer = Announcer::where('email', '=', $data['email'])->first();
        
        if(!$this::isEditable()) { 
            Alert::error('Erro', 'A competição já foi iniciada! Não é possível incluir ou alterar dados de locutores.')
                ->autoClose(10000);
            $valid = false;
        } else if($id == null && !isset($data['image_announcer'])) { 
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
        } else if(!isset($data['email'])) {
            Alert::error('Erro', 'O campo Email deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            Alert::error('Erro', 'Email inválido!')
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
