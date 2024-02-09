@extends('catapult::layout.base')

@section('content')
    <div class="p-8 container mx-auto">

        <h1 class="text-2xl font-bold mb-8">Let's build migrations &#10084;</h1>

        @foreach ($models as $model)
            <a href="{{ route('catapult.migrations.create', ['modelId' => $model->id]) }}"
                class="text-gray-700 block font-bold model group hover:text-white mb-16 border-2 border-dashed hover:border-solid p-4 rounded-md mb-4 hover:bg-gray-700 flex flex-row justify-between items-center"><span>{{ $model->name }}.php</span>
                <div class="flex flex-row items-center">
                    @if ($model->migrations->count() > 0)
                        <span class="text-xs text-gray-500 mr-2 group-hover:text-white">({{ $model->migrations->count() }} columns)</span>

                        @component('catapult::components.icons.checkmark-circle', ['class' => 'h-5 w-5', 'fill' => 'green'])
                        @endcomponent
                    @endif
                </div>
            </a>
        @endforeach


    </div>
@endsection
