$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#productForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: '/products',
            method: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                if (response.status === 'success') {
                    const product = response.data;
                    const totalValue = product.quantity_in_stock * product.price_per_item;

                    let newRow = `
                        <tr data-id="${product.id}">
                            <td>${product.product_name}</td>
                            <td>${product.quantity_in_stock}</td>
                            <td>${product.price_per_item}</td>
                            <td>${product.datetime_submitted}</td>
                            <td>${totalValue}</td>
                            <td>
                                <button class="btn btn-sm btn-warning btn-edit"
                                    data-id="${product.id}"
                                    data-name="${product.product_name}"
                                    data-quantity="${product.quantity_in_stock}"
                                    data-price="${product.price_per_item}">
                                    Edit
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-id="${product.id}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `;

                    $('#productTableBody tr:last').before(newRow);

                    let currentSum = parseFloat($('#sumTotalValues').text()) || 0;
                    $('#sumTotalValues').text((currentSum + totalValue).toFixed(2));
                    $('#productForm')[0].reset();
                }
            },
            error: function (err) {
                alert('Error saving product. Check console for details.');
                console.log(err);
            }
        });
    });

    $(document).on('click', '.btn-edit', function () {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let quantity = $(this).data('quantity');
        let price = $(this).data('price');

        $('#edit_id').val(id);
        $('#edit_product_name').val(name);
        $('#edit_quantity_in_stock').val(quantity);
        $('#edit_price_per_item').val(price);

        let editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    });

    // Handle UPDATE (edit product) via AJAX
    $('#editProductForm').submit(function (e) {
        e.preventDefault();

        let id = $('#edit_id').val();
        let formData = $(this).serialize();

        $.ajax({
            url: '/products/' + id + '/edit',
            method: 'POST',
            data: formData,
            success: function (response) {
                if (response.status === 'success') {
                    let products = response.data;

                    $('#productTableBody').empty();

                    let sumTotalValues = 0;
                    let rowHtml = '';

                    products.forEach(function (prod) {
                        let tv = prod.quantity_in_stock * prod.price_per_item;
                        sumTotalValues += tv;

                        rowHtml += `
                            <tr data-id="${prod.id}">
                                <td>${prod.product_name}</td>
                                <td>${prod.quantity_in_stock}</td>
                                <td>${prod.price_per_item}</td>
                                <td>${prod.datetime_submitted}</td>
                                <td>${tv}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning btn-edit"
                                        data-id="${prod.id}"
                                        data-name="${prod.product_name}"
                                        data-quantity="${prod.quantity_in_stock}"
                                        data-price="${prod.price_per_item}">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    $('#productTableBody').append(rowHtml + `
                        <tr class="fw-bold">
                            <td colspan="4" class="text-end">Total Sum:</td>
                            <td id="sumTotalValues">${sumTotalValues.toFixed(2)}</td>
                            <td></td>
                        </tr>
                    `);

                    let editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                    editModal.hide();
                }
            },
            error: function (err) {
                alert('Error updating product. Check console.');
                console.log(err);
            }
        });
    });

    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');

        if (!confirm("Are you sure you want to delete this product?")) {
            return;
        }

        $.ajax({
            url: '/products/' + id,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.status === 'success') {
                    let products = response.data;
                    $('#productTableBody').empty();

                    let sumTotalValues = 0;
                    let rowHtml = '';

                    products.forEach(function (prod) {
                        let tv = prod.quantity_in_stock * prod.price_per_item;
                        sumTotalValues += tv;

                        rowHtml += `
                            <tr data-id="${prod.id}">
                                <td>${prod.product_name}</td>
                                <td>${prod.quantity_in_stock}</td>
                                <td>${prod.price_per_item}</td>
                                <td>${prod.datetime_submitted}</td>
                                <td>${tv}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning btn-edit"
                                        data-id="${prod.id}"
                                        data-name="${prod.product_name}"
                                        data-quantity="${prod.quantity_in_stock}"
                                        data-price="${prod.price_per_item}">
                                        Edit
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger btn-delete"
                                        data-id="${prod.id}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    rowHtml += `
                        <tr class="fw-bold">
                            <td colspan="4" class="text-end">Total Sum:</td>
                            <td id="sumTotalValues">${sumTotalValues.toFixed(2)}</td>
                            <td colspan="2"></td>
                        </tr>
                    `;

                    $('#productTableBody').append(rowHtml);
                }
            },
            error: function (err) {
                alert('Error deleting product. Check console.');
                console.log(err);
            }
        });
    });

});
