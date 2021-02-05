@extends('layouts.site')

@section('content')

<div class="limiter">
	<div class="container-login100">
        <div class="wrap-login100">
            <div class="pl-2 pt-2 font-weight-bold">
                <a href="{{ url('/competition') }}"><i class="fa fa-arrow-left mr-2" aria-hidden="true"></i>Voltar</a>
            </div>
			<h2 class="pt-2 text-center font-weight-bold">Editar Categoria</h2>
			<form class="login100-form validate-form pt-3 pb-3" method="post" action="{{ route('site.categories.update', Session::get('category')->id) }}">

				{{ csrf_field() }}

				@include('categories._form')

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