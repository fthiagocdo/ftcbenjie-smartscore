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
                                <select name="category" onchange="window.location.href='{{ url('/individual/0') }}' + '?category_id=' + this.options[this.selectedIndex].value;">
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                                </select>
                            </th>   
                            <th class="column8 text-left"></th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div>
                <div class="row">
                    <div class="col-lg-9">
                        <table class="mb-3">
                            <thead>
                                <tr class="table100-head">
                                    <th class="column5 pl-3 text-left">Competidor</th>
                                    <th class="column7"></th>
                                </tr>
                            </thead>
                            <tbody class="table100-body">
                                <tr>
                                    <td>
                                        <img style="width: 500px; height: 500px;" src="{{ URL::asset($competitor->photo) }}">
                                    </td>
                                    <td>
                                        <p class="text-left font-weight-bold" style="font-size: 50px">{{ $competitor->first_name }} {{ $competitor->last_name }}</p>
                                        <p class="text-left" style="font-size: 50px">{{ $competitor->nickname }}</p>
                                        <p class="text-center mt-5" style="font-size: 50px">Pontuação Geral</p>
                                        <p class="text-center  font-weight-bold" style="font-size: 100px">{{ number_format($total_average, 2) }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table>
                            <thead>
                                <tr class="table100-head">
                                    <th class="column1 text-center">Volta</th>
                                    @for($i = 1; $i <= $judges->count(); $i++)
                                    <th class="column1 pr-3 text-center">Juiz {{ $i }}</th>
                                    @endfor
                                    <th class="column1 pr-3 text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody class="table100-body">
                                <tr>
                                    <td class="text-center" style="font-size: 50px">{{ $actual_lap_number }}</td>
                                    @foreach($actual_lap as $lap)
                                        <td class="pr-3 text-center" style="font-size: 50px">{{ number_format($lap->score, 2) }}</td>
                                    @endforeach
                                    <td class="pr-3 text-center" style="font-size: 50px">{{ number_format($total, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-3">
                        <table>
                            <thead>
                                <tr class="table100-head">
                                    <th class="column10 text-left pl-3">Rank Geral</th>
                                    <th class="column1 text-center"></th>
                                </tr>
                            </thead>
                            <tbody class="table100-body">
                                @foreach($registers as $register)
                                <tr>
                                    <td class="column10 pr-3 text-left">
                                        <a href="{{ url('/individual/'.$register->id) }}">
                                            <img src="{{ URL::asset($register->photo) }}"></>
                                        </a>
                                        <p class="mt-4">{{ $register->nickname }}
                                    </td>
                                    <td class="column1 pr-3 text-left">
                                        {{ number_format($register->total_average, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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
                            #th-10:before { content: "Categoria";}
                        </style>
                        <tr>
                            <td class="text-left" id="th-10">
                                <select name="category" style="width: 80%;" onchange="window.location.href='{{ url('/individual/0') }}' + '?category_id=' + this.options[this.selectedIndex].value;">
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                            #th-1:before { content: "Volta"; }
                            #th-2:before { content: "Juiz"; }
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
                        <tr>
                            <td class="column3 text-left first-column" id="th-0">
                                <img src="{{ URL::asset($competitor->photo) }}">
                                <p>{{ $competitor->first_name }} {{ $competitor->last_name }}<br>{{ $competitor->nickname }}</p>
                            </td>
                            <td class="column1 mt-3" id="th-1">{{ $actual_lap_number }}</td>
                            @foreach($actual_lap as $lap)
                            <td class="column1" id="th-2">{{ $loop->iteration }}</td>
                            <td class="column1" id="th-3">{{ number_format($lap->score, 2) }}</td>
                            @endforeach
                            <td class="column1" id="th-4">{{ number_format($total, 2) }}</td>
                        </tr>
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