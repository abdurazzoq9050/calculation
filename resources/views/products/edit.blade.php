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
                        <li class="breadcrumb-item"><a href="javascript: void(0)">Продукты</a></li>
                        <li class="breadcrumb-item" aria-current="page">Изменить продукт</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Изменить продукт</h2>
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
                    @if ($errors->any())
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
                    <div class="accordion" id="accordionExample">
                        <!-- Accordion Item 1 -->
                        <div class="accordion-item">
                            {{-- data-bs-target="#collapseOne" --}}
                            <h2 class="accordion-header" id="headingOne">
                                <button style="font-size: 30px; font-weight:bolder; position: relative !important;" class="accordion-button" type="button"
                                    data-bs-toggle="collapse" aria-expanded="true" aria-controls="collapseOne">
                                    <h3
                                        onclick="this.parentNode.parentNode.querySelector('#productName').classList.remove('d-none');">
                                        {{ $product->name }} <i class="ti ti-edit-circle text-primary"></i> </h3>
                                        <div style="position: absolute; right: 80px;transform: translateY(-40%); top: 50%;">
                                            <h3>Итого: {{ $totalPrice }} смн</h3>
                                        </div>
                                </button>
                                <div class="d-flex align-items-center px-4 pt-3 d-none" id="productName">
                                    <form action="{{ route('products.update') }}" method="POST" class="d-flex w-100">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="id" value="{{ $product->id }}">
                                        <input type="text" class="form-control me-1" name="name" style="width: 200px;"
                                            value="{{ $product->name }}">
                                        <button class="btn px-2 rounded-circle"
                                            onclick="this.parentNode.parentNode.querySelector('#productName').classList.remove('d-none');"><i
                                                class="ti ti-circle-check text-primary"
                                                style="font-size: 30px"></i></button>
                                    </form>
                                </div>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">

                                    <div class="d-flex" style="flex-direction: column">
                                        <h3 class="d-flex justify-content-between">
                                                <div>Сырье</div>
                                                <div>Итого веса: {{ $totalQuantity['siryo'] }} кг</div>
                                        </h3>
                                        <div class="card card-body">
                                            <div class="d-flex w-100">
                                                <p class="m-0 col-3 fw-bold">Ингредиент</p>
                                                <p class="m-0 col-2 fw-bold">Кол-во</p>
                                                <p class="m-0 col-3 fw-bold">Цена</p>
                                                <p class="m-0 col-3 fw-bold">Сумма</p>
                                                <div class="m-0 col-1 fw-bold">
                                                    Действия
                                                </div>
                                            </div>
                                        </div>
                                        @forelse ($product->recipes as $r)
                                        @php
                                                if($r->ingredient->type!='сырье'){
                                                    continue;
                                                }
                                            @endphp
                                            <div class="card card-body">
                                                <div class="d-flex w-100">
                                                    <p class="m-0 col-3">{{ $r->ingredient->name }}</p>
                                                    <p class="m-0 col-2">{{ $r->quantity }} {{ $r->ingredient->unit }}</p>
                                                    <p class="m-0 col-3">{{ $r->price }} смн</p>
                                                    <p class="m-0 col-3">{{ $r->amount }} смн</p>
                                                    <div class="m-0 col-1">
                                                        <button class="btn btn-primary" type="button"
                                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                                            aria-controls="offcanvasRight"
                                                            onclick="
                                                            document.querySelector('#editRecipe #product_id').value = {{ $product->id }};
                                                            document.querySelector('#editRecipe #ingredient_id').value = {{ $r->ingredient->id }};
                                                            document.querySelector('#editRecipe #unit').value = '{{ $r->ingredient->unit }}';
                                                            document.querySelector('#editRecipe #count').value = {{ $r->quantity }};
                                                            document.querySelector('#editRecipe #price').value = {{ $r->price }};
                                                            document.querySelector('#editRecipe #summary').value = {{ $r->amount }};
                                                            document.querySelector('#editRecipe').setAttribute('action', '{{ route('recipe.update', $r->id) }}');
                                                            ">
                                                            <i class="ti ti-edit"></i>
                                                        </button>
                                                        <form action="{{ route('recipe.destroy', $r->id) }}" method="POST"
                                                            class="d-inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger"><i
                                                                    class="ti ti-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            Рецепта нету
                                        @endforelse
                                        <h3 class="d-flex justify-content-between">
                                            <div>Специи</div>
                                            <div>Итого веса: {{ $totalQuantity['specias'] }} кг</div>
                                        </h3>
                                        <div class="card card-body">
                                            <div class="d-flex w-100">
                                                <p class="m-0 col-3 fw-bold">Ингредиент</p>
                                                <p class="m-0 col-2 fw-bold">Кол-во</p>
                                                <p class="m-0 col-3 fw-bold">Цена</p>
                                                <p class="m-0 col-3 fw-bold">Сумма</p>
                                                <div class="m-0 col-1 fw-bold">
                                                    Действия
                                                </div>
                                            </div>
                                        </div>
                                        @forelse ($product->recipes as $r)
                                            @php
                                                if($r->ingredient->type=='сырье'){
                                                    continue;
                                                }
                                            @endphp
                                            <div class="card card-body">
                                                <div class="d-flex w-100">
                                                    <p class="m-0 col-3">{{ $r->ingredient->name }}</p>
                                                    <p class="m-0 col-2">{{ $r->quantity }} {{ $r->ingredient->unit }}</p>
                                                    <p class="m-0 col-3">{{ $r->price }} смн</p>
                                                    <p class="m-0 col-3">{{ $r->amount }} смн</p>
                                                    <div class="m-0 col-1">
                                                        <button class="btn btn-primary" type="button"
                                                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
                                                            aria-controls="offcanvasRight"
                                                            onclick="
                                                            document.querySelector('#editRecipe #product_id').value = {{ $product->id }};
                                                            document.querySelector('#editRecipe #ingredient_id').value = {{ $r->ingredient->id }};
                                                            document.querySelector('#editRecipe #unit').value = '{{ $r->ingredient->unit }}';
                                                            document.querySelector('#editRecipe #count').value = {{ $r->quantity }};
                                                            document.querySelector('#editRecipe #price').value = {{ $r->price }};
                                                            document.querySelector('#editRecipe #summary').value = {{ $r->amount }};
                                                            document.querySelector('#editRecipe').setAttribute('action', '{{ route('recipe.update', $r->id) }}');
                                                            ">
                                                            <i class="ti ti-edit"></i>
                                                        </button>
                                                        <form action="{{ route('recipe.destroy', $r->id) }}" method="POST"
                                                            class="d-inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger"><i
                                                                    class="ti ti-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            Рецепта нету
                                        @endforelse
                                    </div>
                                    <button class="btn  w-auto px-2 rounded-circle" type="button"
                                        data-bs-toggle="offcanvas" data-bs-target="#addRecipe" aria-controls="addRecipe">
                                        <i class="ti ti-circle-plus" style="font-size: 30px"></i>
                                    </button>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end" style="width: 450px" tabindex="-1" id="offcanvasRight"
        aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 id="offcanvasRightLabel">Изменить строку рецепта</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="editRecipe" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" id="product_id" name="product_id">

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label text-lg-end">Ингредиент</label>
                        <div class="col-12">
                            <select class="form-control" data-trigger id="choices-single-default">
                                <option value="">Выбрать ингридиент</option>
                                @forelse ($ingredients as $i)
                                    <option>{{ $i->name }}</option>
                                @empty
                                    <option value="" disabled selected>Ингредиенты не найдены</option>
                                @endforelse
                            </select>
                            <input type="hidden" id="unit">
                            <input type="hidden" id="ingredient_id" name="ingredient_id">
                        </div>
                    </div>
                </div>

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label">Количество</label>
                        <input type="number" step="any" id="count" oninput="updateByCount(this)"
                            class="form-control" name="quantity" placeholder="Введите количество">
                    </div>
                </div>

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label">Цена</label>
                        <input type="number" step="any" id="price" oninput="updateByPrice(this)"
                            class="form-control" name="price" placeholder="Введите цена">
                    </div>
                </div>

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label">Сумма</label>
                        <input type="number" readonly id="summary" class="form-control" placeholder="Введите сумма">
                    </div>
                </div>
                <button class="btn btn-primary col-12">Сохранить</button>
            </form>

        </div>
    </div>
    <div class="offcanvas offcanvas-end" style="width: 450px" tabindex="-1" id="addRecipe"
        aria-labelledby="addRecipeLabel">
        <div class="offcanvas-header">
            <h5 id="addRecipeLabel">Добавить строку рецепта</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('recipe.store') }}" method="POST" id="addRecipeForm">
                @csrf
                <input type="hidden" id="product_id" name="product_id" value="{{ $product->id }}">

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label text-lg-end">Ингредиент</label>
                        <div class="col-12">
                            <select class="form-control" data-trigger id="choices-single-default">
                                <option value="">Выбрать ингридиент</option>
                                @forelse ($ingredients as $i)
                                    <option>{{ $i->name }}</option>
                                @empty
                                    <option value="" disabled selected>Ингредиенты не найдены
                                    </option>
                                @endforelse
                            </select>
                            <input type="hidden" id="unit">
                            <input type="hidden" id="ingredient_id" name="ingredient_id">
                        </div>
                    </div>
                </div>

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label">Количество</label>
                        <input type="number" step="any" id="count" oninput="updateByCount(this)"
                            class="form-control" name="quantity" placeholder="Введите количество">
                    </div>
                </div>

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label">Цена</label>
                        <input type="number" step="any" id="price" oninput="updateByPrice(this)"
                            class="form-control" name="price" placeholder="Введите цена">
                    </div>
                </div>

                <div class="w-100">
                    <div class="form-group">
                        <label class="form-label">Сумма</label>
                        <input type="number" readonly id="summary" class="form-control" placeholder="Введите сумма">
                    </div>
                </div>

                <button class="btn btn-primary col-12">Сохранить</button>
            </form>

        </div>
    </div>

    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            ingredients = {{ Js::from($ingredients) }};

            initChoices();

        });

        function initChoices() {
            var genericExamples = document.querySelectorAll('[data-trigger]');

            for (i = 0; i < genericExamples.length; ++i) {
                var element = genericExamples[i];
                new Choices(element, {
                    placeholderValue: 'Выберите ингредиент',
                    searchPlaceholderValue: 'Поиск...'
                });

                element.addEventListener('change', function() {
                    let parent = this.parentNode.parentNode.parentNode;
                    let option = this.options[0];
                    let unit = parent.querySelector('#unit');
                    let ingredient = parent.querySelector('#ingredient_id');
                    unit.value = ingredients.find((i) => i.name == option.value).unit;
                    ingredient.value = ingredients.find((i) => i.name == option.value).id;
                    parent.querySelector('.choices__item.choices__item--selectable').innerHTML += " (" + unit
                        .value + ")";
                    let prapra = parent.parentNode.parentNode.parentNode
                    let count = prapra.querySelector('#count')
                    if (count.value != "") {
                        updateByCount(count)
                    }
                });
            }
        }

        updateByCount = (e) => {
            let parent = e.parentNode.parentNode.parentNode;
            let unit = parent.querySelector('#unit')
            let summary = parent.querySelector('#summary')
            let price = parent.querySelector('#price')
            console.log(unit.value)
            summary.value = e.value * price.value
        }
        updateByPrice = (e) => {
            let parent = e.parentNode.parentNode.parentNode;
            let unit = parent.querySelector('#unit')
            let summary = parent.querySelector('#summary')
            let count = parent.querySelector('#count')
            console.log(unit.value)
            summary.value = e.value * count.value
        }
    </script>

@endsection
