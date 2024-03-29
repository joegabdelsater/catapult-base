<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Catapult</title>
</head>

<body>

    <aside class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
        aria-label="Sidebar">
        <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('catapult.welcome') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-700 {{ Route::currentRouteName() === 'catapult.welcome' ? 'bg-gray-700' : '' }} group">
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('catapult.models.create') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-700 {{ Route::currentRouteName() === 'catapult.models.create' ? 'bg-gray-700' : '' }}  group">
                        <span class="ms-3 mr-2">Models</span>
                        @if ($alerts['models'])
                            @component('catapult::components.icons.alert', ['fill' => 'yellow'])
                            @endcomponent
                        @endif
                    </a>
                </li>

                <li>
                    <a href="{{ route('catapult.relationships.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-700 {{ str_contains(Route::currentRouteName(), 'catapult.relationships') ? 'bg-gray-700' : '' }}  group">
                        <span class="ms-3">Relationships</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('catapult.migrations.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-700 {{ str_contains(Route::currentRouteName(), 'catapult.migrations') ? 'bg-gray-700' : '' }}  group">
                        <span class="ms-3 mr-2">Migrations & Validation</span>
                        @if ($alerts['validations'])
                            @component('catapult::components.icons.alert', ['fill' => 'yellow'])
                            @endcomponent
                        @endif
                    </a>
                </li>

                <li>
                    <a href="{{ route('catapult.controllers.create') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-700 {{ str_contains(Route::currentRouteName(), 'catapult.controllers') ? 'bg-gray-700' : '' }}  group">
                        <span class="ms-3 mr-3">Controllers</span>
                        @if ($alerts['controllers'])
                            @component('catapult::components.icons.alert', ['fill' => 'yellow'])
                            @endcomponent
                        @endif
                    </a>
                </li>

                <li>
                    <a href="{{ route('catapult.routes.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-700 {{ str_contains(Route::currentRouteName(), 'catapult.routes') ? 'bg-gray-700' : '' }}  group">
                        <span class="ms-3">Routes</span>
                    </a>
                </li>
        </div>
    </aside>

    <div class="sm:ml-64">
        @yield('content')
    </div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
@stack('scripts')

</html>
