@extends('catapult::layout.base')

@section('content')
    <div class="container max-w-auto w-full p-8 ">
        <div>
            <h1 class="text-2xl font-bold mb-4">Let's build routes for the {{ $controller->name }}.php controller &#10084;</h1>


            <form action="{{ route('catapult.routes.store', ['controller' => $controller->id]) }}" method="POST"
                id="create-model-relationship">
                @csrf
                <div class=" mb-16 border-2 border-dashed p-8 rounded-md">
                    <div class="mb-4  flex flex-row justify-between items-center">
                        <p class="text-gray-700 font-bold">{{ $controller->name }}.php</p>

                        <div class="flex flex-row items-center">
                            <button type="submit" form="create-model-relationship"
                                class="text-white mr-4 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center self-end">Save</button>
                        </div>

                    </div>

                    <div class="w-full">
                        @foreach ($supportedRoutes as $rKey => $supportedRoute)
                            <div class="text-white bg-gray-700 inline-block p-2 text-lg font-bold mb-4 rounded">
                                {{ Str::upper($supportedRoute) }}: </div>
                            <div class="bg-gray-700 p-4 rounded-md w-full h-80 mb-4 relative overflow-hidden">
                                <div class="h-full w-full overflow-y-scroll py-4 px-2">
                         
                                    {{-- @if ($existing[$rKey]->count() > 0)
                                        @foreach ($existing[$rKey] as $current)
                                            @component('catapult::components.route-item', [
                                                'current' => $current,
                                                'relationship_parameters' => $relationshipMethodParameters[$rKey],
                                            ])
                                            @endcomponent
                                        @endforeach
                                    @endif --}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </form>
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

    <script src="{{ asset('joegabdelsater/catapult-base/js/relationships/dnd.js') }}"></script>
@endpush
