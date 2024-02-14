<div class="bg-white rounded-md mb-2 grid grid-cols-[26fr_1fr] items-center ">
    <div class="p-4">
        <div class="flex flex-row flex-start items-center">
            <p class="text-sm font-bold">Route::<span class='text-sky-500'>{{ $route->method }}(</span> <span
                    class="text-orange-500">'{{ $route->route_path }}'</span>,
                <span class="text-orange-500">'{{ $route->controller_method }}'</span>
                <span class="text-sky-500">)</span>
                @if($route->route_name)
                <span class="text-sm font-bold text-sky-500">->name(</span><span class="text-orange-500">'{{ $route->route_name }}'</span><span
                    class="text-sm font-bold text-sky-500">);</span>
                @else
                <span class="text-sm font-bold">;</span>
                @endif

            </p>

        </div>

    </div>


    <button onclick="deleteRoute('{{ $route->id }}', '{{ $route->catapult_controller_id }}')" type="button" class="bg-red-600 rounded-r-md flex items-center justify-center h-full w-full">
        <div class="delete-btn">
            @component('catapult::components.icons.delete', ['fill' => 'white'])
            @endcomponent
        </div>
    </button>

</div>
