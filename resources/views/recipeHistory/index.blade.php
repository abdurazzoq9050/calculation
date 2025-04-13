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
                        <li class="breadcrumb-item"><a href="javascript: void(0)">История рецептов</a></li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title mb-3">
                        <h2 class="mb-0">История рецептов</h2>
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
            <div  class="d-flex justify-content-center mb-4">
                <input type="text" class="form-control me-3 w-25" id="myInp" placeholder="Поиск по имени...">
                <button class="btn btn-primary" onclick="showProduct(this.parentNode)">Показать историю</button>
            </div>
            <div class="card">
                <div class="card-header">
                </div>
                <div class="card-body table-border-style">
                    <h3 id="productTitle"></h3>

                    <div class="card card-body" id="productHistory" style="display: none;">
                    </div> 
                </div>
            </div>
        </div>
        <!-- [ stiped-table ] end -->
    </div>
    <!-- [ Main Content ] end -->


    <script>
        function showProduct(div){
            let input = div.querySelector("#myInp").value;
            let products = {{ Js::from($products) }};
            let recipeH = {{ Js::from($recipeH) }};

            let find = products.find(product => product.name.toLowerCase().includes(input.toLowerCase()));

            let filtered = recipeH.filter(recipe => recipe.product.id === find.id);

            let productTitle = document.getElementById("productTitle");
            productTitle.innerHTML = find.name;
            let productHistory = document.getElementById("productHistory");
            productHistory.style.display = "block";
            let recipes = find.recipes.histories
            productHistory.innerHTML = `
                        <div class="d-flex w-100 border-bottom pb-2 alert">
                            <p class="m-0 col-2 fw-bolder">Дата</p>
                            <p class="m-0 col-2 fw-bolder">Ингредиент</p>
                            <p class="m-0 col-2 fw-bolder">Количество до</p>
                            <p class="m-0 col-2 fw-bolder">Количество после</p>
                            <p class="m-0 col-1 fw-bolder">Цена до</p>
                            <p class="m-0 col-1 fw-bolder">Цена после</p>
                            <p class="m-0 col-1 fw-bolder">Сумма до</p>
                            <p class="m-0 col-1 fw-bolder">Сумма после</p>
                        </div>`;
                        
            filtered.forEach(element => {
                let date = new Date(element.created_at).toLocaleString('ru-RU', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });

                total_price_before = element.price_before * element.quantity_before;
                
                total_price_after = element.price_after * element.quantity_after;

                console.log(element)

                let div =   `<div class="d-flex w-100 border-bottom py-2 alert ${ element.quantity_after == null && element.price_after == null ? 'alert-success' : '' }">
                                <p class="m-0 col-2">${ date }</p>
                                <p class="m-0 col-2">${ element.ingredient.name }</p>
                                <p class="m-0 col-2">${ element.quantity_before } ${ element.ingredient.unit }</p>
                                <p class="m-0 col-2">${ element.quantity_after!=null ? element.quantity_after : "" } ${ element.ingredient.unit }</p>
                                <p class="m-0 col-1"><span>${ element.price_before }</span> смн</p>
                                <p class="m-0 col-1"><span>${ element.price_after !=null ? element.price_after : '' }</span> смн</p>
                                <p class="m-0 col-1"><span>${ total_price_before != "NaN" ? total_price_before : "0" }</span> смн</p>
                                <p class="m-0 col-1"><span>${ total_price_after != "NaN" ? total_price_after : "0" }</span> смн</p>
                            </div>`;
                productHistory.innerHTML += div;
            });
        }
    </script>
@endsection
