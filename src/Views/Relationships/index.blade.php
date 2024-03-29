@extends('catapult::layout.base')

@section('content')
    <div class="p-8 container mx-auto">

        <div class="flex flex-row items-center justify-between mb-8">
            <h1 class="text-2xl font-bold ">Let's build relationships &#10084;</h1>

            <a href="{{ route('catapult.models.generate-all') }}"
                onclick="return confirm('Are you sure you want to generate all models? This will overwrite any existing files.')"
                class="p-2 text-gray-500 rounded-lg text-white text-base bg-rose-800 text-center block hover:bg-rose-900 mr-2">
                Re-generate all models
            </a>
        </div>


        @if (count($models) === 0)
            <div class="text-gray-700 text-lg">Seems like you haven't created any models yet. &#128549;</div>
        @endif

        @foreach ($models as $model)
            <div
                class="flex flex-row justify-between items-center group  mb-16 border-2 border-dashed hover:border-solid p-4 rounded-md mb-4 hover:bg-gray-700 ">
                <a href="{{ route('catapult.relationships.create', ['modelId' => $model->id]) }}"
                    class="text-gray-700 block font-bold  bg-green-200 rounded-md px-4 py-2"><span>{{ $model->name }}.php</span>
                </a>

                <div class="flex flex-row items-center">
                    @if ($model->updated)
                        <a href="{{ route('catapult.models.generate', ['model' => $model->id]) }}"
                            class="p-2 text-gray-500 rounded-lg text-white text-xs bg-rose-800 text-center block hover:bg-rose-900 mr-2">
                            re-generate and apply relationships </a>
                    @endif
                    @if ($model->relationships->count() > 0)
                        <span
                            class="text-xs text-gray-500 mr-2 group-hover:text-white">({{ $model->relationships->count() }}
                            relationships)</span>
                        @component('catapult::components.icons.checkmark-circle', ['class' => 'h-5 w-5', 'fill' => 'green'])
                        @endcomponent
                    @endif
                </div>
            </div>
        @endforeach


    </div>
@endsection
