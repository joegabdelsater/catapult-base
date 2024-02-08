@extends('catapult::layout.base')

@section('content')
    <div class="grid grid-cols-[1000px_200px] gap-16 h-screen p-8 ">
        <div>
            <h1 class="text-2xl font-bold mb-4">Let's setup your relationships</h1>

            @foreach ($models as $mKey => $model)
                <div class=" mb-16 border-2 border-dashed p-8 rounded-md">
                    <div class="mb-4  flex flex-row justify-between items-center">
                        <p class="text-gray-700 font-bold">{{ $model->name }}.php</p>

                        <div class="cursor-pointer js-arrow-up {{ $mKey > 0 ? 'hidden' : '' }}"
                            data-arrow="arrow-up-{{ $model->name }}">
                            @component('catapult::components.icons.chevron-up')
                            @endcomponent
                        </div>

                        <div class="cursor-pointer js-arrow-down   {{ $mKey > 0 ? '' : 'hidden' }}"
                            data-arrow="arrow-up-{{ $model->name }}">
                            @component('catapult::components.icons.chevron-down')
                            @endcomponent
                        </div>

                    </div>

                    <div class="w-full  js-{{ $model->name }}-container {{ $mKey > 0 ? 'hidden' : '' }}">
                        @foreach ($relationships as $rKey => $relationship)
                            <div class="text-gray text-sm font-bold underline mb-2">{{ $relationship }}</div>

                            <div class="bg-gray-700 p-4 rounded-md w-full h-80 mb-4 relative overflow-hidden"
                                ondrop="drop(event, '{{ $rKey }}', '{{ $model->name }}', '{{$relationship}}')"
                                ondragover="allowDrop(event)" data-relationship="{{ $rKey }}"
                                data-model="{{ $mKey }}">

                                <div
                                    class="h-full w-full js-{{ $model->name }}-{{ $rKey }} overflow-y-scroll py-4 px-2">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

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
                                draggable="true" ondragstart="drag(event, '{{ $model->name }}::class')">
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
    var deleteComponent = `@component('catapult::components.icons.delete') @endcomponent`;
</script>

<script src="{{ asset('joegabdelsater/catapult-base/js/relationships/main.js') }}"></script>

<script src="{{ asset('joegabdelsater/catapult-base/js/relationships/dnd.js') }}"></script>

@endpush
