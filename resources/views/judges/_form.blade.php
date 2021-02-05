<div class="wrap-img100"><img src="{{ Session::has('judge') ? URL::asset(Session::get('judge')->photo) : URL::asset('img/judge.jpg') }}"></div>
<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100">Foto</span>
    <input type="file" name="image_judge">
</div>

<div class="wrap-input100 m-b-26 mt-3" data-validate="Nome é obrigatório">
    <span class="label-input100">Nome</span>
    <input class="input100" type="text" maxlength="100" name="name" value="{{ Session::has('user') ? Session::get('user')->name : '' }}" placeholder="Insira nome">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3" data-validate="Usuário é obrigatório">
    <span class="label-input100">Usuário</span>
    <input class="input100" type="text" maxlength="50" name="email" value="{{ Session::has('user') ? Session::get('user')->email : '' }}" placeholder="Insira usuário">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3" data-validate="Password é obrigatório">
    <span class="label-input100">Password</span>
    <input class="input100" type="password" maxlength="20" name="password" placeholder="Insira password">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3" data-validate="Confirmar Password é obrigatório">
    <span class="label-input100">Confirmar Password</span>
    <input class="input100" type="password" maxlength="20" name="password_confirmation" placeholder="Confirme password">
    <span class="focus-input100"></span>
</div>