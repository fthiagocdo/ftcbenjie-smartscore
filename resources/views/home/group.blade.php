@extends('layouts.site')

@section('content')

<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100 d-none d-lg-block">
            <div class="pb-3">
                <table>
                    <thead>
                        <tr class="table100-head">
                            <th class="column1 text-left">Categoria:</th> 
                            <th class="column3">
                                <select name="category" onchange="window.location.href='{{ url('/group') }}' + '?category_id=' + this.options[this.selectedIndex].value;">
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                                </select>
                            </th>   
                            <th class="column1 text-right">Bateria:</th>                        
                            <th class="column1 text-left">
                                <select name="group" onchange="window.location.href='{{ url('/group') }}' + '?category_id={{ $category_id }}&group_id=' + this.options[this.selectedIndex].value;">
                                @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ $group_id == $group->id ? 'selected' : '' }}>{{ $group->order }}</option>
                                @endforeach
                                </select>
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
                            <th class="column1 text-center">Ordem</th>
                            <th class="column6 pr-3 text-left">Competidores</th>
                            @for($i = 1; $i <= $quantity_laps; $i++)
                            <th class="column1 pr-3">Volta {{ $i }}</th>
                            @endfor
                            @if($score_type == 'total_average')
                            <th class="column1 pr-3">Média Total</th>
                            @elseif($score_type == 'best_lap')
                            <th class="column1 pr-3">Melhor Volta</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="table100-body">
                        @foreach($registers as $register)
                        <tr>
                            <td class="column1 text-center">{{ $loop->iteration }}º</td>
                            <td class="column6 pr-3 text-left">
                                <img src="{{ URL::asset($register->photo) }}">
                                <p class="mt-4">{{ $register->name }}<br>{{ $register->nickname }}</p>
                            </td>
                            @foreach($register->total_lap as $lap)
                            <td class="column1 pr-3">{{ number_format($lap->score, 2) }}</td>
                            @endforeach
                            @for($i = $register->total_lap->count(); $i < $quantity_laps; $i++)
                            <td class="column1 pr-3">{{ number_format(0, 2) }}</td>
                            @endfor
                            <td class="column1 pr-3">{{ number_format($register->total_average, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pl-2 pt-2 font-weight-bold">
                <a href="{{ url('/') }}"><i class="fa fa-arrow-left mr-2" aria-hidden="true"></i>Voltar</a>
            </div>
        </div>

        <div class="wrap-table100 d-lg-none">
            <div class="pb-3">
                <table>
                    <tbody class="table100-body table100-body-competition">
                        <style>
                            #th-10:before { content: "Categoria:";}
                            #th-11:before { content: "Bateria:";}
                        </style>
                        <tr>
                            <td class="text-left" id="th-10">
                                <select name="category" style="width: 80%;" onchange="window.location.href='{{ url('/group') }}' + '?category_id=' + this.options[this.selectedIndex].value;">
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-left" id="th-11">
                                <select name="group" style="width: 80%;" onchange="window.location.href='{{ url('/group') }}' + '?category_id={{ $category_id }}&group_id=' + this.options[this.selectedIndex].value;">
                                    @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ $group_id == $group->id ? 'selected' : '' }}>{{ $group->order }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div>
                <table>
                    <tbody class="table100-body">
                        <style>
                            #th-0:before { content: "Competidor"; padding-right: "10px;"}
                            #th-1:before { content: "Ordem"; }
                            #th-2:before { content: "Volta"; }
                            #th-3:before { content: "Nota"; }
                            #th-4:before { content: "Total";}
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
                            <td class="column3 text-left first-column" id="th-0">
                                <img src="{{ URL::asset($register->photo) }}">
                                <p>{{ $register->name }}<br>{{ $register->nickname }}</p>
                            </td>
                            <td class="column1 mt-3" id="th-1">{{ $loop->iteration }}º</td>
                            @foreach($register->total_lap as $lap)
                            <td class="column1" id="th-2">{{ $loop->iteration }}ª</td>
                            <td class="column1" id="th-3">{{ number_format($lap->score, 2) }}</td>
                            @endforeach
                            <td class="column1" id="th-4">{{ number_format($register->total_average, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pl-2 pt-2 font-weight-bold">
                <a href="{{ url('/') }}"><i class="fa fa-arrow-left mr-2" aria-hidden="true"></i>Voltar</a>
            </div>
        </div>
    </div>
</div>

@endsection