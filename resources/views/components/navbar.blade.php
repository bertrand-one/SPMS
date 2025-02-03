<div class="fixed top-0 left-0 flex justify-between items-center px-10 h-[10vh] w-full bg-teal-600 text-white">
    <div class="text-2xl font-bold">
        <a href="../dashboard">SPMS</a>
    </div>

    <div class="flex gap-[100px] items-center">
        <div class="flex gap-5">   
        <a href="../dashboard">Dashboard</a>
        <a href="../products">Products</a>
        <a href="../stockin">Stock in</a>
        <a href="../stockout">Stock out</a>
        <a href="../reports">Reports</a>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
         <button type="submit">Logout</button>
        </form>
        </div>

        <div class="flex items-center gap-2">
            <div class="rounded-full bg-white text-black w-10 h-10 flex items-center justify-center">{{ substr(Session::get('username'), 0, 1) }}</div>
            <p class="font-semibold">
                   {{ Session::get('username') }}
            </p>
        </div>
    </div>
</div>