<div class="wrap-img100"><img src="{{ Session::has('announcer') ? URL::asset(Session::get('announcer')->photo) : URL::asset('img/announcer.jpg') }}"></div>
<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100">Foto</span>
    <input type="file" name="image_announcer">
</div>

<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100">Nome</span>
    <input class="input100" type="text" maxlength="50" name="first_name" value="{{ Session::has('announcer') ? Session::get('announcer')->first_name : '' }}" placeholder="Insira nome">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100 label-long">Sobrenome</span>
    <input class="input100" type="text" maxlength="50" name="last_name" value="{{ Session::has('announcer') ? Session::get('announcer')->last_name : '' }}" placeholder="Insira sobrenome">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100">Apelido</span>
    <input class="input100" type="text" maxlength="20" name="nickname" value="{{ Session::has('announcer') ? Session::get('announcer')->nickname : '' }}" placeholder="Insira apelido">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100">Telefone</span>
    <input class="input100 phone" type="text" name="phone" value="{{ Session::has('announcer') ? Session::get('announcer')->phone : '' }}" placeholder="Insira telefone">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100">Email</span>
    <input class="input100" type="email" maxlength="100" name="email" value="{{ Session::has('announcer') ? Session::get('announcer')->email : '' }}" placeholder="Insira email">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100" style="left: -140px;">Patrocinadores</span>
    <input class="input100" type="text" maxlength="100" name="sponsors" value="{{ Session::has('announcer') ? Session::get('announcer')->sponsors : '' }}" placeholder="Insira patrocinadores (separados por vírgula)">
    <span class="focus-input100"></span>
</div>