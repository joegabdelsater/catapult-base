@extends('catapult::layout.base')

@section('content')
    <div class="p-8">
        <div class="mb-8">
            <p class="font-bold text-lg mb-4">Package(s) added to composer.json</p>
            <p class="mb-2"> The package(s) have been added to your composer.json file.</p>


            {{-- <p class="mb-2"> You can now run <code class="cursor-pointer bg-gray-700 text-white px-2 py-1">composer update -W</code> to install the newly added package(s).</p> --}}




            <p class="mb-2">When package installation is complete, run <code
                    class="cursor-pointer bg-gray-700 text-white px-2 py-1">sail artisan catapult:setup-packages</code> to
                setup all your packages:</p>

            <div class="mt-4">
                <p class="mb-2"><strong>If you are using filament:</strong></p>
                <p class="mb-2">If you are using laravel filament, you can now run <code
                        class="cursor-pointer bg-gray-700 text-white px-2 py-1">sail artisan filament:install --panels</code>
                    to
                    install filament.</p>

                <p class="mb-2">If you are using spatie filament translatable package, you can now run <code
                        class="cursor-pointer bg-gray-700 text-white px-2 py-1">sail artisan catapult:translate-filament-service-provider</code> to add translations to the filament admin
                    service
                    provider. <strong>Don't forget to change the locales to fit your project's needs</strong></p>
            </div>

            <div class="mt-8">
                <a href="{{ route('catapult.models.create') }}"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center self-end">Setup
                    Models</a>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            //add a function to copy to clipboard
            //execute the function everytime a code element is clicked
            document.querySelectorAll('code').forEach((code) => {
                code.addEventListener('click', () => {
                    copyToClipboard(code.innerText);
                    //show a toast message built from scratch
                    const toast = document.createElement('div');
                    toast.classList.add('fixed', 'bottom-0', 'right-0', 'bg-green-500', 'text-white', 'p-2',
                        'm-4', 'rounded-lg', 'shadow-lg', 'z-50');
                    toast.innerText = 'Copied to clipboard';
                    document.body.appendChild(toast);
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);

                });
            });

            //function to copy to clipboard
            function copyToClipboard(text) {
                const el = document.createElement('textarea');
                el.value = text;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
            }
        </script>
    @endpush
