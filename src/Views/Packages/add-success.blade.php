@extends('catapult::layout.base')

@section('content')
    <div class="p-8">
        <div class="mb-8">
            <p class="font-bold text-lg mb-4">Package(s) added to composer.json</p>
            <p class="mb-2"> The package(s) have been added to your composer.json file.</p>
            <p class="mb-2"> You can now run <code class="bg-gray-700 text-white px-2 py-1">composer update</code> to install the newly added package(s).</p>

            <p>When package installation is complete, run <code class="bg-gray-700 text-white px-2 py-1">php artisan catapult:setup-packages</code> to setup all your packages:</p>
      

            <div class="mt-8">
                <a href="{{ route('catapult.models.create') }}"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center self-end">Setup Models</a>
            </div>
        </div>
    @endsection
