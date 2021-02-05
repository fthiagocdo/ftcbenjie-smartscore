<div class="wrap-img100"><img src="{{ Session::has('competitor') ? URL::asset(Session::get('competitor')->photo) : URL::asset('img/competitor.jpg') }}"></div>
<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100">Foto</span>
    <input type="file" name="image_competitor">
</div>

<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100">Nome</span>
    <input class="input100" type="text" maxlength="50" name="first_name" value="{{ Session::has('competitor') ? Session::get('competitor')->first_name : '' }}" placeholder="Insira nome">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100 label-long">Sobrenome</span>
    <input class="input100" type="text" maxlength="50" name="last_name" value="{{ Session::has('competitor') ? Session::get('competitor')->last_name : '' }}" placeholder="Insira sobrenome">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100">Apelido</span>
    <input class="input100" type="text" maxlength="20" name="nickname" value="{{ Session::has('competitor') ? Session::get('competitor')->nickname : '' }}" placeholder="Insira apelido">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100">Telefone</span>
    <input class="input100 phone" type="text" name="phone" value="{{ Session::has('competitor') ? Session::get('competitor')->phone : '' }}" placeholder="Insira telefone">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100">Email</span>
    <input class="input100" type="email" maxlength="100" name="email" value="{{ Session::has('competitor') ? Session::get('competitor')->email : '' }}" placeholder="Insira email">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3">
    <span class="label-input100" style="left: -140px;">Patrocinadores</span>
    <input class="input100" type="text" maxlength="100" name="sponsors" value="{{ Session::has('competitor') ? Session::get('competitor')->sponsors : '' }}" placeholder="Insira patrocinadores (separados por vírgula)">
    <span class="focus-input100"></span>
</div>

<div class="wrap-input100 m-b-26 mt-3" style="border-bottom: none;">
    <span class="label-input100" style="left: -140px;">Categoria</span>
    <select name="category" onchange="$('#div_group').hide();">
        <option value="">-- Selecione opção --</option>
        @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ Session::has('competitor') && Session::get('competitor')->hasCategory($category->id) ? 'selected' : '' }}>{{ $category->name }}</option>
        @endforeach
    </select>
</div>

@if(Session::has('competitor') && Session::get('competitor')->categories()->count() > 0)
<div  id="div_group" class="wrap-input100 m-b-26 mt-3" style="border-bottom: none;">
    <span class="label-input100" style="left: -140px;">Bateria</span>
    <select name="group">
        <option value="">-- Selecione opção --</option>
        @foreach($category->groups()->get() as $group)
        <option value="{{ $group->id }}" {{ Session::has('competitor') && Session::get('competitor')->hasGroup($group->id) ? 'selected' : '' }}>Bateria {{ $group->order }}</option>
        @endforeach
    </select>
</div>
@endif