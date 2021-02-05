<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100">Nome</span>
    <input class="input100" type="text" maxlength="100" name="name" value="{{ Session::has('category') ? Session::get('category')->name : '' }}" placeholder="Insira nome">
    <span class="focus-input100"></span>
</div>