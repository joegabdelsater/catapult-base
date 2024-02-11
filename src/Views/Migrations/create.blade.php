@extends('catapult::layout.base')

@section('content')

    <div class="p-8 container w-full">
        <h1 class="text-2xl font-bold mb-4">Let's create your {{$model->name}} model's migration: create_<span class="text-rose-600">{{ $model->table_name }}</span>_table</h1>

        <p class="text-xl mb-8">You can refer to <a href="https://laravel.com/docs/10.x/migrations#available-column-types" target="_blank" class="text-rose-500 font-bold hover:text-rose-800">the official laravel migrations documentation</a> for more info.</p>


        <form class="w-full" method="POST" action="{{ route('catapult.migrations.store', ['model' => $model->id]) }}">
            @csrf
        </form>
        <div id="editor" class="w-full h-[700px] pb-10">{{ $base }}</div>
        <div class="flex flex-row justify-end mt-8">
            <button class="bg-rose-600 text-white px-4 py-2 rounded-md" type="button" onclick="submit()">Save</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/ace.js" type="text/javascript" charset="utf-8"></script>
{{-- <script src="https://www.unpkg.com/ace-builds@latest/src-noconflict/ace.js"></script> --}}
    <script>
        var editor = ace.edit("editor");
        editor.setTheme("ace/theme/monokai");
        editor.session.setMode("ace/mode/php");
        editor.setOptions({
            // autoScrollEditorIntoView: true,
            copyWithEmptySelection: true,
        });

        function submit() {
            var value = editor.getValue();
            console.log(value);
            document.querySelector('form').innerHTML += `<input type="hidden" name="migration_code" value="${value}">`;
            document.querySelector('form').submit();
        }
    </script>
@endpush
