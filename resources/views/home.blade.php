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
                    <a href="{{ url('/group/1') }}">Rank Por Bateria</a>
                </button>
                <button class="form-btn ml-3">
                    <a href="{{ url('/individual/') }}">Rank Individual</a>
                </button>
            </div>
            <div>
                <table>
                    <thead>
                        <tr class="table100-head">
                            <th class="column1 text-center">Posição</th>
                            <th class="column6 pr-3 text-left">Competidores</th>
                            <th class="column1 pr-3">Volta</th>
                            <th class="column1 pr-3">Média Total</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="wrap-table100 d-lg-none">
            <div>
                <table>
                    <tbody class="table100-body">
                        <tr>
                            <style>#th-0:before { content: "Competidor";}</style>
                            <td class="column3 text-left first-column" id="th-0">
                                <img src="img/authors/1.jpg">
                                <p>Nome do Competidor<br>Apelido</p>
                            </td>
                            <style>#th-1:before { content: "1º Juiz";}</style>
                            <td class="column1" id="th-1">7.0</td>
                            <style>#th-2:before { content: "2º Juiz";}</style>
                            <td class="column1" id="th-2">7.0</td>
                            <style>#th-3:before { content: "3º Juiz";}</style>
                            <td class="column1" id="th-3">7.0</td>
                            <style>#th-4:before { content: "4º Juiz";}</style>
                            <td class="column1" id="th-4">7.0</td>
                            <style>#th-5:before { content: "Total";}</style>
                            <td class="column1" id="th-5">28.0</td>
                        </tr>
                        <tr>
                            <style>#th-0:before { content: "Competidor";}</style>
                            <td class="column3 text-left first-column" id="th-0">
                                <img src="img/authors/2.jpg">
                                <p>Nome do Competidor<br>Apelido</p>
                            </td>
                            <style>#th-1:before { content: "1º Juiz";}</style>
                            <td class="column1" id="th-1">7.0</td>
                            <style>#th-2:before { content: "2º Juiz";}</style>
                            <td class="column1" id="th-2">7.0</td>
                            <style>#th-3:before { content: "3º Juiz";}</style>
                            <td class="column1" id="th-3">7.0</td>
                            <style>#th-4:before { content: "4º Juiz";}</style>
                            <td class="column1" id="th-4">7.0</td>
                            <style>#th-5:before { content: "Total";}</style>
                            <td class="column1" id="th-5">28.0</td>
                        </tr>
                        <tr>
                            <style>#th-0:before { content: "Competidor";}</style>
                            <td class="column3 text-left first-column" id="th-0">
                                <img src="img/authors/3.jpg">
                                <p>Nome do Competidor<br>Apelido</p>
                            </td>
                            <style>#th-1:before { content: "1º Juiz";}</style>
                            <td class="column1" id="th-1">7.0</td>
                            <style>#th-2:before { content: "2º Juiz";}</style>
                            <td class="column1" id="th-2">7.0</td>
                            <style>#th-3:before { content: "3º Juiz";}</style>
                            <td class="column1" id="th-3">7.0</td>
                            <style>#th-4:before { content: "4º Juiz";}</style>
                            <td class="column1" id="th-4">7.0</td>
                            <style>#th-5:before { content: "Total";}</style>
                            <td class="column1" id="th-5">28.0</td>
                        </tr>
                        <tr>
                            <style>#th-0:before { content: "Competidor";}</style>
                            <td class="column3 text-left first-column" id="th-0">
                                <img src="img/authors/4.jpg">
                                <p>Nome do Competidor<br>Apelido</p>
                            </td>
                            <style>#th-1:before { content: "1º Juiz";}</style>
                            <td class="column1" id="th-1">7.0</td>
                            <style>#th-2:before { content: "2º Juiz";}</style>
                            <td class="column1" id="th-2">7.0</td>
                            <style>#th-3:before { content: "3º Juiz";}</style>
                            <td class="column1" id="th-3">7.0</td>
                            <style>#th-4:before { content: "4º Juiz";}</style>
                            <td class="column1" id="th-4">7.0</td>
                            <style>#th-5:before { content: "Total";}</style>
                            <td class="column1" id="th-5">28.0</td>
                        </tr>
                        <tr>
                            <style>#th-0:before { content: "Competidor";}</style>
                            <td class="column3 text-left first-column" id="th-0">
                                <img src="img/authors/5.jpg">
                                <p>Nome do Competidor<br>Apelido</p>
                            </td>
                            <style>#th-1:before { content: "1º Juiz";}</style>
                            <td class="column1" id="th-1">7.0</td>
                            <style>#th-2:before { content: "2º Juiz";}</style>
                            <td class="column1" id="th-2">7.0</td>
                            <style>#th-3:before { content: "3º Juiz";}</style>
                            <td class="column1" id="th-3">7.0</td>
                            <style>#th-4:before { content: "4º Juiz";}</style>
                            <td class="column1" id="th-4">7.0</td>
                            <style>#th-5:before { content: "Total";}</style>
                            <td class="column1" id="th-5">28.0</td>
                        </tr>
                        <tr>
                            <style>#th-0:before { content: "Competidor";}</style>
                            <td class="column3 text-left first-column" id="th-0">
                                <img src="img/authors/6.jpg">
                                <p>Nome do Competidor<br>Apelido</p>
                            </td>
                            <style>#th-1:before { content: "1º Juiz";}</style>
                            <td class="column1" id="th-1">7.0</td>
                            <style>#th-2:before { content: "2º Juiz";}</style>
                            <td class="column1" id="th-2">7.0</td>
                            <style>#th-3:before { content: "3º Juiz";}</style>
                            <td class="column1" id="th-3">7.0</td>
                            <style>#th-4:before { content: "4º Juiz";}</style>
                            <td class="column1" id="th-4">7.0</td>
                            <style>#th-5:before { content: "Total";}</style>
                            <td class="column1" id="th-5">28.0</td>
                        </tr>
                        <tr>
                            <style>#th-0:before { content: "Competidor";}</style>
                            <td class="column3 text-left first-column" id="th-0">
                                <img src="img/authors/7.jpg">
                                <p>Nome do Competidor<br>Apelido</p>
                            </td>
                            <style>#th-1:before { content: "1º Juiz";}</style>
                            <td class="column1" id="th-1">7.0</td>
                            <style>#th-2:before { content: "2º Juiz";}</style>
                            <td class="column1" id="th-2">7.0</td>
                            <style>#th-3:before { content: "3º Juiz";}</style>
                            <td class="column1" id="th-3">7.0</td>
                            <style>#th-4:before { content: "4º Juiz";}</style>
                            <td class="column1" id="th-4">7.0</td>
                            <style>#th-5:before { content: "Total";}</style>
                            <td class="column1" id="th-5">28.0</td>
                        </tr>
                        <tr>
                            <style>#th-0:before { content: "Competidor";}</style>
                            <td class="column3 text-left first-column" id="th-0">
                                <img src="img/authors/8.jpg">
                                <p>Nome do Competidor<br>Apelido</p>
                            </td>
                            <style>#th-1:before { content: "1º Juiz";}</style>
                            <td class="column1" id="th-1">7.0</td>
                            <style>#th-2:before { content: "2º Juiz";}</style>
                            <td class="column1" id="th-2">7.0</td>
                            <style>#th-3:before { content: "3º Juiz";}</style>
                            <td class="column1" id="th-3">7.0</td>
                            <style>#th-4:before { content: "4º Juiz";}</style>
                            <td class="column1" id="th-4">7.0</td>
                            <style>#th-5:before { content: "Total";}</style>
                            <td class="column1" id="th-5">28.0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection