<?php

namespace App\Http\Controllers;

use Helper;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\JudgesExport;
use App\User;
use App\Judge;
use App\Competition;


class JudgesController extends Controller
{   
    public function index(Request $request)
    {
        if(auth()->user()->can('list-judges')){
            $registers = Judge::orderBy('id')->get();

            session(['page' => 'judges']);
            return view('judges.list')
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
        if(auth()->user()->can('add-judges')){
            if(Helper::isRandomOrderCompetition()) {
                return redirect()->back();
            } else {
                session(['user' => null]);
                session(['judge' => null]);
                session(['page' => 'judges']);
                return view('judges.add');
            }
            
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function save(Request $request)
    {
        if(auth()->user()->can('add-judges')){
            if(Helper::isRandomOrderCompetition()) {
                return redirect()->back();
            } else {
                try{
                    $data = $request->all();
                    
                    $user = new User();
                    $user->name = $data['name'];
                    $user->email = $data['email'];
                    $user->password = bcrypt($data['password']);
                    
                    if($this->validation($data)) {
                        $user->save();

                        $role = $user->addRole('JUDGE');

                        $judge = new Judge();
                        $judge->user_id = $user->id;
                        if($request->file('image_judge')){
                            $file = $request->file('image_judge');
                            $diretorio = 'img/judges/';
                            $extensao = $file->guessClientExtension();
                            $nomeArquivo = '_img_'.$user->email.'_'.time().'.'.$extensao;
                            $file->move($diretorio, $nomeArquivo);
                            $judge->photo = $diretorio.'/'.$nomeArquivo;
                        } else {
                            $judge->photo = 'img/judges/judge.jpg';
                        }
                        $judge->save();

                        Alert::success('Info', 'Dados cadastrados com sucesso!')
                            ->autoClose(10000);
                        return redirect()->intended('/judges');
                    } else {
                        $request->session()->flash('user', $user);
                        return redirect()->back();
                    }
                } catch(Exception $e) {
                    Log::error('JudgesController.save: '.$e->getMessage());
                    Alert::error('Erro', 'Erro ao executar ação (Código: 101)!')
                        ->autoClose(10000);
                    
                    $request->session()->flash('user', $user);
                    return redirect()->back();
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
        if(auth()->user()->can('edit-judges')){
            $judge = Judge::find($id);
            
            session(['user' => $judge->user]);
            session(['judge' => $judge]);
            session(['page' => 'judges']);
            return view('judges.edit');
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->can('edit-judges')){
            try{
                $data = $request->all();

                $judge = Judge::find($id);
                $user = $judge->user;

                $data['original_email'] = $user->email;
                if($data['password'] != '') {
                    $user->password = bcrypt($data['password']);
                }
                $user->name = $data['name'];
                $user->email = $data['email'];
                
                
                if($this->validation($data, $id)) {
                    $user->update();

                    if($request->file('image_judge')){
                        $file = $request->file('image_judge');
                        $diretorio = 'img/judges/';
                        $extensao = $file->guessClientExtension();
                        $nomeArquivo = '_img_'.$user->email.'_'.time().'.'.$extensao;
                        $file->move($diretorio, $nomeArquivo);
                        $judge->photo = $diretorio.'/'.$nomeArquivo;
                    }
                    $judge->update();

                    Alert::success('Info', 'Dados atualizados com sucesso!')
                        ->autoClose(10000);
                    return redirect()->intended('/judges/edit/'.$id);
                } else {
                    $request->session()->flash('user', $user);
                    return redirect()->back();
                }
            } catch(Exception $e) {
                Log::error('JudgesController.update: '.$e->getMessage());
                Alert::error('Erro', 'Erro ao executar ação (Código: 102)!')
                    ->autoClose(10000);
                
                $request->session()->flash('user', $user);
                return redirect()->back();
            }
        }else{
            Alert::error('Erro', 'Este usuário não possui autorização para esta ação!')
                ->autoClose(10000);
            return redirect()->intended('/');
        }
    }

    public function delete($id)
    {
        if(auth()->user()->can('delete-judges')){
            if(!$this::isEditable()) { 
                Alert::error('Erro', 'A competição já foi iniciada! Não é possível incluir ou alterar dados de competidores.')
                    ->autoClose(10000);
                $valid = false;
            } else {
                try{
                    $user = Judge::find($id)->user;
                    $user->delete();

                    Alert::success('Info', 'Registro excluído com sucesso!')
                        ->autoClose(10000);
                    return redirect()->intended('/judges');
                } catch(Exception $e) {
                    Log::error('JudgesController.delete: '.$e->getMessage());
                    Alert::error('Erro', 'Erro ao executar ação (Código: 103)!')
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
        if(auth()->user()->can('list-judges')){
            try{
                return Excel::download(new JudgesExport, time().'_judges_list.xlsx');
            } catch(Exception $e) {
                Log::error('JudgesController.export: '.$e->getMessage());
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

        if(!$this::isEditable()) { 
            Alert::error('Erro', 'A competição já foi iniciada! Não é possível incluir ou alterar dados de competidores.')
                ->autoClose(10000);
            $valid = false;
        } else if($id == null && !isset($data['image_judge'])) { 
            Alert::error('Erro', 'O campo Foto deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if(!isset($data['name'])) {
            Alert::error('Erro', 'O campo Nome deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if(!isset($data['email'])) {
            Alert::error('Erro', 'Os campo Email deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if($id == null && !isset($data['password'])) {
            Alert::error('Erro', 'O campo Password deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if($id == null && !isset($data['password_confirmation'])) {
            Alert::error('Erro', 'O campo Confirmar Password deve ser preenchido!')
                ->autoClose(10000);
            $valid = false;
        } else if($data['password'] != $data['password_confirmation']) {
            Alert::error('Erro', 'Password e Confirmar Password não coincidem!')
                ->autoClose(10000);
            $valid = false;
        } else if ($data['password'] != '' && strlen($data['password']) < 4) {
            Alert::error('Erro', 'Password deve ter no mínimo 4 caracteres!')
                ->autoClose(10000);
            $valid = false;
        } else if(User::where('email', '=', $data['email'])->first()) {
            if($id == null || $data['original_email'] != $data['email']) {
                Alert::error('Erro', 'Email já cadastrado!')
                ->autoClose(10000);
                $valid = false;
            }
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
