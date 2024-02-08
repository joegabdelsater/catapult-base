@extends('catapult::layout.base')

@section('content')
    <div class="container max-w-auto grid grid-cols-[3fr_1fr] gap-8 h-screen p-8 ">
        <div>
            <h1 class="text-2xl font-bold mb-4">Let's build relationships for the {{$model->name}}.php Model &#10084;</h1>


            <form action="{{ route('catapult.relationships.store', ['model' => $model->id]) }}" method="POST">
                @csrf
                <div class=" mb-16 border-2 border-dashed p-8 rounded-md">
                    <div class="mb-4  flex flex-row justify-between items-center">
                        <p class="text-gray-700 font-bold">{{ $model->name }}.php</p>

                        <div class="flex flex-row items-center">
                            <button type="submit"
                                class="text-white mr-4 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center self-end">Save</button>
                        </div>

                    </div>

                    <div class="w-full  js-{{ $model->name }}-container ">
                        @foreach ($relationships as $rKey => $relationship)
                            <div class="text-white bg-gray-700 inline-block p-2 text-lg font-bold mb-4 rounded">{{ ucfirst($relationship) }}: </div>
                            <div class="bg-gray-700 p-4 rounded-md w-full h-80 mb-4 relative overflow-hidden"
                                ondrop="drop(event, '{{ $rKey }}', '{{ $model->name }}', '{{ $relationship }}')"
                                ondragover="allowDrop(event)">

                                <div
                                    class="h-full w-full js-{{ $model->name }}-{{ $rKey }} overflow-y-scroll py-4 px-2">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

        <div class="relative">
            <div class="fixed top-20">
                <h1 class="text-2xl font-bold mb-4">Models</h1>
                @if (count($models) === 0)
                    <div class="text-gray-700">Seems like you haven't created anything yet.</div>
                @endif
                <div class="">
                    @foreach ($models as $model)
                        <div class="flex justify-between items-center p-3">
                            <div class="text-gray-700 px-4 py-2 bg-sky-200 rounded-md font-bold cursor-pointer"
                                draggable="true"
                                ondragstart="drag(event, '{{ $model->name }}::class', '{{ Str::lcfirst($model->name) }}', '{{ Str::plural(Str::lcfirst($model->name)) }}')">
                                {{ $model->name }}<span class="text-blue-600">::class</span></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var relationshipMethodInputs = @php echo json_encode($relationshipMethods) @endphp;
        var deleteComponent =
            `@component('catapult::components.icons.delete') @endcomponent`;
    </script>

    <script src="{{ asset('joegabdelsater/catapult-base/js/relationships/main.js') }}"></script>

    <script src="{{ asset('joegabdelsater/catapult-base/js/relationships/dnd.js') }}"></script>
@endpush
