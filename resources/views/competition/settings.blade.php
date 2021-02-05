@extends('layouts.site')

@section('content')

<div class="limiter">
	<div class="container-login100">
        <div class="wrap-login100">
            <div class="pl-2 pt-2 font-weight-bold">
                <a href="{{ url('/competition') }}"><i class="fa fa-arrow-left mr-2" aria-hidden="true"></i>Voltar</a>
            </div>
			<h2 class="pt-2 text-center font-weight-bold">Editar Configuração</h2>
			<form class="login100-form validate-form pt-3 pb-3" method="post" action="{{ route('site.competition.settings.save') }}" >

				{{ csrf_field() }}

                <div class="wrap-input100 validate-input m-b-26 mt-3" data-validate="Nome da Batertia é obrigatório">
                    <span class="label-input100 label-long">Nome da Bateria</span>
                    <input class="input100" type="text" maxlength="50" name="competition_name" value="{{ Session::has('competition') ? Session::get('competition')->competition_name : '' }}" placeholder="Insira nome da bateria">
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 validate-input m-b-26 mt-3" data-validate="Quantidade de voltas é obrigatório">
                    <span class="label-input100 label-long">Quantidade de voltas</span>
                    <input class="input100" type="text" maxlength="50" name="quantity_laps" value="{{ Session::has('competition') ? Session::get('competition')->quantity_laps : '' }}" placeholder="Insira quantidade de voltas">
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 wrap-input100-radio validate-input m-b-26 mt-4" data-validate="Tipo de Pontuação é obrigatório">
                    <span class="label-input100">Tipo de Pontuação</span>
                    <p class="mt-4">
                        <input type="radio" id="total_average" name="score_type" value="total_average" {{ !Session::has('competition') || Session::get('competition')->score_type == 'total_average' ? 'checked' : '' }}>
                        <label for="total_average" class="mr-4">Por Média Total</label>
                        <input type="radio" id="best_lap" name="score_type" value="best_lap" {{ Session::has('competition') && Session::get('competition')->score_type == 'best_lap' ? 'checked' : '' }}>
                        <label for="best_lap">Por Melhor Volta</label>
                    </p>
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