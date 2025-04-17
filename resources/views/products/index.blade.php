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
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Продукты</a></li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title mb-3">
                        <h2 class="mb-0">Продукты</h2>
                    </div>
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
                    <a class="btn btn-primary" href="{{ route('products.create') }}">Добавить</a>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Имя</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @forelse ($products as $u)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>
                                            <a href="{{ route('products.show', $u->id) }}" class="btn btn-info px-2"><i
                                                    class="ti ti-eye-check mx-1"></i></a>
                                            <form action="{{ route('products.destroy', $u->id) }}" method="POST"
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
                                    Продуктов нету!
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ stiped-table ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection
