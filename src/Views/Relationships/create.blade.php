@extends('catapult::layout.base')


@section('content')
    <div class="grid grid-cols-[800px_300px] gap-16 h-screen p-8 ">
        <div>
            <h1 class="text-2xl font-bold mb-4">Let's setup your relationships</h1>

            @foreach ($models as $mKey => $model)
                <div class=" mb-16 border-2 border-dashed p-8 rounded-md">
                    <div class="mb-4  flex flex-row justify-between items-center">
                        <p class="text-gray-700 font-bold">{{ $model->name }}.php</p>

                        <div class="cursor-pointer js-arrow-up {{$mKey > 0 ? 'hidden' : '' }}" data-arrow="arrow-up-{{ $model->name }}">
                            @component('catapult::components.icons.chevron-up')
                            @endcomponent
                        </div>

                        <div class="cursor-pointer js-arrow-down   {{$mKey > 0 ? '' : 'hidden' }}" data-arrow="arrow-up-{{ $model->name }}">
                            @component('catapult::components.icons.chevron-down')
                            @endcomponent
                        </div>

                    </div>

                    <div class="w-full  grid grid-cols-2 gap-6 js-{{ $model->name }}-container {{ $mKey > 0 ? 'hidden' : ''}}">
                        @foreach ($relationships as $rKey => $relationship)
                            <div class="bg-sky-100 p-4 rounded-md w-full h-80 mb-4 relative overflow-hidden"
                                ondrop="drop(event, '{{ $rKey }}', '{{ $model->name }}')"
                                ondragover="allowDrop(event)" data-relationship="{{ $rKey }}"
                                data-model="{{ $mKey }}">
                                <div class="text-gray text-sm font-bold underline">{{ $relationship }}</div>

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
        document.addEventListener('DOMContentLoaded', function() {
            // Select all the arrow-up elements
            const arrowUps = document.querySelectorAll('.js-arrow-up');
            arrowUps.forEach(arrowUp => {
                arrowUp.addEventListener('click', function() {
                    const modelName = this.getAttribute('data-arrow').replace('arrow-up-', '');
                    // Hide the arrow up
                    this.style.display = 'none';
                    // Show the arrow down
                    document.querySelector(`.js-arrow-down[data-arrow="arrow-up-${modelName}"]`)
                        .style.display = 'grid';
                    // Hide the container
                    document.querySelector(`.js-${modelName}-container`).style.display = 'none';
                });
            });

            // Select all the arrow-down elements
            const arrowDowns = document.querySelectorAll('.js-arrow-down');
            arrowDowns.forEach(arrowDown => {
                arrowDown.addEventListener('click', function() {
                    const modelName = this.getAttribute('data-arrow').replace('arrow-up-',
                    ''); // Note: your data-arrow attributes for down are currently the same as for up, which might be a mistake.
                    // Show the arrow up
                    document.querySelector(`.js-arrow-up[data-arrow="arrow-up-${modelName}"]`).style
                        .display = 'grid';
                    // Hide the arrow down
                    this.style.display = 'none';
                    // Show the container
                    document.querySelector(`.js-${modelName}-container`).style.display = 'grid';
                });
            });
        });

        function allowDrop(event) {
            event.preventDefault();
        }

        function drag(event, modelName) {
            event.dataTransfer.setData("origin", modelName);
        }

        function drop(event, relationship, model) {
            event.preventDefault();

            var origin = event.dataTransfer.getData("origin");
            var element = document.querySelector(`.js-${model}-${relationship}`);

            const newClass = document.createElement('div');
            newClass.innerHTML =
                `${origin} @component('catapult::components.icons.delete') @endcomponent`;
            newClass.className =
                'bg-white p-2 rounded-md mb-2 font-medium text-sm flex flex-row justify-between items-center cursor-pointer';

            newClass.setAttribute('data-origin', origin);
            newClass.addEventListener('click', function() {
                this.remove();
            });

            element.appendChild(newClass);
        }
    </script>
@endpush
