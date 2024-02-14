<div class="bg-white rounded-md mb-2 grid grid-cols-[26fr_1fr] items-center p-4">
    <div class="">
        <div class="flex flex-row flex-start items-center">
            <p class="text-sm font-bold">Route::<span class='text-sky-500'>{{ $method }}</span>(</p>
            <input type="text"
                class="focused px-2 text-sm text-white font-bold rounded bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-80 "
                placeholder="/path/{parameter}" name="" required value="" />
            <p>, </p>
            <input type="text"
                class="focused px-2 text-sm text-white font-bold rounded bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-40 "
                placeholder="controllerMethod" name="" required value="" />
            <p class="text-sm font-bold">)->name(</p><input type="text"
                class="focused px-2 text-sm text-white font-bold rounded bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer w-40 "
                placeholder="route.name" name="" required value="" />
            <p>);</p>
        </div>

    </div>

    <div>
        <button type="submit" form="create-model-relationship"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Save</button>
    </div>


</div>
