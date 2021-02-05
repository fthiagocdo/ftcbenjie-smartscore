@extends('layouts.site')

@section('content')

<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100 d-none d-lg-block">
            <div class="container-form-btn pb-3">
                <button class="form-btn">
                    <a href="#" class="active">Rank Geral</a>
                </button>
                <button class="form-btn ml-3">
                    <a href="{{ url('/group') }}">Rank Por Bateria</a>
                </button>
                <button class="form-btn ml-3">
                    <a href="{{ url('/individual/'.$actual_competitor_id) }}">Rank Individual</a>
                </button>
            </div>

            @if(isset($category_id))
            <div class="pb-3">
                <table>
                    <thead>
                        <tr class="table100-head">
                            <th class="column1 text-left">Categoria:</th>
                            <th class="column3 text-left"> 
                                <select name="category" onchange="window.location.href='{{ url('/') }}?category_id=' + this.options[this.selectedIndex].value;">
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                                </select>
                            </th>
                            <th class="column8"></th>
                        </tr>
                    </thead>
                </table>
            </div>
            @endif

            <div>
                <table>
                    <thead>
                        <tr class="table100-head">
                            <th class="column1 text-center">Posição</th>
                            <th class="column6 pr-3 text-left">Competidores</th>
                            @for($i = 1; $i <= $quantity_laps; $i++)
                            <th class="column1 pr-3">Volta {{ $i }}</th>
                            @endfor
                            @if($score_type == 'total_average')
                            <th class="column1 pr-3">Média Total</th>
                            @elseif($score_type == 'best_lap')
                            <th class="column1 pr-3">Melhor Volta</th>
                            @else
                            <th class="column1 pr-3"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="table100-body">
                        @foreach($registers as $register)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}º</td>
                            <td class="pr-3 text-left">
                                <img src="{{ URL::asset($register->photo) }}">
                                <p class="mt-4">{{ $register->name }}<br>{{ $register->nickname }}</p>
                            </td>
                            @foreach($register->total_lap as $lap)
                            <td class="pr-3">{{ number_format($lap->score, 2) }}</td>
                            @endforeach
                            @for($i = $register->total_lap->count(); $i < $quantity_laps; $i++)
                            <td class="pr-3">{{ number_format(0, 2) }}</td>
                            @endfor
                            <td class="pr-3">{{ number_format($register->total_average, 2) }}</td>
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
            <div class="container-form-btn container-form-competition-btn">
                <button class="form-btn ml-2 mb-3">
                    <a href="#" class="active">Rank Geral</a>
                </button>
                <button class="form-btn ml-2 mb-3">
                    <a href="{{ url('/group/1') }}">Rank Por Bateria</a>
                </button>
            </div>

            @if(isset($category_id))
            <div class="pb-3">
                <table>
                    <tbody class="table100-body table100-body-competition">
                        <style>
                            #th-10:before { content: "Categoria";}
                        </style>
                        <tr>
                            <td class="text-left" id="th-10">
                                <select name="category" style="width: 80%;" onchange="window.location.href='{{ url('/') }}?category_id=' + this.options[this.selectedIndex].value;">
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif

            <div>
                <table>
                    <tbody class="table100-body">
                        <style>
                            #th-0:before { content: "Competidor"; padding-right: "10px;"}
                            #th-1:before { content: "Posição"; }
                            #th-2:before { content: "Volta"; }
                            #th-3:before { content: "Nota"; }
                            #th-4:before { content: "Total";}
                            #th-5:before { content: "Individual";}
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
                            <td class="column1" id="th-5">
                                <a href="{{ url('/individual/'.$register->id) }}" class="pl-2"><i class="fa fa-eye" aria-hidden="true"></i></a>
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