

<form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST"
    enctype="multipart/form-data" role="form" onkeydown="return event.key != 'Enter';">
    @csrf
    @isset($product)
        @method('PUT')
    @endisset
    <div class="row">
        <div class="col-md-6 d-flex align-items-stretch mb-3">
            <x-card>

                <x-select label="Category" name="category" :searchable="true">
                    @isset($product)
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected($product->category_id == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    @else
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    @endisset
                </x-select>

                <x-input label="Item Name" name="name"
                    value="{{ old('name', isset($product) ? $product->name : '') }}" />


                <x-textarea label="Description" name="description"
                    value="{{ old('description', isset($product) ? $product->description : null) }}" />
            </x-card>
        </div>
        <div class="col-md-6 d-flex align-items-stretch mb-3">
            <x-card>
                <x-select label="status.text" name="status">
                    @isset($product)
                        <option value="available" @if ($product->is_active) selected @endif>
                            @lang('For Sale')
                        </option>
                        <option value="unavailable" @if (!$product->is_active) selected @endif>
                            @lang('Hidden')
                        </option>
                    @else
                        <option value="available">@lang('For Sale')</option>
                        <option value="unavailable">@lang('Hidden')</option>
                    @endisset
                </x-select>
                <x-number-input label="Sort Order" name="sort_order"
                    value="{{ old('sort_order', isset($product) ? $product->sort_order : '') }}" />
                    <x-select label="Size" name="size">
                    @isset($product)
                        <option value="S" {{ old('size') == 'S' ? 'selected' : '' }}>
                            @lang('Small')
                        </option>
                        <option value="M" {{ old('size') == 'M' ? 'selected' : '' }}>
                            @lang('Medium')
                        </option>
                        <option value="L" {{ old('size') == 'L' ? 'selected' : '' }}>
                            @lang('Large')
                        </option>
                    @else
                        <option value="S">@lang('Small')</option>
                        <option value="M">@lang('Medium')</option>
                        <option value="L">@lang('Large')</option>
                    @endisset
                </x-select>
                <x-input label="Ages" name="age"
                    value="{{ old('age', isset($product) ? $product->age : '') }}" />
                <x-input label="Color" name="color"
                    value="{{ old('color', isset($product) ? $product->color : '') }}" />
            </x-card>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 d-flex align-items-stretch">
            <x-card class="mb-3" >
                <div class="card-title h4 text-muted">@lang('Pricing')</div>

                <x-currency-input label="Cost" name="cost"
                    value="{{ old('cost', isset($product) ? $product->cost : '') }}" />
<!-- 
                <x-currency-input label="Sale Price" name="sale_price"
                    value="{{ old('sale_price', isset($product) ? $product->sale_price : '') }}" /> -->

                <x-currency-input label="Retailsale Price" name="retailsale_price"
                    value="{{ old('retailsale_price', isset($product) ? $product->retailsale_price : '') }}" />

                <x-currency-input label="Wholesale Price" name="wholesale_price"
                    value="{{ old('wholesale_price', isset($product) ? $product->wholesale_price : '') }}" />
                    

            </x-card>
        </div>
        <div class="col-md-6 d-flex align-items-stretch">
            <x-card class="mb-3">
                <div class="card-title h4 text-muted">@lang('Stock Management')</div>

                <x-stock-input label="In Stock" name="in_stock"
                    value="{{ old('in_stock', isset($product) ? $product->in_stock : '') }}" />

                <x-checkbox label="Track Stock" name="track_stock"
                    checked="{{ isset($product) ? $product->track_stock : true }}" />

                <x-checkbox label="Keep selling when out of stock" name="continue_selling_when_out_of_stock"
                    checked="{{ isset($product) ? $product->continue_selling_when_out_of_stock : true }}" />

                <!-- <div class="row">
                    <div class="col-md-6">
                        <x-input label="Barcode" name="barcode"
                            value="{{ old('barcode', isset($product) ? $product->barcode : '') }}"
                            formText="You can also use a scanner" />
                    </div>
                    <div class="col-md-6">
                        <x-input label="SKU" name="sku"
                            value="{{ old('sku', isset($product) ? $product->sku : '') }}" />
                    </div>
                </div> -->
                
                <div class="row">
                    <div class="col-md-6">
                        <x-input label="Retail Barcode" name="retail_barcode"
                            value="{{ old('retail_barcode', isset($product) ? $product->retail_barcode : '') }}"
                            formText="You can also use a scanner" />
                    </div>
                    <div class="col-md-6">
                        <x-input label="Retail SKU" name="retail_sku"
                            value="{{ old('retail_sku', isset($product) ? $product->retail_sku : '') }}" />
                    </div>
                </div>
                <div class="row">
    <div class="col-md-12">
        <label for="imei_barcode">@lang('IMEI')</label>
        <div id="imei-container">
            @php
                $imeiBarcodes = isset($product) && is_array($product->imei_barcode) 
                    ? $product->imei_barcode 
                    : (isset($product) && is_string($product->imei_barcode) ? explode(',', $product->imei_barcode) : []);
            @endphp

            <!-- First IMEI Field (Filled from Data) -->
            <div class="input-group mb-2 imei-field">
                <input type="text" name="imei_barcode[]" class="form-control imei-input" 
                       value="{{ old('imei_barcode.0', $imeiBarcodes[0] ?? '') }}" 
                       placeholder="Scan or enter IMEI" />
                <button type="button" id="add-imei" class="btn btn-success">+</button>
            </div>

            <!-- Additional IMEI Fields -->
            @foreach(array_slice($imeiBarcodes, 1) as $index => $imei)
                <div class="input-group mb-2 imei-field">
                    <input type="text" name="imei_barcode[]" class="form-control imei-input" 
                           value="{{ old('imei_barcode.' . ($index + 1), $imei) }}" 
                           placeholder="Scan or enter IMEI" />
                    <button type="button" class="btn btn-danger remove-imei">X</button>
                </div>
            @endforeach
        </div>
    </div>
</div>



                <div class="row">
                    <div class="col-md-12">
                    <x-currency-input label="Box Price" name="box_price"
                    value="{{ old('box_price', isset($product) ? $product->box_price : '') }}" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-input label="Wholesale Barcode" name="wholesale_barcode"
                            value="{{ old('wholesale_barcode', isset($product) ? $product->wholesale_barcode : '') }}"
                            formText="You can also use a scanner" />
                    </div>
                    <div class="col-md-6">
                        <x-input label="Wholesale SKU" name="wholesale_sku"
                            value="{{ old('wholesale_sku', isset($product) ? $product->wholesale_sku : '') }}" />
                    </div>
                </div>

 
                                {{-- add bar code print button --}}
                <div class="row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary btn-block d-flex align-items-center mx-3 mt-3 w-75 h-12"
                            onclick="printBarCode('retail_barcode')">
                            @lang('Unit Barcode Print')
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary btn-block d-flex align-items-center mx-3 mt-3 w-75 h-12"
                            onclick="printBarCode('wholesale_barcode')">
                            @lang('WholeSale Barcode Print')
                        </button>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
    <x-card class="mb-3">
        <div class="mb-5">
            <label for="image" class="form-label">@lang('Image')</label>
            <input class="form-control @error('image') is-invalid @enderror" name="image" type="file"
                id="image-input" accept="image/png, image/jpeg">
            @error('image')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @else
                <div id="imageHelp" class="form-text">@lang('Choose an image')</div>
            @enderror
        </div>
        <div class="mb-5 text-center">
            <div class="mb-3">
                @isset($product)
                    <img src="{{ $product->image_url }}" height="250"
                        class="object-fit-cover border rounded  @if (!$product->image_path) d-none @endif"
                        alt="{{ $product->name }}" id="image-preview">
                @else
                    <img src="#" height="250" class="object-fit-cover border rounded  d-none" alt="image"
                        id="image-preview">
                @endisset
            </div>
            @isset($product)
                @if ($product->image_path)
                    <div class="mb-3">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#removeCategoryImageModal">
                            @lang('Remove Image')
                        </button>
                    </div>
                @endif
            @endisset
        </div>
    </x-card>
    <div class="mb-3">
        <x-save-btn>
            @lang(isset($product) ? 'Update Item' : 'Save Item')
        </x-save-btn>
    </div>
</form>

@isset($product)
    <div class="modal" id="removeCategoryImageModal" tabindex="-1" aria-labelledby="removeCategoryImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="removeCategoryImageModalLabel">@lang('Are you sure?')</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('products.image.destroy', $product) }}" method="POST" role="form">
                    <div class="modal-body">
                        @csrf
                        @method('DELETE')
                        @lang('You cannot undo this action!')
                    </div>
                    <div class="row p-0 m-0 border-top">
                        <div class="col-6 p-0">
                            <button type="button"
                                class="btn btn-link w-100 m-0 text-danger btn-lg text-decoration-none rounded-0 border-end"
                                data-bs-dismiss="modal">@lang('Cancel')</button>
                        </div>
                        <div class="col-6 p-0">
                            <button type="submit"
                                class="btn btn-link w-100 m-0 text-black btn-lg text-decoration-none rounded-0 border-start">
                                @lang('Remove Image')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endisset
@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("image-input").onchange = function() {
                previewImage(this, document.getElementById("image-preview"))
            };
        });
        
        function printBarCode(param) {
    value = $(`input[name=${param}]`).val();
    box_price = $(`input[name=box_price]`).val();
    item_name = $(`input[name="name"]`).val(); // Get the item name
    let logoHtml = '';
    let storeDetailsHtml = '';

    @if ($settings->logo)
        logoHtml = `
           <div style="text-align: center; margin-bottom: 4px;">
                {!! $settings->logo !!}
            </div>`;
    @endif

    storeDetailsHtml = `
        <div style="text-align: center; margin: 0; font-size: 0.8rem; padding: 0;">
            @if ($settings->storeAddress)
                <p style="margin: 0; padding: 0;">{{ $settings->storeAddress }}</p>
            @endif
            @if ($settings->storePhone)
                <p style="margin: 0; padding: 0;">{{ $settings->storePhone }}</p>
            @endif
        </div>
    `;

    let htmlContent = `
        <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Print Barcode</title>
                <style>
                    @media print {
                        @page {
                            size: 3.3in 1.85in; /* Set custom page size */
                            margin: 0;
                        }
                        body {
                            margin: 0;
                            padding: 0;
                            width: 3.3in;
                            height: 1.85in;
                            font-family: Arial, sans-serif;
                        }
                        .barcode-container {
                            width: 100%;
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            padding: 10px;
                        }
                        .barcode-image {
                            width: 80%; /* Adjust width */
                            height: 1in; /* Adjust height */
                            margin-top: 5px;
                            margin-bottom: 10px;
                        }
                        .item-name {
                            font-size: 1rem;
                            text-align: center;
                            margin: 0; /* Remove extra spacing */
                            font-weight: bold; /* Make it stand out */
                        }
                        .box-price {
                            font-size: 0.9rem;
                            text-align: center;
                            margin: 0; /* Remove extra spacing */
                            padding: 0; /* Remove extra spacing */
                        }
                    }
                    .barcode-container {
                        width: 100%;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        padding: 10px;
                    }
                    .barcode-image {
                        width: 80%; /* Adjust width */
                        height: 1in; /* Adjust height */
                        margin-top: 5px;
                        margin-bottom: 10px;
                    }
                    .item-name {
                        font-size: .9rem;
                        text-align: center;
                        margin: 0; /* Remove extra spacing */
                        font-weight: bold; /* Make it stand out */
                    }
                    .box-price {
                        font-size: 0.9rem;
                        text-align: center;
                        margin: 0; /* Remove extra spacing */
                        padding: 0; /* Remove extra spacing */
                    }
                </style>
            </head>
            <body>
                <div class="barcode-container">
                    <p class="item-name">${item_name}</p> <!-- Display the item name -->
                    <img 
                        src="https://barcode.orcascan.com/?type=code128&format=png&data=${value}" 
                        alt="Barcode"
                        class="barcode-image"
                    />
                    <p class="box-price">Box Price: ${box_price}</p> <!-- Display the box price -->
                </div>
            </body>
        </html>
    `;

    let printWindow = window.open('', 'BarcodePrintWindow', 'width=600,height=600');
    printWindow.document.write(htmlContent);
    printWindow.document.close();

    printWindow.onload = function() {
        printWindow.print();
        printWindow.close();
    };
}

    </script>
@endpush


@push('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let imeiContainer = document.getElementById("imei-container");
        let addImeiBtn = document.getElementById("add-imei");

        // Function to create a new IMEI input field
        function addImeiField() {
            let newField = document.createElement("div");
            newField.classList.add("input-group", "mb-2", "imei-field");
            newField.innerHTML = `
                <input type="text" name="imei_barcode[]" class="form-control imei-input" 
                       placeholder="Scan or enter IMEI" />
                <button type="button" class="btn btn-danger remove-imei">X</button>
            `;
            imeiContainer.appendChild(newField);
        }

        // When the user clicks the "Add" button, add a new IMEI field
        addImeiBtn.addEventListener("click", function() {
            addImeiField();
        });

        // Detect input changes in IMEI fields and add a new field automatically
        imeiContainer.addEventListener("input", function(event) {
            if (event.target.classList.contains("imei-input")) {
                let imeiFields = document.querySelectorAll(".imei-input");
                let lastField = imeiFields[imeiFields.length - 1];

                // If the last input field has data, add a new empty field
                if (lastField.value.trim() !== "") {
                    addImeiField();
                }
            }
        });

        // Remove IMEI field on button click
        imeiContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains("remove-imei")) {
                let fieldGroup = event.target.closest(".imei-field");

                // Only remove if there's more than one field
                if (imeiContainer.querySelectorAll(".imei-field").length > 1) {
                    fieldGroup.remove();
                }
            }
        });
    });
</script>
@endpush
