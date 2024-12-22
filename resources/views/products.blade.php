<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel AJAX Product Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h1 class="mb-4">Laravel - AJAX Product Form</h1>
    <div class="card mb-5">
        <div class="card-header">Add New Product</div>
        <div class="card-body">
            <form id="productForm">
                @csrf
                <div class="row mb-3">
                    <label for="product_name" class="col-sm-2 col-form-label">Product Name:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="quantity_in_stock" class="col-sm-2 col-form-label">Quantity in Stock:</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="quantity_in_stock" name="quantity_in_stock" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="price_per_item" class="col-sm-2 col-form-label">Price per Item:</label>
                    <div class="col-sm-10">
                        <input type="number" step="any" class="form-control" id="price_per_item" name="price_per_item" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Product</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Submitted Products</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                <tr>
                    <th>Product Name</th>
                    <th>Quantity in Stock</th>
                    <th>Price per Item</th>
                    <th>Datetime Submitted</th>
                    <th>Total Value</th>
                    <th>Edit</th>
                </tr>
                </thead>
                <tbody id="productTableBody">
                @php
                    $sumTotalValues = 0;
                @endphp
                @foreach ($products as $product)
                    @php
                        $totalValue = $product->quantity_in_stock * $product->price_per_item;
                        $sumTotalValues += $totalValue;
                    @endphp
                    <tr data-id="{{ $product->id }}">
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->quantity_in_stock }}</td>
                        <td>{{ $product->price_per_item }}</td>
                        <td>{{ $product->datetime_submitted }}</td>
                        <td>{{ $totalValue }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->product_name }}"
                                    data-quantity="{{ $product->quantity_in_stock }}"
                                    data-price="{{ $product->price_per_item }}">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
                <tr class="fw-bold">
                    <td colspan="4" class="text-end">Total Sum:</td>
                    <td id="sumTotalValues">{{ $sumTotalValues }}</td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editProductForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="edit_product_name" name="product_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_quantity_in_stock" class="form-label">Quantity in Stock</label>
                        <input type="number" class="form-control" id="edit_quantity_in_stock" name="quantity_in_stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price_per_item" class="form-label">Price per Item</label>
                        <input type="number" step="any" class="form-control" id="edit_price_per_item" name="price_per_item" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update Product</button>
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/product.js') }}"></script>
</body>
</html>
