@extends('catapult::layout.base')

@section('content')
    <div class="container max-w-auto grid grid-cols-[3fr_1fr] gap-8 h-screen p-8 ">
        <div>
            <h1 class="text-2xl font-bold mb-4">Let's build relationships for the {{ $model->name }}.php Model &#10084;</h1>


            <form action="{{ route('catapult.relationships.store', ['model' => $model->id]) }}" method="POST"
                id="create-model-relationship">
                @csrf
                <div class=" mb-16 border-2 border-dashed p-8 rounded-md">
                    <div class="mb-4  flex flex-row justify-between items-center">
                        <p class="text-gray-700 font-bold text-lg">It's easier for you to define all the relationships, then
                            hit save (or enter) after you're done</p>

                        <div class="flex flex-row items-center">
                            <button type="submit" form="create-model-relationship"
                                class="text-white mr-4 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center self-end">Save</button>
                        </div>

                    </div>

                    <div class="w-full  js-{{ $model->name }}-container ">
                        @foreach ($supportedRelationships as $rKey => $relationship)
                            <div class="text-white bg-gray-700 inline-block p-2 text-lg font-bold mb-4 rounded">
                                {{ ucfirst($relationship) }}: </div>
                            <div class="bg-gray-700 p-4 rounded-md w-full h-80 mb-4 relative overflow-hidden"
                                ondrop="drop(event, '{{ $rKey }}', '{{ $model->name }}', '{{ $relationship }}')"
                                ondragover="allowDrop(event)">

                                <div
                                    class="h-full w-full js-{{ $model->name }}-{{ $rKey }} overflow-y-scroll py-4 px-2">

                                    @if ($existing[$rKey]->count() === 0)
                                        <div class="text-gray-700">No {{ $relationship }} found.</div>
                                    @endif

                                    @if ($existing[$rKey]->count() > 0)
                                        @foreach ($existing[$rKey] as $current)
                                            @component('catapult::components.relationship-item', [
                                                'current' => $current,
                                                'relationship_parameters' => $relationshipMethodParameters[$rKey],
                                            ])
                                            @endcomponent
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

        <div class="relative">

            <div class="top fixed">
                <div class="mb-8">
                    <p class="text-2xl font-bold mb-1">You are in the</br>
                    <div class="bg-gray-700 px-2 py-1 text-center">
                        <span class="text-2xl font-bold text-white ">{{ $model->name }}</span>
                        <span class="text-2xl font-bold text-sky-300">::class</span>
                    </div>
                    </p>
                </div>
                <h1 class="text-2xl font-bold mb-4">Available Models</h1>
                @if (count($models) === 0)
                    <div class="text-gray-700">Seems like you haven't created anything yet.</div>
                @endif
                <div class="overflow-y-scroll h-[600px] pb-12">

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
        var relationshipMethodInputs = @php echo json_encode($relationshipMethodParameters) @endphp;
        var deleteComponent =
            `@component('catapult::components.icons.delete') @endcomponent`;
        var csrfToken = '{{ csrf_token() }}';
    </script>

    <script src="{{ asset('Joeabdelsater/catapult-base/js/relationships/main.js') }}"></script>

    <script src="{{ asset('Joeabdelsater/catapult-base/js/relationships/dnd.js') }}"></script>
@endpush
