@extends('index')

@section('title', $title)

@section('content')
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Главная</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Ингредиенты</a></li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title mb-3">
                        <h2 class="mb-0">Ингредиенты</h2>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li class="mb-0">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('success') != '')
                        <div class="alert alert-success">
                            <svg xmlns="http://www.w3.org/2000/svg" style="display: none">
                                <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z">
                                    </path>
                                </symbol>
                            </svg>
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24">
                                <use xlink:href="#info-fill"></use>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ stiped-table ] start -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#addIngredient" aria-controls="addIngredient">Добавить</button>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Имя</th>
                                    <th>Единица измерения</th>
                                    <th>Вид</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @forelse ($ingredients as $u)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>{{ $u->unit }}</td>
                                        <td>{{ $u->type }}</td>
                                        <td>
                                          <button class="btn btn-info px-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#editIngredient" aria-controls="editIngredient"
                                            onclick="
                                                document.querySelector('#editIngredientForm #id').value = '{{ $u->id }}';
                                                document.querySelector('#editIngredientForm #name').value = '{{ $u->name }}';
                                                document.querySelector('#editIngredientForm #unit').value = '{{ $u->unit }}';
                                            "
                                          ><i class="ti ti-edit-circle mx-1"></i></button>
                                            <form action="{{ route('ingredients.destroy', $u->id) }}" method="POST"
                                                style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger px-2"><i
                                                        class="ti ti-trash mx-1"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @empty
                                    Сотрудников нету!
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ stiped-table ] end -->
    </div>


    <div class="offcanvas offcanvas-end" style="width: 450px" tabindex="-1" id="addIngredient"
        aria-labelledby="addIngredientLabel">
        <div class="offcanvas-header">
            <h5 id="addIngredientLabel">Добавить ингредиент</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('ingredients.store') }}" method="POST" id="addIngredientForm">
                @csrf

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label">Название</label>
                        <input type="text" class="form-control" name="name" placeholder="Введите название">
                    </div>
                </div>

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label text-lg-end">Единица измерения</label>
                        <div class="col-12">
                            <select class="form-control" name="unit" id="choices-single-default">
                                <option selected disabled>Выбрать ед.измерения</option>
                                <option>кг</option>
                                <option>литр</option>
                                <option>шт</option>
                                <option>метр</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label text-lg-end">Вид ингредиента</label>
                        <div class="col-12">
                            <select class="form-control" name="type" id="choices-single-default">
                                <option selected disabled>Выбрать вид</option>
                                <option>сырье</option>
                                <option>специя</option>
                                <option>cкрытое</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary col-12">Сохранить</button>
            </form>

        </div>
    </div>

    <div class="offcanvas offcanvas-end" style="width: 450px" tabindex="-1" id="editIngredient"
        aria-labelledby="editIngredientLabel">
        <div class="offcanvas-header">
            <h5 id="editIngredientLabel">Добавить ингредиент</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form method="POST" id="editIngredientForm" action="{{ route('ingredients.update') }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="id" id="id">

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label">Название</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Введите название">
                    </div>
                </div>

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label text-lg-end">Единица измерения</label>
                        <div class="col-12">
                            <select class="form-control" name="unit" id="unit">
                                <option selected disabled>Выбрать ед.измерения</option>
                                <option>кг</option>
                                <option>литр</option>
                                <option>шт</option>
                                <option>метр</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label text-lg-end">Вид ингредиента</label>
                        <div class="col-12">
                            <select class="form-control" name="type" id="choices-single-default">
                                <option selected disabled>Выбрать вид</option>
                                <option>сырье</option>
                                <option>специя</option>
                                <option>cкрытое</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary col-12">Сохранить</button>
            </form>

        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection
