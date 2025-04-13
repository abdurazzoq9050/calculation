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
                        <li class="breadcrumb-item" aria-current="page">Добавить продукт</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Добавить продукт</h2>
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
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Имя</label>
                                    <input type="text" class="form-control" name="name" placeholder="Введите имя">
                                </div>
                                <button type="submit" class="btn btn-primary mb-4 w-auto float-end">Сохранить</button>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h3>Итого: <span id="total"></span></h3>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="trow d-flex w-100 justify-content-center">

                                    <div class="col-3">
                                        <div class="form-group">
                                            <label class="form-label text-lg-end">Ингредиент</label>
                                            <div class="col-12">
                                                <select class="form-control" data-trigger 
                                                    id="choices-single-default">
                                                    <option value="">Выбрать ингридиент</option>
                                                    @forelse ($ingredients as $i)
                                                        <option>{{ $i->name }}</option>
                                                    @empty
                                                        <option value="" disabled selected>Ингредиенты не найдены
                                                        </option>
                                                    @endforelse
                                                </select>
                                                <input type="hidden" id="unit">
                                                <input type="hidden" id="ingredient_id" name="ingredient_id[]">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group mx-2">
                                            <label class="form-label">Количество</label>
                                            <input type="number" step="any" id="count"
                                                oninput="updateByCount(this)" class="form-control" name="quantity[]"
                                                placeholder="Введите количество">
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group mx-2">
                                            <label class="form-label">Цена</label>
                                            <input type="number" step="any" id="price"
                                                oninput="updateByPrice(this)" class="form-control" name="price[]"
                                                placeholder="Введите цена">
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group">
                                            <label class="form-label">Сумма</label>
                                            <input type="number" readonly id="summary" class="form-control" placeholder="Введите сумма">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="h2 float-right">
                                <i class="ti ti-circle-plus" onclick="addRow()"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
    <script>
        let ingredients;
        document.addEventListener('DOMContentLoaded', function() {

            ingredients = {{ Js::from($ingredients) }};

            initChoices();
            
            document.querySelector('#total').innerHTML = "Узнать";
            document.querySelector('#total').style.cursor = "pointer";
            document.querySelector('#total').classList.add('text-primary');
            document.addEventListener('click', ()=>{
                let total = 0;
                document.querySelectorAll('#summary').forEach((e) => {
                    if (e.value != "") {
                        total += parseFloat(e.value);
                    }
                })
                if(total > 0){
                    document.querySelector('#total').innerHTML = total + " смн";
                    document.querySelector('#total').classList.remove('text-primary');
                    document.querySelector('#total').classList.add('text-success');
                }
            })
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
                    parent.querySelector('.choices__item.choices__item--selectable').innerHTML += " (" + unit.value + ")";
                    let prapra = parent.parentNode.parentNode.parentNode
                    let count = prapra.querySelector('#count')
                    if (count.value != "") {
                        updateByCount(count)
                    }
                });
            }
        }

        // document.getElementById('choices-single-default').addEventListener('change', function() {
        //     let parent = this.parentNode.parentNode.parentNode;
        //     let option = this.options[0];
        //     let unit = parent.querySelector('#unit');
        //     unit.value = ingredients.find((i) => i.id == this.value).unit;
        //     let prapra = parent.parentNode.parentNode.parentNode
        //     let count = prapra.querySelector('#count')
        //     if (count.value != "") {
        //         updateByCount(count)
        //     }
        // });

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

        function addRow() {

            let currentValues = [];
            document.querySelectorAll('.trow input').forEach(function(element) {
                currentValues.push({
                    name: element.name,
                    value: element.value
                });
            });


            let trow = document.querySelector('.trow');
            let parent = trow.parentNode;
            let i = parent.children.length;
            parent.insertAdjacentHTML('beforeend', `<div class="trow d-flex w-100 justify-content-center">
                                    
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label class="form-label text-lg-end">Ингредиент</label>
                                            <div class="col-12">
                                                <select class="form-control" data-trigger 
                                                    id="choices-single-default">
                                                    <option value="">Выбрать ингридиент</option>
                                                    @forelse ($ingredients as $i)
                                                        <option>{{ $i->name }}</option>
                                                    @empty
                                                        <option value="" disabled selected>Ингредиенты не найдены
                                                        </option>
                                                    @endforelse
                                                </select>
                                                <input type="hidden" id="unit">
                                                <input type="hidden" id="ingredient_id" name="ingredient_id[]">
                                            </div>
                                          </div>
                                    </div>
                                    
                                    <div class="col-3">
                                        <div class="form-group mx-2">
                                            <label class="form-label">Количество</label>
                                            <input type="number" step="any" id="count" oninput="updateByCount(this)" class="form-control" name="quantity[]"
                                                placeholder="Введите количество">
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group mx-2">
                                            <label class="form-label">Цена</label>
                                            <input type="number" step="any" id="price" oninput="updateByPrice(this)" class="form-control" name="price[]"
                                                placeholder="Введите цена">
                                        </div>
                                    </div>
                                    
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label class="form-label">Сумма</label>
                                            <input type="number" readonly id="summary" class="form-control" name="amount[]"
                                                placeholder="Введите сумма">
                                        </div>
                                    </div>
                                </div>`);
            initChoices()
        }
    </script>
    <!-- [ form-element ] end -->
@endsection
