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
            <h2 class="text-xl font-bold mb-5">Generate report.</h2>
            

             <div class="flex flex gap-[100px] relative mt-[10px] items-start">
                 <form action="{{ route('reports.generate') }}" method="POST" class="p-4 shadow">
                 @csrf
                    <h2 class="mb-3">report generation form</h2>
                    <p>transaction type:</p>
                    <select name="transaction_type" class="input" id="transaction_type" required>
                       <option value="IN" {{ old('transaction_type') == 'IN' ? 'selected' : '' }}>IN</option>
                       <option value="OUT" {{ old('transaction_type') == 'OUT' ? 'selected' : '' }}>OUT</option>
                    </select><br><br>

                    <p>select first date:</p>
                    <input type="date" name="first_date" class="input" />
                    <p>select last date:</p>
                    <input type="date" name="second_date" class="input" /><br>
                    <button type="submit" class="btn-primary mt-3 w-full">Generate</button>
                 </form>
 
                 <div>
                 @if (isset($transactions))
                    <div class="flex justify-between mb-3"> 
                      <h2>Report results</h2>
                      <button class="btn-primary">Export</button>
                    </div>
                  @endif  


                    @if (isset($transactions))  {{-- Check if transactions data is available --}}
    <h2 class="flex items-center gap-5"><b>STOCK {{ $transactionType }}</b><b>FROM</b>{{ $firstDate }} <b>TO</b> {{ $secondDate }} </h2>

    @if ($transactions->isEmpty())
        <p>No transactions found.</p>
    @else
                 <table class="inventory-table">
                   <tr>
                    <th>type</th>
                    <th>product</th>
                    <th>quantity</th>
                    <th>price</th>
                    <th>date</th>
                    <th>edit</th>
                    <th>delete</th>
                   </tr>

                   @foreach ($transactions as $transaction)
                   <tr>
                    <td>{{ $transactionType }}</td>
                    <td>{{ $transaction->product->Pname }}</td>
                    <td>{{ $transaction->Inquantity ?? $transaction->Outquantity }}</td>
                    <td>{{ $transaction->Inprice ?? $transaction->Outprice }}</td>
                    <td>{{ $transaction->date }}</td>
                    <td><p class="text-teal-900 cursor-pointer">edit</p></td>
                    <td><p class="text-red-900 cursor-pointer">delete</p></td>
                   </tr>
                   @endforeach
                </table>
                <h2>Total Transactions: {{ $totalTransactions }}</h2>
    @endif
@endif

                </div>


                 
             </div>

           </div>
        
    </body>
</html>
