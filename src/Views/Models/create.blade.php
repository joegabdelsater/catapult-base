@extends('catapult::layout.base')

@section('content')
    <div class="grid grid-cols-2 h-screen p-8">
        <div>
            <h1 class="text-2xl font-bold mb-4">Let's create some models!</h1>
            <form class="max-w-lg" method="POST" action="{{ route('catapult.models.store') }}">
                @csrf
                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="name" id="name"
                        class="focused block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder="Foo" autofocus required value="{{ old('name') }}" />

                    @error('name')
                        <div class="text-sm text-red-400 mt-2">{{ $message }}</div>
                    @enderror

                    <label for="name"
                        class="peer-focus:font-medium absolute text-sm text-gray-500  duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Model
                        name</label>
                </div>

                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="table_name" id="table_name"
                        class="focused block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder="Foo" autofocus value="{{ old('table_name') }}" />

                    @error('table_name')
                        <div class="text-sm text-red-400 mt-2">{{ $message }}</div>
                    @enderror

                    <label for="table_name"
                        class="peer-focus:font-medium absolute text-sm text-gray-500  duration-300 transform -translate-y-6 scale-75 top-2 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Table
                        name (Optional)</label>
                </div>

                <div class="flex items-center mb-4">
                    <input type="checkbox" id="has_translations" name="has_translations"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                    <label for="has_translations" class="ms-2 text-sm font-medium text-gray-400 dark:text-gray-500">You
                        model will have translatable fields (Spatie/Translatable)</label>
                </div>

                <div class="flex items-center mb-4">
                    <input type="checkbox" id="only_guard_id" name="only_guard_id"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                    <label for="only_guard_id" class="ms-2 text-sm font-medium text-gray-400 dark:text-gray-500">Only
                        Guard id (protected $guarded = ['id'])</label>
                </div>

                <div class="flex items-center mb-4">
                    <input type="checkbox" id="has_validation_request" name="has_validation_request"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600"
                        checked>
                    <label for="has_validation_request"
                        class="ms-2 text-sm font-medium text-gray-400 dark:text-gray-500">Create validation request</label>
                </div>

                <div class="flex flex-row justify-end">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center self-end">Save</button>
                </div>
            </form>
        </div>

        <div class="">
            <div class="flex flex-row items-center justify-between mb-2">
                <h1 class="text-2xl font-bold mb-4">Models</h1>
                <a href="{{ route('catapult.models.generate-all') }}"
                    onclick="return confirm('Are you sure you want to generate all models? This will overwrite any existing files.')"
                    class="p-2 text-gray-500 rounded-lg text-white text-base bg-rose-800 text-center block hover:bg-rose-900 mr-2">
                    Generate All
                </a>
            </div>

            @if (count($models) === 0)
                <div class="text-gray-700">Seems like you haven't created anything yet.</div>
            @endif
            <div class="">
                @foreach ($models as $model)
                    <div class="flex justify-between items-center p-3 border-b border-gray-200">
                        <div class="flex flex-row items-center">
                            @if ($model->created)
                                @component('catapult::components.icons.checkmark-circle', ['class' => 'h-5 w-5 mr-2', 'fill' => 'green'])
                                @endcomponent
                            @else
                                <a href="{{ route('catapult.models.generate', ['model' => $model->id]) }}"
                                    class="p-2 text-gray-500 rounded-lg text-white text-xs bg-rose-800 text-center block hover:bg-rose-900 mr-2">
                                    Generate

                                </a>
                            @endif
                            <div class="text-gray-700">{{ $model->name }}.php</div>
                        </div>

                        @if ($model->has_translations)
                            <div class="text-gray-400">Translatable</div>
                        @endif
                        @if ($model->only_guard_id)
                            <div class="text-gray-400">Only Guard id</div>
                        @endif

                        @if ($model->has_validation_request)
                            <div class="text-gray-400">Has validation request</div>
                        @endif

                        <form method="POST" action="{{ route('catapult.models.destroy', ['model' => $model->id]) }}">
                            @csrf
                            <button
                                onclick="return confirm('Are you sure you want to delete this model from the database?')">
                                @component('catapult::components.icons.delete')
                                @endcomponent
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
