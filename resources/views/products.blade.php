<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SPMS</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>  {{-- Include jQuery --}}
</head>
<body>
    <x-navbar />
    <div class="main">
        <h2 class="text-xl font-bold mb-5">Products.</h2>

        <div class="flex gap-[50px] relative mt-[10px] items-start">

            <div class="shadow p-4 rounded min-w-[300px] flex flex-col">
                <h2 class="mb-3">Add products here</h2>
                <form action="{{ route('products.create') }}" method="POST">
                    @csrf
                    <p>product code:</p>
                    <input type="text" class="input" name="Pcode">
                    <p>product name:</p>
                    <input type="text" class="input" name="Pname">
                    <button type="submit" class="btn-primary mt-3 w-full">Add product</button>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                </form>
            </div>

            @if ($productsWithStock->isEmpty())
                <p>No products found Please register products that your store contains.</p>
            @else
                <table class="inventory-table">
                    <tr>
                        <th>pcode</th>
                        <th>product name</th>
                        <th>Quantity</th>
                        <th>edit</th>
                        <th>delete</th>
                    </tr>
                    @foreach ($productsWithStock as $product)
                        <tr>
                            <td>{{ $product->Pcode }}</td>
                            <td>{{ $product->Pname }}</td>
                            <td>{{ $product->stock ?? 0}}</td>
                            <td><button type="button" class="text-teal-900 hover:text-blue-700 font-semibold rounded edit-product" data-id="{{ $product->id }}" data-pcode="{{ $product->Pcode }}" data-pname="{{ $product->Pname }}">Edit</button></td>
                            <td>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-900 hover:text-red-700 font-semibold rounded" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif

            <div id="editModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="editModalLabel" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-black opacity-75"></div>
                    </div>
                    <div class="inline-block w-10 align-bottom bg-white rounded text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-teal-700 text-white py-2 flex justify-center sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-2xl font-semibold" id="editModalLabel">Edit Product</h3>
                        </div>
                        <div class="bg-white px-4 pb-5 sm:p-6">
                            <form id="editForm" class="flex flex-col">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="editProductId" name="id">
                                <label for="Pcode">Product Code</label>
                                <input type="text" class="input" id="editPcode" name="Pcode" required>

                                <label for="Pname">Product Name</label>
                                <input type="text" class="input" id="editPname" name="Pname" required>

                                <div id="edit-product-errors"></div>
                                <div class="flex items-center justify-end mt-5">
                                    <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2 close-modal" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>





            <div class="shadow p-4 rounded min-w-[300px] flex flex-col">
                <div>
                    <h2>filters</h2>
                    <div class="grid grid-cols-2 justify-start filters">
                        <button>By name</button>
                        <button>By quantity</button>
                        <button>By price</button>
                        <button>By date</button>
                    </div>
                </div>
                <div class="card mt-3">
                    <h3>Total products</h3>
                    <p>{{ $totalProducts }}</p>
                </div>
                <div class="btn-primary mt-5">Export products list</div>
            </div>
        </div>

    </div>


    <script>
        $(document).ready(function () {
            $('.edit-product').click(function () {
                var productId = $(this).data('id');
                var pcode = $(this).data('pcode');
                var pname = $(this).data('pname');

                $('#editProductId').val(productId);
                $('#editPcode').val(pcode);
                $('#editPname').val(pname);

                $('#editForm').attr('action', '/products/' + productId); // Set the correct update URL
                $('#editModal').removeClass('hidden'); // Show the modal!!! 
            });

            $('.close-modal').click(function() {
                $('#editModal').addClass('hidden');
            });

            $('#editForm').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success) {
                            $('#editModal').addClass('hidden'); // Hide the modal after successful update
                            location.reload(); // Refresh the page to show updated data
                            alert(response.success);
                        } else if (response.error) {
                            $('#edit-product-errors').html('<div class="alert alert-danger">'+ response.error +'</div>');
                        }
                    },
                    error: function (xhr, status, error) {
                        var errors = xhr.responseJSON.errors;
                        var errorHtml = '<div class="alert alert-danger"><ul>';
                        $.each(errors, function (key, value) {
                            errorHtml += '<li>' + value[0] + '</li>';
                        });
                        errorHtml += '</ul></div>';
                        $('#edit-product-errors').html(errorHtml);
                    }
                });
            });
        });
    </script>

</body>
</html>