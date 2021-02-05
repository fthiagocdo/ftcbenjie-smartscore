@extends('layouts.site')

@section('content')

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <form class="login100-form validate-form" method="post" action="{{ route('site.login.authenticate') }}">

                {{csrf_field()}}

                <div class="wrap-input100 m-b-26" data-validate="Usuário é obrigatório">
                    <span class="label-input100">Usuário</span>
                    <input class="input100" type="text" name="email" placeholder="Insira seu usuário">
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 m-b-18" data-validate = "Password é obrigatório">
                    <span class="label-input100">Password</span>
                    <input class="input100" type="password" name="password" placeholder="Insira seu password">
                    <span class="focus-input100"></span>
                </div>

                <div class="container-form-btn center-button pt-3">
                    <button class="form-btn">
                        Acessar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection