<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SPMS</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @endif
    </head>
    <body>
           <x-navbar />
           <div class="main">
            <h2 class="text-xl font-bold mb-5">Dashboard.</h2>
            <div class="flex gap-4 justify-center">
               <div class="card">
                  <h3>Total products in stock</h3>
                  <p>{{ $totalProducts }}</p>
               </div>
               <div class="card">
                  <h3>Total Stock in</h3>
                  <p>{{ $totalStockIn ?? 0 }}</p>
               </div>
               <div class="card">
                  <h3>Total stock out</h3>
                  <p>{{ $totalStockOut ?? 0 }}</p>
               </div>
               <div class="card">
                  <h3>Exhausted products</h3>
                  <p>{{ $exhaustedProductsCount }}</p>
               </div>
            </div>

             <div class="flex mt-[50px] gap-[100px]">

               <div class="">
                <p class="text-lg font-semibold">Generate reports of transactions</p>
                <div class="flex gap-2">   
                  <a href="../reports"><div class="btn-primary">Generate monthly</div></a>
                  <a href="../reports"><div class="btn-primary">Generate daily</div></a>
                </div>
               </div>
             </div>

             <div class="flex flex-col mt-[50px] items-center">
                <h2 class="text-lg font-semibold">Recent transactions</h2>

                @if ($recentTransactions->isNotEmpty())
                <table class="inventory-table">
                   <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Type</th>
                   </tr>


                   @foreach ($recentTransactions as $transaction)
                <tr>
                    <td>{{ $transaction->created_at }}</td>  {{-- Or format as needed --}}
                    <td>{{ $transaction->product->Pname }}</td> {{-- Access product name --}}
                    <td>{{ $transaction->Inquantity ?? $transaction->Outquantity }}</td> {{-- Display quantity --}}
                    <td>{{ $transaction->Inprice ?? $transaction->Outprice }}</td> {{-- Display price --}}
                    <td>{{ $transaction->type }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No products found.</p>
@endif
             </div>

           </div>
        
    </body>
</html>
