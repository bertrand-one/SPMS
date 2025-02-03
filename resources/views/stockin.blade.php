<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SPMS</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @endif
    </head>
    <body>
           <x-navbar />
           <div class="main">
            <h2 class="text-xl font-bold mb-5">Stock in.</h2>
            

             <div class="flex flex gap-[50px] relative mt-[10px] items-start">

             <div class="shadow p-4 rounded min-w-[300px] flex flex-col">
                <h2 class="mb-3">Add stockin here</h2>
                <form action="{{ route('productin.create') }}" method="POST">
                    @csrf

                    <p>Select product</p>
                    <select name="Pcode" id="Pcode" class="input" required>
                       <option value="">Select a product</option>
                           @foreach (App\Models\Product::where('user_id', session('user_id'))->get() as $product)  {{-- Get products for the current user --}}
                       <option value="{{ $product->id }}" {{ old('Pcode') == $product->id ? 'selected' : '' }}>
                          {{ $product->Pname }} ({{ $product->Pcode }})
                       </option>
                           @endforeach
                    </select><br>

                    <p>Quantity:</p>
                    <input type="number" name="Inquantity" class="input">
                    <p>Price:</p>
                    <input type="number" name="Inprice" class="input">
                    <p>Date:</p>
                    <input type="date" name="date" class="input">
                    <button type="submit" class="btn-primary mt-3 w-full">Add Stockin</button>


                    {{-- Error and success messages --}}
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
                 
               @if ($productIns->isEmpty())
                 <p>No Product Ins found! fill the form on left side to insert products.</p>
               @else
                <table class="inventory-table">
                   <tr>
                    <th>product</th>
                    <th>quantity</th>
                    <th>price(rwf)</th>
                    <th>date</th>
                    <th>edit</th>
                    <th>delete</th>
                   </tr>

                   @foreach ($productIns as $productIn)
                <tr>
                    <td>{{ $productIn->product->Pname }}</td>  {{-- Access Pname through the relationship --}}
                    <td>{{ $productIn->Inquantity }}</td>
                    <td>{{ $productIn->Inprice }}</td>
                    <td>{{ $productIn->date }}</td>
                    <td><button type="button" class="text-teal-900 hover:text-blue-700 font-semibold rounded edit-product-in" data-id="{{ $productIn->id }}" data-inquantity="{{ $productIn->Inquantity }}" data-inprice="{{ $productIn->Inprice }}" data-date="{{ $productIn->date }}">Edit</button></td>
                    <td>
                        <form action="{{ route('product_ins.destroy', $productIn->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-900 hover:text-red-700 font-bold rounded" onclick="return confirm('Are you sure you want to delete this Product In?')">Delete</button>
                        </form></td>
                </tr>
            @endforeach
                </table>
            @endif



            <div id="editProductInModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="editModalLabel" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-black opacity-75"></div>
                    </div>
                    <div class="inline-block w-10 align-bottom bg-white rounded text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-teal-700 text-white py-2 flex justify-center sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-2xl font-semibold" id="editModalLabel">Edit Stockin</h3>
                        </div>
                        <div class="bg-white px-4 pb-5 sm:p-6">
                            <form id="editProductInForm" class="flex flex-col">
                            @csrf
                    @method('PUT')
                    <input type="hidden" id="editProductInId" name="id">

                    <label for="Inquantity">Quantity</label>
                    <input type="number" class="input" id="editInquantity" name="Inquantity" required>

                    <label for="Inprice">Price</label>
                    <input type="number" class="input" id="editInprice" name="Inprice" required>

                    <label for="date">Date</label>
                    <input type="date" class="input" id="editDate" name="date" required>

                    <div id="edit-product-in-errors"></div>
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
                      <h3>Total stock in</h3>
                      <p>{{ $totalProductIns }}</p>
                   </div>
                   <a href="./reports"><div class="btn-primary mt-5">Generate stockin report</div></a>
                </div>
             </div>

           </div>



           <script>
        $(document).ready(function () {
    // Edit Product In
    $('.edit-product-in').click(function () {
        var productId = $(this).data('id');
        var inquantity = $(this).data('inquantity');
        var inprice = $(this).data('inprice');
        var date = $(this).data('date');

        $('#editProductInId').val(productId);
        $('#editInquantity').val(inquantity);
        $('#editInprice').val(inprice);
        $('#editDate').val(date);

        $('#editProductInForm').attr('action', '/product_ins/' + productId); 
        $('#editProductInModal').removeClass('hidden'); 
    });

    $('.close-modal').click(function() {
        $('#editProductInModal').addClass('hidden');
    });

    $('#editProductInForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'PUT',
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    $('#editProductInModal').addClass('hidden'); 
                    location.reload(); 
                    alert(response.success);
                } else if (response.error) {
                    $('#edit-product-in-errors').html('<div class="alert alert-danger">'+ response.error +'</div>');
                }
            },
            error: function (xhr, status, error) {
                var errors = xhr.responseJSON.errors;
                var errorHtml = '<div class="alert alert-danger"><ul>';
                $.each(errors, function (key, value) {
                    errorHtml += '<li>' + value[0] + '</li>';
                });
                errorHtml += '</ul></div>';
                $('#edit-product-in-errors').html(errorHtml);
            }
        });
    });

    // ... (Add similar JavaScript for product_outs here) 
});
    </script>
        
    </body>
</html>
