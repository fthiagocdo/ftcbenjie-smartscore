@extends('layouts.site')

@section('content')

<div class="limiter">
	<div class="container-login100">
        <div class="wrap-login100">
			<h2 class="pt-2 text-center font-weight-bold">Pontuação do Competidor</h2>
			<form class="login100-form validate-form pt-3 pb-3" method="post" action="{{ route('site.score.save') }}">

				{{ csrf_field() }}

                <div class="wrap-img100"><img src="{{ Session::has('competitor') ? URL::asset(Session::get('competitor')->photo) : URL::asset('img/competitors/competitor.jpg') }}"></div>
                <div class="m-b-3 mt-3" style="width: 100%">
                    <p><b>Nome: </b>{{ Session::has('competitor') ? Session::get('competitor')->first_name.' '.Session::get('competitor')->last_name : '' }}</p></td>
                    <p><b>Apelido: </b>{{ Session::has('competitor') ? Session::get('competitor')->nickname : '' }}</p></td>
                    <p><b>Volta: </b>{{ Session::has('lap_number') ? Session::get('lap_number') : '' }}</p></td>
                </div>
                <div class="wrap-input100 validate-input m-b-26 mt-3" data-validate="Pontuação é obrigatório">
                    <span class="label-input100">Pontuação</span>
                    <input class="input100 decimal" type="text" maxlength="4" name="score" placeholder="Insira pontuação">
                    <span class="focus-input100"></span>
                </div>

                <div class="container-form-btn center-button pt-3">
                    <button class="form-btn">
                        Confirmar
                    </button>
                </div>
			</form>
		</div>
	</div>
</div>

@endsection