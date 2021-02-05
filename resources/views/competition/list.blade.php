@extends('layouts.site')

@section('content')

<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100 d-none d-lg-block">
            <div class="container-form-btn pb-3">
                <button class="form-btn mr-3">
                    <a href="{{ url('/competition/settings') }}">Configurar Campeonato</a>
                </button>

                <button class="form-btn mr-3">
                    <a href="{{ url('/competition/restart') }}">Reiniciar Campeonato</a>
                </button>
            
                @if($groups->count() == 0)
                <button class="form-btn mr-3">
                    <a href="{{ url('/competition/ordergroups') }}">Criar Baterias</a>
                </button>
                @endif
            </div>
            <div>
                <table>
                    <thead>
                        <tr class="table100-head">
                            <th class="column10 text-left">Categorias</th>
                            <th class="column2 pl-3">
                                @if($groups->count() == 0)
                                <a href="{{ url('/categories/add') }}" class="pr-3" title="Adicionar Categoria"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody class="table100-body">
                        @foreach($registers as $register)
                        <tr>
                            <td class="text-left"><p>{{ $register->name }}</p></td>
                            <td class="pl-3">
                                @if($register->groups()->count() > 0 && !$register->started && !$register->finished)
                                <a href="{{ url('/categories/start/'.$register->id) }}" class="pr-3" title="Iniciar"><i class="fa fa-flag" aria-hidden="true"></i></a>
                                @endif
                                <a href="{{ url('/categories/export/'.$register->id) }}" class="pr-3" title="Exportar"><i class="fa fa-print" aria-hidden="true"></i></a>
                                <a href="{{ url('/categories/competitors/'.$register->id) }}" class="pr-3" title="Competidores"><i class="fa fa-user" aria-hidden="true"></i></a>
                                <a href="{{ url('/categories/edit/'.$register->id) }}" class="pr-3" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
								<a href="{{ url('/categories/delete/'.$register->id) }}" class="pr-3" title="Excluir"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                        @endforeach
                        @if($registers->count() == 0)
                        <tr>
                            <td class="column12 text-center">
                                <p>Nenhum registro encontrado.</p>
                            </td>
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
                        height: 80px;
                    }
                </style>
                <button class="form-btn"><a href="{{ url('/competition/settings') }}">Configurar Campeonato</a></button>
                <div></div>
                <button class="form-btn"><a href="{{ url('/competition/restart') }}">Reiniciar Campeonato</a></button>
                <div></div>
                @if($groups->count() == 0)
                <button class="form-btn"><a href="{{ url('/competition/ordergroups') }}">Criar Baterias</a></button>
                <div></div>
                <button class="form-btn"><a href="{{ url('/categories/add') }}">Adicionar Categoria</a></button>
                <div></div>
                @endif
            </div>
            <div>
                <table>
                    <tbody class="table100-body">
                        <style>
                            #th-0:before { content: "Categoria"; padding-right: "10px;"}
                        </style>
                        @foreach($registers as $register)
                        <tr>
                            <td class="column1 first-column" id="th-0">
                                <p class="ml-2">{{ $register->name }}</p>
                            </td>
                            <td class="column1">
                            @if($register->groups()->count() > 0 && !$register->started)
                                <a href="{{ url('/categories/start/'.$register->id) }}" class="pr-3" title="Iniciar"><i class="fa fa-flag" aria-hidden="true"></i></a>
                                @endif
                                @if(!$register->finished)
                                <a href="{{ url('/categories/export/'.$register->id) }}" class="pr-3" title="Exportar"><i class="fa fa-print" aria-hidden="true"></i></a>
                                @endif
                                <a href="{{ url('/categories/competitors/'.$register->id) }}" class="pr-3" title="Competidores"><i class="fa fa-user" aria-hidden="true"></i></a>
                                <a href="{{ url('/categories/edit/'.$register->id) }}" class="pr-3" title="Editar"><i class="fa fa-pencil" aria-hidden="true"></i></a>
								<a href="{{ url('/categories/delete/'.$register->id) }}" class="pr-3" title="Excluir"><i class="fa fa-trash" aria-hidden="true"></i></a>
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