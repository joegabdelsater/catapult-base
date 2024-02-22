<div class="bg-white rounded-md mb-2 grid grid-cols-[26fr_1fr]" data-origin="">
    <div class=" p-4">
        <div class="flex flex-row flex-start items-center">
            <p class="text-sm font-bold mr-2">public function</p>
            <input type="text"
                class="focused px-2 text-sm text-white font-bold rounded bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-40 "
                placeholder="functionName" name="r[{{ $current->form_input_key }}][relationship_method_name]" required
                value="{{ $current->relationship_method_name }}" />
            <p class="text-sm font-bold">( ) {</p>
        </div>

        <div class="flex flex-row flex-start items-center">
            &nbsp;&nbsp;&nbsp;&nbsp;
            <p class="text-sm font-bold"><span class="text-sky-500">return
                </span><span class="text-orange-600">$this->{{ $current->relationship_method }}</span>
                <span>(</span>
                @if ($current->relationship !== 'ploymorphic_morph_to')
                    {{ str_replace('::class', '', $current->relationship_model) }}<span
                        class="text-sky-500">::class</span>
            </p>
            @endif
            @foreach ($relationship_parameters as $parameter)
                <span>,</span>
                <input type="text"
                    class="focused block mx-1 font-bold px-2 w-40 text-sm text-white rounded bg-gray-700  border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-40"
                    placeholder="{{ $parameter }}" name="r[{{ $current->form_input_key }}][{{ $parameter }}]"
                    value="{{ $current->{$parameter} }}" />
            @endforeach


            <input type="hidden" name="r[{{ $current->form_input_key }}][model]" value="{{ $current->model }}" />
            <input type="hidden" name="r[{{ $current->form_input_key }}][relationship_model]"
                value="{{ $current->relationship_model }}" />
            <input type="hidden" name="r[{{ $current->form_input_key }}][relationship]"
                value="{{ $current->relationship }}" />
            <input type="hidden" name="r[{{ $current->form_input_key }}][id]" value="{{ $current->id }}" />
            <input type="hidden" name="r[{{ $current->form_input_key }}][relationship_method]"
                value="{{ $current->relationship_method }}" />
                <input type="hidden" name="r[{{ $current->form_input_key }}][table]"
                value="{{ $current->table }}" />

                <input type="hidden" name="r[{{ $current->form_input_key }}][related_model_foreign_key]"
                value="{{ $current->related_model_foreign_key }}" />
                <input type="hidden" name="r[{{ $current->form_input_key }}][polymorphic_relation]"
                value="{{ $current->polymorphic_relation }}" />
            <span class="font-bold">);</span>
        </div>
        <p>}</p>
    </div>



    <button onclick="deleteRelationship('{{ $current->id }}', '{{ $current->catapult_model_id }}')" type="button"
        class="bg-red-600 rounded-r-md flex items-center justify-center h-full w-full">
        <div class="delete-btn">
            @component('catapult::components.icons.delete')
            @endcomponent
        </div>
    </button>

</div>
