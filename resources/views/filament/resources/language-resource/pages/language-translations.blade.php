<x-filament-panels::page>
    <div class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
         id="subformcont">
        <div class="tab-content">

            {{ app()->setLocale($language->iso_code) }}
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg" id="lang_{{ $language->iso_code }}" role="tabpanel">
                <form method="POST" class="form" action=""
                      enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="lang" value="{{ $language->iso_code }}">
                    <!--begin::Tab-->
                    <div class="tab-pane show active px-7">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-xl-12 my-2">
                                <table
                                    class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="p-4">Key</th>
                                        <th scope="col" class="p-4">Value</th>
                                    </tr>
                                    </thead>
                                    @foreach($translations as $t)

                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="px-6 py-6"
                                                style="vertical-align: middle;">{{ $t->key }}</td>
                                            <td style="width: 60%; vertical-align: middle;" class="p-0">

                                                <input type="text"
                                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                       style="background-color: transparent !important;"
                                                       name="trans[{{$t->id}}]" value="{{ $t->value }}"/>
                                            </td>

                                        </tr>
                                    @endforeach

                                </table>

                            </div>


                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Tab-->
                    <button type="submit" style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);" class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 disabled:pointer-events-none disabled:opacity-70 rounded-lg fi-btn-color-primary gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus:ring-custom-500/50 dark:focus:ring-custom-400/50 fi-ac-btn-action" >
                        Submit
                    </button>

                </form>

            </div>


        </div>
    </div>
</x-filament-panels::page>
