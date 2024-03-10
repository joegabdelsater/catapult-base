@extends('catapult::layout.base')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">Let's setup the fields on your model</h1>
        <p>Fields are the columns in your database table. You can add as many fields as you want.</p>
        <p>Here you define the type of the column in the database, how you wish to validate it, and configure it for your
            filament dashboard</p>


        <div class="mt-8 grid grid-cols-2 gap-20">
            @if ($errors->any())
                {{-- show all errors --}}
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4" role="alert">
                    <strong class="font-bold">Holy smokes! </strong>
                    <span class="block sm:inline">{{ $errors->first() }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            @endif
            <div>
                <form action="{{ route('catapult.field.store', ['modelId' => $model->id]) }}" method="POSt">
                    @csrf
                    <!-- Modal content -->
                    <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5 mb-4">
                        <!-- Modal header -->
                        <div
                            class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Add Field
                            </h3>
                        </div>
                        <!-- Modal body -->
                        <div class="grid gap-4 mb-4 sm:grid-cols-2">
                            <div>
                                <label for="name"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Column
                                    name</label>
                                <input type="text" name="column_name" id="name"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Type column name" required="true">
                            </div>

                            <div>
                                <label for="category"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Column
                                    Type</label>
                                <select id="dbColumnType" name="column_type" required=""
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option selected="">Select field type</option>
                                    <option value="string">String</option>
                                    <option value="text">Text</option>
                                    <option value="boolean">Boolean</option>
                                    <option value="integer">Integer</option>
                                    <option value="bigInteger">Big Integer</option>
                                    <option value="float">Float</option>
                                    <option value="double">Double</option>
                                    <option value="decimal">Decimal</option>
                                    <option value="enum">Enum</option>
                                    <option value="json">Json</option>
                                    <option value="date">Date</option>
                                    <option value="datetime">DateTime</option>
                                    <option value="time">Time</option>
                                    <option value="relationship">Relationship</option>
                                    {{-- <option value="PH">Year</option>
                                    <option value="PH">Binary</option>
                                    <option value="PH">Uuid</option>
                                    <option value="PH">IpAddress</option>
                                    <option value="PH">MacAddress</option> --}}

                                </select>
                            </div>
                        </div>
                        <div id="dbColumnsDynamicInputs" class="grid gap-4 mb-4 sm:grid-cols-2"></div>
                        <div class="grid gap-4 mb-4 sm:grid-cols-2">
                            <div>
                                <label for="default"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Default
                                    Value (Optional)</label>
                                <input type="text" name="default" id="default"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Defaut value">
                            </div>


                            <div class="flex items-center pt-6">
                                <input id="nullable" type="checkbox"  name="nullable"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="nullable"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nullable</label>

                            </div>

                            <div class="flex items-center pt-6">
                                <input id="unique" type="checkbox"  name="unique"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="unique"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Unique</label>

                            </div>

                            <div class="flex items-center pt-6">
                                <input id="translatable" type="checkbox" name="translatable"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="translatable"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Translatable</label>
                            </div>
                        </div>
                    </div>

                    <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5 mb-4">
                        <!-- Modal header -->
                        <div
                            class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Add Validation
                            </h3>
                        </div>

                        <div>
                            <label for="validation"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valdiation</label>
                            <input type="text" name="validation" id="validation"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="required|min:8">
                        </div>
                        {{-- Checkboxes --}}
                        {{-- <div class="grid gap-4 mb-4 sm:grid-cols-4 mb-8">
                            <div class="flex items-center pt-6">
                                <input id="required" type="checkbox" value="" name="required"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="required"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Required</label>
                            </div>

                            <div class="flex items-center pt-6">
                                <input id="accepted" type="checkbox" value="" name="accepted"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="accepted"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Accepted</label>
                            </div>
                            <div class="flex items-center pt-6">
                                <input id="string" type="checkbox" value="" name="string"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="string"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">String</label>

                            </div>

                            <div class="flex items-center pt-6">
                                <input id="boolean" type="checkbox" value="" name="boolean"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="boolean"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Boolean</label>

                            </div>

                            <div class="flex items-center pt-6">
                                <input id="email" type="checkbox" value="" name="email"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="email"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Email</label>

                            </div>

                            <div class="flex items-center pt-6">
                                <input id="nullable" type="checkbox" value="" name="nullable"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="nullable"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nullable</label>
                            </div>

                            <div class="flex items-center pt-6">
                                <input id="sometimes" type="checkbox" value="" name="sometimes"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="sometimes"
                                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sometimes</label>

                            </div>

                        </div> --}}

                        {{-- Inputs --}}
                        {{-- <div class="grid gap-4 mb-4 sm:grid-cols-4">

                            <div>
                                <label for="min"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Min</label>
                                <input type="text" name="min" id="min"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Defaut value" >
                            </div>

                            <div>
                                <label for="max"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Max</label>
                                <input type="text" name="max" id="max"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Defaut value" >
                            </div>

                            <div>
                                <label for="exists"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Exists</label>
                                <input type="text" name="exists" id="exists"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="table,column" >
                            </div>

                            <div>
                                <label for="required_if"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Required
                                    If</label>
                                <input type="text" name="required_if" id="required_if"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="anotherfield,value">
                            </div>

                        </div> --}}
                    </div>


                    <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5 mb-4">
                        <!-- Modal header -->
                        <div
                            class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Admin panel listing column configuration
                            </h3>
                        </div>
                        <!-- Modal body -->
                        <div class="grid gap-4 mb-4 sm:grid-cols-2">

                            <div>
                                <label for="listColumnType"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Column
                                    type in listing table</label>
                                <select id="listColumnType" name="admin_column_type"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option selected value="">Select list table column type</option>
                                    <option value="text_column">Text Column</option>
                                    <option value="relationship_text_column">Relationship Text Column</option>
                                    {{-- <option value="icon_column">Icon Column</option> --}}
                                    <option value="image_column">Image Column</option>
                                    <option value="color_column">Color Column</option>
                                    {{-- <option value="select_column">Select Column</option> --}}
                                    <option value="toggle_column">Toggle Column</option>
                                    {{-- <option value="text_input_column">Text Input Column</option> --}}
                                    <option value="checkbox_column">Checkbox column</option>
                                </select>
                            </div>
                        </div>
                        <div id="listDynamicInputs" class="grid gap-4 mb-4 sm:grid-cols-2"></div>
                    </div>

                    <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5 mb-4">
                        <!-- Modal header -->
                        <div
                            class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Admin panel create/edit form field configuration
                            </h3>
                        </div>
                        <!-- Modal body -->
                        <div class="grid gap-4 mb-4 sm:grid-cols-2">

                            <div>
                                <label for="formFieldType"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Field type in
                                    create/edit form</label>
                                <select id="formFieldType" name="admin_field_type"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option selected value="">Select form field column type</option>
                                    <option value="text_input">Text Input</option>
                                    <option value="select">Select</option>
                                    <option value="relationship_select">Relationship Select</option>
                                    <option value="checkbox">Checkbox</option>
                                    <option value="toggle">Toggle</option>
                                    <option value="radio">Radio</option>
                                    <option value="rich_editor">Rich Editor</option>
                                    <option value="markdown_editor">Markdown Editor</option>
                                    <option value="textarea">Textarea</option>
                                    <option value="file_upload">File Upload</option>
                                    {{-- <option value="repeater">Repeater</option> --}}
                                    <option value="date_time">Date-time Picker</option>
                                    <option value="time">Time Picker</option>
                                    <option value="date">Date Picker</option>
                                </select>
                            </div>
                        </div>
                        <div id="formFieldDynamicInputs" class="grid gap-4 mb-4 sm:grid-cols-2"></div>
                    </div>

                    <div class="flex flex-row justify-end mt-4">
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center self-end">Save</button>
                    </div>

                </form>
            </div>

            <div>
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold">{{ $model->name }} Model</h2>
                    <form action="{{ route('catapult.fields.build', ['modelId' => $model->id]) }}" method="POST">
                        @csrf

                        <button type="submit"
                            class="btn text-white hover:text-red-700 bg-red-600 dark:hover:text-red-500 p-2 rounded">Generate</button>
                    </form>
                </div>
                <div class="mt-4">
                    @foreach ($model->fields as $field)
                        <div
                            class="flex justify-between items-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5 mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $field->column_name }}
                                </h3>
                                <div class="flex flex-row ">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $field->column_type }}, {{ $field->admin_column_type }},
                                        {{ $field->admin_field_type }} {{ $field->nullable ? ', Nullable' : '' }}
                                        {{ $field->unique ? ', Unique' : '' }}
                                        {{ $field->default ? ', Default: ' . $field->default : '' }}
                                        {{ $field->validation ? ', Validation: ' . $field->validation : '' }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <form action="{{ route('catapult.field.destroy', ['fieldId' => $field->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-400 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                                </form>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        const models = @json($models);
        document.addEventListener('DOMContentLoaded', function() {
            const dbColumnTypeSelect = document.getElementById('dbColumnType');

            dbColumnTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                updateDbColumnsDynamicInputs(selectedType);
            });

            const listColumnTypeSelect = document.getElementById('listColumnType');

            listColumnTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                updateListDynamicInputs(selectedType);
            });

            const formFieldTypeSelect = document.getElementById('formFieldType');

            formFieldTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                updateFormFieldDynamicInputs(selectedType);
            });

        });

        function getTextField(label, name, placeholder, required = false, type = 'text', ) {
            return `<div><label for="${name}"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">${label}</label>  <input type="${type}" name="${name}" id="${name}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                            placeholder="${placeholder}" ${required ? 'require' : '' }></div>`;
        }

        function getTextAreaField(label, name, placeholder, required = false) {
            return `<div><label for="${name}"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">${label}</label>  <textarea  name="${name}" id="${name}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                            placeholder="${placeholder}" required="${required}"></textarea></div>`;
        }

        function getSelectField(label, name, options) {
            let optionsHtml = '';
            options.forEach(option => {
                optionsHtml += `<option value="${option.value}">${option.label}</option>`;
            });
            return `<div><label for="${name}"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">${label}</label>  <select id="${name}" name="${name}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            ${optionsHtml}
                                        </select></div>`;
        }

        function getCheckboxField(label, name) {
            return `<div class="flex items-center pt-6">
                                            <input id="${name}" type="checkbox"  name="${name}"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="${name}"
                                                class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">${label}</label>
                                        </div>`;
        }

        function updateDbColumnsDynamicInputs(selectedType) {
            const dbColumnsDynamicInputs = document.getElementById('dbColumnsDynamicInputs');
            dbColumnsDynamicInputs.innerHTML = ''; // Clear current inputs

            if (selectedType === 'string' || selectedType === 'text') {
                dbColumnsDynamicInputs.innerHTML += getTextField('length', 'column_config[length]',
                    'length')
            } else if (selectedType === 'enum') {
                dbColumnsDynamicInputs.innerHTML += getTextField('Enum Options', 'column_config[enum_options]',
                    'Enter enum options comma-separated')
            } else if (selectedType === 'decimal') {
                dbColumnsDynamicInputs.innerHTML += getTextField('Precision (total digits)', 'column_config[precision]',
                    'Enter precision')
                dbColumnsDynamicInputs.innerHTML += getTextField('Scale (decimal digits)', 'column_config[scale]',
                    'Enter scale')
            } else if (selectedType === 'float') {
                dbColumnsDynamicInputs.innerHTML += getTextField('Precision (total digits)', 'column_config[precision]',
                    'Enter precision')
                dbColumnsDynamicInputs.innerHTML += getTextField('Scale (decimal digits)', 'column_config[scale]',
                    'Enter scale')
            } else if (selectedType === 'double') {
                dbColumnsDynamicInputs.innerHTML += getTextField('Precision (total digits)', 'column_config[precision]',
                    'Enter precision')
                dbColumnsDynamicInputs.innerHTML += getTextField('Scale (decimal digits)', 'column_config[scale]',
                    'Enter scale')
            } else if (selectedType === 'relationship') {
                dbColumnsDynamicInputs.innerHTML += getSelectField('Related Model', 'column_config[related_model]', models
                    .map(model => ({
                        value: model.name,
                        label: model.name
                    })))

                dbColumnsDynamicInputs.innerHTML += getSelectField('On Delete', 'column_config[on_delete]', [{
                    value: 'cascade',
                    label: 'Cascade'
                }, {
                    value: 'restrict',
                    label: 'Restrict'
                }, {
                    value: 'set_null',
                    label: 'Set Null'
                }, {
                    value: 'no_action',
                    label: 'No Action'
                }])
            }
            // Add more conditions for other types and configurations
        }

        function updateListDynamicInputs(selectedType) {
            const listDynamicInputs = document.getElementById('listDynamicInputs');
            listDynamicInputs.innerHTML = ''; // Clear current inputs

            if (selectedType === 'icon_column') {
                listDynamicInputs.innerHTML += getTextField('Icon', 'admin_column_config[icon]', 'Enter icon class')
            } else if (selectedType === 'relationship_text_column') {
                listDynamicInputs.innerHTML += getTextField('Related attribute', 'admin_column_config[related_attribute]',
                    'relationship.name', 'text', 'true')
            } else if (selectedType === 'image_column') {
                listDynamicInputs.innerHTML += getTextField('Size', 'admin_column_config[size]', '40')
                listDynamicInputs.innerHTML += getTextField('Disk', 'admin_column_config[disk]', 'public')
            }
        }

        function updateFormFieldDynamicInputs(selectedType) {
            const formFieldDynamicInputs = document.getElementById('formFieldDynamicInputs');
            formFieldDynamicInputs.innerHTML = ''; // Clear current inputs

            if (selectedType === 'text_input') {
                formFieldDynamicInputs.innerHTML += getTextField('Label', 'admin_field_config[label]', 'Enter label')

                formFieldDynamicInputs.innerHTML += getTextField('Min length', 'admin_field_config[min]',
                    'Enter min length')
                formFieldDynamicInputs.innerHTML += getTextField('Max length', 'admin_field_config[max]',
                    'Enter max length')

                formFieldDynamicInputs.innerHTML += getCheckboxField('Required', 'admin_field_config[required]')

                formFieldDynamicInputs.innerHTML += getCheckboxField('Disabled', 'admin_field_config[disabled]')

                formFieldDynamicInputs.innerHTML += getCheckboxField('Readonly', 'admin_field_config[readonly]')
                formFieldDynamicInputs.innerHTML += getCheckboxField('Password', 'admin_field_config[[password]')
                formFieldDynamicInputs.innerHTML += getCheckboxField('Numeric', 'admin_field_config[numeric]')


            } else if (selectedType === 'select') {
                formFieldDynamicInputs.innerHTML += getTextAreaField('Options', 'admin_field_config[options]',
                    'key1:value 1,key2:value 2',
                    true)
                formFieldDynamicInputs.innerHTML += getCheckboxField('Searchable', 'admin_field_config[searchable]')
                formFieldDynamicInputs.innerHTML += getCheckboxField('Multiple', 'admin_field_config[multiple]')
                formFieldDynamicInputs.innerHTML += getCheckboxField('Required', 'admin_field_config[required]')

            } else if (selectedType === 'relationship_select') {
                formFieldDynamicInputs.innerHTML += getSelectField('Related Model', 'admin_field_config[related_model]',
                    models.map(model => ({
                        value: model.name,
                        label: model.name
                    })))

                formFieldDynamicInputs.innerHTML += getTextField('Pluck', 'admin_field_config[pluck]', 'name, id')

                formFieldDynamicInputs.innerHTML += getCheckboxField('Searchable', 'admin_field_config[searchable]')
                formFieldDynamicInputs.innerHTML += getCheckboxField('Multiple', 'admin_field_config[multiple]')
                formFieldDynamicInputs.innerHTML += getCheckboxField('Required', 'admin_field_config[required]')

            } else if (selectedType === 'radio') {
                formFieldDynamicInputs.innerHTML += getTextAreaField('Options', 'admin_field_config[options]',
                    'key1:value 1,key2:value 2',
                    true)
            } else if (selectedType === 'time' || selectedType === 'date' || selectedType === 'date_time') {
                formFieldDynamicInputs.innerHTML += getTextField('Format', 'admin_field_config[format]', 'Enter format')
            } else if (selectedType === 'file_upload') {
                formFieldDynamicInputs.innerHTML += getTextField('Disk', 'admin_field_config[disk]', 'public')
                formFieldDynamicInputs.innerHTML += getTextField('Directory', 'admin_field_config[directory]', 'directory')
                formFieldDynamicInputs.innerHTML += getTextField('Accepted File Types',
                    'admin_field_config[accepted_file_types]', 'application/pdf, image/*')
                formFieldDynamicInputs.innerHTML += getTextField('Min size', 'admin_field_config[min_size]',
                    'Enter min size', '512')
                formFieldDynamicInputs.innerHTML += getTextField('Max size', 'admin_field_config[max_size]',
                    'Enter max size', '1024')


                formFieldDynamicInputs.innerHTML += getCheckboxField('Multiple', 'admin_field_config[multiple]')
                formFieldDynamicInputs.innerHTML += getCheckboxField('Image', 'admin_field_config[image]')
                formFieldDynamicInputs.innerHTML += getCheckboxField('Avatar', 'admin_field_config[avatar]')
                formFieldDynamicInputs.innerHTML += getCheckboxField('Image Editor', 'admin_field_config[image_editor]')
                formFieldDynamicInputs.innerHTML += getCheckboxField('Openable', 'admin_field_config[openable]')
                formFieldDynamicInputs.innerHTML += getCheckboxField('Required', 'admin_field_config[required]')

            }
        }
    </script>
@endpush
