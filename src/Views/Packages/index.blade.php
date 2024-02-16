@extends('catapult::layout.base')

@section('content')
    <div class="p-8">
        <div class="mb-8">
            <p class="font-bold text-lg mb-4">Welcome to Laravel Catapult!</p>
            <p class="mb-2"> Let's get started by setting up your project's dependencies.</p>
            <p> Select the packages you want to install, and
                Catapult will handle the packages installation & setup</p>
        </div>

        <form method="POST" action="{{ route('catapult.packages.add-to-composer') }}">
            @csrf
            <div class="flex flex-row items-center mb-4">
                <h2 class="text-bold text-xl font-bold mr-4">Package List</h2>
                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center self-end">Done</button>
            </div>
            @foreach ($packages as $packageKey => $package)
                <div class="flex items-center mb-4">
                    <input type="checkbox" id="{{ $packageKey }}" name="{{ $packageKey }}"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:border-gray-600">
                    <label for="has_translations"
                        class="ms-2 text-base font-medium text-gray-400 dark:text-gray-500">{{ $package['package_name'] }}{{$package['version']}}</label>
                </div>
            @endforeach
        </form>

    </div>
@endsection
