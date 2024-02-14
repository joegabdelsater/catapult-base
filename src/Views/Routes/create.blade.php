@extends('catapult::layout.base')

@section('content')
    <div class="container max-w-auto w-full p-8 ">
        <div>
            <h1 class="text-2xl font-bold mb-4">Let's build routes for the {{ $controller->name }}.php controller &#10084;
            </h1>


            <div class=" mb-16 border-2 border-dashed p-8 rounded-md">
                <div class="w-full">
                    @foreach ($supportedRoutes as $rKey => $supportedRoute)
                        <div class="text-white bg-gray-700 inline-block p-2 text-lg font-bold mb-4 rounded">
                            {{ Str::upper($supportedRoute) }}: </div>

                        <div class="bg-gray-700 p-4 rounded-md w-full h-80 mb-4 relative overflow-hidden">

                            <div class="h-full w-full overflow-y-scroll py-4 px-2 ">
                                <form id="{{ $supportedRoute }}"
                                    action="{{ route('catapult.routes.store', ['controller' => $controller->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="bg-white rounded-md mb-2 grid grid-cols-[26fr_1fr] items-center p-4">
                                        <div class="">
                                            <div class="flex flex-row flex-start items-center">
                                                <p class="text-sm font-bold">Route::<span
                                                        class='text-sky-500'>{{ $supportedRoute }}</span>(</p>
                                                <input type="text"
                                                    class="focused px-2 text-sm text-white font-bold rounded bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-80 "
                                                    placeholder="/path/{parameter}" name="route_path" required
                                                    value="" />
                                                <p>, </p>
                                                <input type="text"
                                                    class="focused px-2 text-sm text-white font-bold rounded bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-40 "
                                                    placeholder="controllerMethod" name="controller_method" required
                                                    value="" />
                                                <p class="text-sm font-bold">)->name(</p><input type="text"
                                                    class="focused px-2 text-sm text-white font-bold rounded bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-40 "
                                                    placeholder="route.name" name="route_name" value="" />
                                                <p>);</p>
                                            </div>
                                            <input type="hidden" name="method" value="{{ $supportedRoute }}" />
                                        </div>

                                        <div>
                                            <button type="submit" form="{{ $supportedRoute }}"
                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Save</button>
                                        </div>
                                    </div>
                                </form>

                                <div class="mt-8">
                                    @foreach ($existing[$supportedRoute] as $route)
                                        @if ($route)
                                            @component('catapult::components.route-item', ['route' => $route])
                                            @endcomponent
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        var deleteComponent =
            `@component('catapult::components.icons.delete') @endcomponent`;
        var csrfToken = '{{ csrf_token() }}';
    </script>

    <script src="{{ asset('joegabdelsater/catapult-base/js/relationships/main.js') }}"></script>

@endpush
