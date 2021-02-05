@extends('layouts.site')

@section('content')

<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100 d-none d-lg-block">
            <div class="pl-2 pb-2 font-weight-bold">
                <a href="{{ url('/competition') }}"><i class="fa fa-arrow-left mr-2" aria-hidden="true"></i>Voltar</a>
            </div>
            <div class="pb-3">
                <table>
                    <thead>
                        <tr class="table100-head">
                            <th class="column3 text-left">Categoria: <span style="color: white">{{ $category->name }}</span></th>                           
                            <th class="column2 text-left">
                                @if(isset($group_id))
                                <select name="group" onchange="window.location.href='{{ url('/categories/competitors/'.$category->id) }}' + '?group=' + this.options[this.selectedIndex].value;">
                                @foreach($category->groups()->get() as $group)
                                <option value="{{ $group->id }}" {{ $group_id == $group->id ? 'selected' : '' }}>Bateria {{ $group->order }}</option>
                                @endforeach
                                </select>
                                @endif
                            </th>
                            <th class="column7 text-left"></th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div>
                <table>
                    <thead>
                        <tr class="table100-head">
                            <th class="column1 text-left">Ordem</th>
                            <th class="column8 text-left">Competidores</th>
                            <th class="column1 text-left"></th>                            
                        </tr>
                    </thead>
                    <tbody class="table100-body">
                        @foreach($registers as $register)
                        <tr>
                            @if(isset($group_id))
                            <td class="text-left pl-5">{{ $loop->index + 1 }}</td>
                            @else
                            <td class="-left pl-5"></td>
                            @endif
                            <td class="text-left">
                                <img src="{{ URL::asset($register->photo) }}">
                                <p class="mt-4">{{ $register->first_name }} {{ $register->last_name }}<br>{{ $register->nickname }}</p>
                            </td>
                            <td class="pl-3">
                                @if(!$category->finished && $register->released)
                                <a href="#" class="pr-3" title="Pontuação Liberada"><i class="fa fa-check-circle-o" aria-hidden="true"></i></a>
                                @elseif(!$category->finished && $register->id == $actual_competitor)
                                <a href="{{ url('/categories/releasecompetitor/'.$register->id) }}" class="pr-3" title="Liberar Pontuação"><i class="fa fa-circle-o" aria-hidden="true"></i></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @if($registers->count() == 0)
                        <tr>
                            <td></td>
                            <td class="text-center">
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
            <div class="pl-2 pb-2 font-weight-bold">
                <a href="{{ url('/competition') }}"><i class="fa fa-arrow-left mr-2" aria-hidden="true"></i>Voltar</a>
            </div>
            <div class="pb-3">
                <table>
                    <tbody class="table100-body table100-body-competition">
                        <style>
                            #th-10:before { content: "Categoria";}
                            #th-11:before { content: "Bateria";}
                        </style>
                        <tr>
                            <td class="text-left" id="th-10"><p class="ml-2">{{ $category->name }}</p></td>
                            @if(isset($group_id))
                            <td class="text-left" id="th-11">
                                <select style="width: 90%" onchange="window.location.href='{{ url('/categories/competitors/'.$category->id) }}' + '?group=' + this.options[this.selectedIndex].value;">
                                @foreach($category->groups()->get() as $group)
                                <option value="{{ $group->id }}" {{ $group_id == $group->id ? 'selected' : '' }}>Bateria {{ $group->order }}</option>
                                @endforeach
                                </select>
                            </td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>

            <div>
                <table>
                    <tbody class="table100-body table100-body-competition">
                        <style>
                                #th-0:before { content: "Competidor";}
                                #th-2:before { content: "Ordem";}
                                #th-3:before { content: "Liberado?";}
                                @media screen and (max-width: 992px) {
                                    table tbody tr .first-column {
                                        margin-bottom: 70px;
                                    }
                                }
                                @media only screen and (max-width: 479px) {
                                    table tbody tr .first-column {
                                        margin-bottom: 50px;
                                    }
                                }
                                @media only screen and (max-width: 576px) {
                                    table tbody tr .first-column {
                                        margin-bottom: 30px;
                                    }
                                }
                        </style>
                        @foreach($registers as $register)
                        <tr>
                            <td class="column3 first-column text-left" id="th-0">
                                <img class="ml-2" src="{{ URL::asset($register->photo) }}">
                                <p class="ml-2 pt-2">{{ $register->first_name }} {{ $register->last_name }}<br>{{ $register->nickname }}</p>
                            </td>
                            @if(isset($group_id))
                            <td class="column1 text-left" id="th-2"><p class="ml-2">{{ $loop->index + 1 }}</p></td>
                            @else
                            <td class="column1 text-left" id="th-2"><p class="ml-2"></p></td>
                            @endif
                            @if(!$category->finished && $register->released)
                            <td class="column1" id="th-3">
                                <a href="#" class="pl-2"><i class="fa fa-check-circle-o" aria-hidden="true"></i></a>
                            </td>
                            @elseif(!$category->finished && $register->id == $actual_competitor)
                            <td class="column1" id="th-3">
                                <a href="{{ url('/categories/releasecompetitor/'.$register->id) }}" class="pl-2"><i class="fa fa-circle-o" aria-hidden="true"></i></a>
                            </td>
                            @endif
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