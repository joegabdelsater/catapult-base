@extends('catapult::layout.base')

@section('content')
    <div class="p-8 container mx-auto">
        <h1 class="text-2xl font-bold mb-8">Let's build migrations &#10084;</h1>

        @if (count($models) === 0)
            <div class="text-gray-700 text-lg">Seems like you haven't created any models yet. &#128549;</div>
        @endif

        @foreach ($models as $model)
            <div
                class="text-gray-700 block font-bold model group hover:text-white mb-16 border-2 border-dashed hover:border-solid p-4 rounded-md mb-4 hover:bg-gray-700 flex flex-row justify-between items-center">
                <div class="flex flex-row items-center">
                    <a href="{{ route('catapult.migrations.create', ['model' => $model->id]) }}"
                        class="mr-4">{{ $model->name }}.php</a>
                    @if ($model->migration && !$model->migration->created)
                        <p class="font-regular text-sm text-orange-400">{{ $model->warning_message }}</p>
                    @endif
                </div>
                <div class="flex flex-row items-center">
                    @if ($model->migration && !$model->migration->created)
                        <span class="text-xs text-rose-500 mr-2 group-hover:text-white">Migration exists but not
                            created</span>
                        <form action="{{ route('catapult.migrations.destroy', ['migration' => $model->migration->id]) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                @component('catapult::components.icons.delete', ['fill' => 'white'])
                                @endcomponent
                            </button>

                        </form>

                        <form action="{{ route('catapult.migrations.generate', ['model' => $model->id]) }}" method="POST">
                            @csrf

                            <button type="submit"
                                class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Create</button>
                        </form>
                    @endif
                    @if ($model->migration && $model->migration->created)
                        <span class="text-xs text-green-500 mr-2 group-hover:text-white">Migration created</span>
                        <div>
                            @component('catapult::components.icons.checkmark-circle', ['class' => 'h-5 w-5', 'fill' => 'green'])
                            @endcomponent
                        </div>
                    @endif


                </div>
            </div>
        @endforeach


    </div>
@endsection
