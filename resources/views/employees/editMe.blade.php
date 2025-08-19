@extends('index')

@section('title', $title)

@section('content')
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../dashboard/index.html">Главная</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Профиль</a></li>
                        <li class="breadcrumb-item" aria-current="page">{{ $title }}</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">{{ $title }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li class="mb-0">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.update', $user->id ) }}" method="post">
                      @method('PATCH')
                      @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Имя</label>
                                    <input type="text" class="form-control" name="name" value="{{ $user->name }}" placeholder="Введите имя">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Логин</label>
                                    <input type="text" class="form-control" name="login" value="{{ $user->login }}" placeholder="Введите логин">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Номер телефона</label>
                                    <input type="text" class="form-control" name="phone" value="{{ $user->phone }}" placeholder="Введите номер телефона">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Фамилия</label>
                                    <input type="text" class="form-control" name="surname" value="{{ $user->surname }}" placeholder="Введите фамилию">
                                </div>
                                <div class="form-group">
                                  <label class="form-label">Пароль</label>
                                  <input type="password" class="form-control" name="password" placeholder="Введите пароль">
                              </div>
                                <button type="submit" class="btn btn-primary mb-4 float-end">Сохранить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- [ form-element ] end -->
@endsection
