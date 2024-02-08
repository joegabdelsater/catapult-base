@extends('catapult::layout.base')

@section('content')
    <div class="p-8 container mx-auto">

        <h1 class="text-2xl font-bold mb-8">Let's build relationships &#10084;</h1>

        @foreach ($models as $model)
            <a href="{{ route('catapult.relationships.create', ['modelId' => $model->id]) }}"
                class="text-gray-700 block font-bold model hover:text-white mb-16 border-2 border-dashed hover:border-solid p-4 rounded-md mb-4 hover:bg-gray-700">{{ $model->name }}.php</a>
        @endforeach


    </div>
@endsection
