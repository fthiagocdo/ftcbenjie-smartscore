@extends('layouts.site')

@section('content')

<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100 d-none d-lg-block">
            <div>
                <table>
                    <thead>
                        <tr class="table100-head">
                            <th class="column1 text-left">Competidor</th>
                            <th class="column2 pl-3 text-left">Nome</th>
                            <th class="column2 pl-3 text-left">Sobrenome</th>
                            <th class="column2 pl-3 text-left">Apelido</th>
                            <th class="column2 pl-3 text-left">Categoria</th>
                            <th class="column1 pl-3">
                                <a href="{{ url('/competitors/export') }}" class="pr-3" title="Exportar"><i class="fa fa-print" aria-hidden="true"></i></a>
                                @if($editable)
                                <a href="{{ url('/competitors/add') }}" class="pr-3" title="Adicionar"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table100-body">
                        @foreach($registers as $register)
                        <tr>
                            <td class="column1">
                                <img src="{{ URL::asset($register->photo) }}">
                            </td>
                            <td class="pl-3 text-left"><p>{{ $register->first_name }}</p></td>
                            <td class="pl-3 text-left"><p>{{ $register->last_name }}</p></td>
                            <td class="pl-3 text-left"><p>{{ $register->nickname }}</p></td>
                            <td class="pl-3 text-left"><p>{{ $register->categories()->first()->name }}</p></td>
                            <td class="pl-3">
                                @if($editable)
                                <a href="{{ url('/competitors/edit/'.$register->id) }}" class="pr-3" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
								<a href="{{ url('/competitors/delete/'.$register->id) }}" class="pr-3" title="Excluir"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @if($registers->count() == 0)
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="column4 text-right">
                                <p>Nenhum registro encontrado.</p>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="wrap-table100 d-lg-none">
            <div class="container-form-btn center pb-3">
                <style>
                   .container-form-btn div {
                        width: 5px;
                    }

                    @media only screen and (max-width: 320px) {
                        .container-form-btn div {
                            width: 0px;
                            height: 50px;
                            margin-bottom: 20px;
                        }
                    }
                </style>
                <button class="form-btn" pb><a href="{{ url('/competitors/export') }}">Exportar</a></button>
                <div></div>
                @if($editable)
                <button class="form-btn"><a href="{{ url('/competitors/add') }}">Adicionar</a></button>
                @endif
            </div>
            <div>
                <table>
                    <tbody class="table100-body">
                        <style>
                            #th-0:before { content: "Competidor"; padding-right: "10px;"}
                            #th-1:before { content: "Nome"; }
                            #th-2:before { content: "Sobrenome"; }
                            #th-3:before { content: "Apelido"; }
                            #th-4:before { content: "Categoria"; }
                            @media screen and (max-width: 992px) {
                                table tbody tr .first-column {
                                    margin-bottom: 90px;
                                }
                            }
                            @media only screen and (max-width: 479px) {
                                table tbody tr .first-column {
                                    margin-bottom: 50px;
                                }
                            }
                            @media only screen and (max-width: 576px) {
                                table tbody tr .first-column {
                                    margin-bottom: 0px;
                                }
                            }
                        </style>
                        @foreach($registers as $register)
                        <tr>
                            <td class="column1 first-column" id="th-0">
                                <img class="ml-2" src="{{ URL::asset($register->photo) }}">
                            </td>
                            <td class="column2" id="th-1">
                                <p class="ml-2">{{ $register->first_name }}</p>
                            </td>
                            <td class="column2" id="th-2">
                                <p class="ml-2">{{ $register->last_name }}</p>
                            </td>
                            <td class="column2" id="th-3">
                                <p class="ml-2">{{ $register->nickname }}</p>
                            </td>
                            <td class="column2" id="th-3">
                                <p class="ml-2">{{ $register->categories()->first()->name }}</p>
                            </td>
                            <td class="column1">
                                @if($editable)
                                <a href="{{ url('/competitors/edit/'.$register->id) }}" class="pr-3 pl-3" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
								<a href="{{ url('/competitors/delete/'.$register->id) }}" class="pr-3" title="Excluir"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @if($registers->count() == 0)
                        <tr>
                            <td class="column12 text-center" style="padding-left: 0px !important;">
                                Nenhum registro encontrado.
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection