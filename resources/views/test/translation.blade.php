<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script
        src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet"/>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        clifford: '#da373d',
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .content-auto {
                content-visibility: auto;
            }
        }
    </style>
</head>
{{--@dd($form_fields)--}}
<body class="m-2 !bg-gray-700">

<form action="{{route('translation.submit')}}" method="post">

    <select id="countries"
            name="languages"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 d">
        <option value="en">English</option>
        <option value="ar">Arabic</option>
        <option value="tr">Turkish</option>
        <option value="spa">Spanish</option>
        <option value="fr">French</option>
        <option value="de">Germany</option>
        <option value="pt">Portuguese</option>
        <option value="it">Italian</option>
        <option value="ru">Russian</option>
    </select>
    @csrf
    @foreach($form_fields as $key => $value)

        <div id="accordion-collapse" data-accordion="collapse"
             class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow  mt-4">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{$key}}</h5>
            @foreach($value as $field_label => $field_value)

                @if(!is_array($field_value))
                    <div class="font-normal text-gray-700 dark:text-gray-400">
                        <label for="message"
                               class="block mb-2 text-sm font-medium text-gray-900 ">{{$field_label}}</label>
                        <textarea rows="4"
                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 "
                                  name="{{$key}}[{{$field_label}}]"
                                  placeholder="{{$field_value}}"></textarea>
                    </div>
                @else
                    @php($i = 0)
                    @foreach($field_value as  $sub_field_label =>$sub_field_value)
                        @if(is_string($sub_field_value))
                            <div class="font-normal text-gray-700 dark:text-gray-400">
                                <label for="message"
                                       class="block mb-2 text-sm font-medium text-gray-900 ">{{$sub_field_value}}</label>
                                <textarea rows="4"
                                          class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 "
                                          name="{{$key}}[{{$field_label}}][{{$sub_field_label}}]"
                                          placeholder="{{$sub_field_value}}">

                                </textarea>
                            </div>

                        @endif
                        @if(is_object($sub_field_value))

                            @if(isset($sub_field_value->subLinks))

                                <div class="font-normal text-gray-700 dark:text-gray-400">
                                    <label for="message"
                                           class="block mb-2 text-sm font-medium text-gray-900 ">Sub link title
                                        ({{$sub_field_value->title}})</label>
                                    <textarea rows="4"
                                              class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 "
                                              name="{{$key}}[{{$field_label}}][{{$i}}][title]"
                                              placeholder="{{$sub_field_value->title}}"></textarea>
                                </div>
                                @php($j = 0)
                                @foreach($sub_field_value->subLinks as $sub_link)

                                    <div class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow m-4">
                                        <h5 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 "> Sub Link</h5>
                                        <div class="font-normal text-gray-700 dark:text-gray-400">
                                            <label for="message"
                                                   class="block mb-2 text-sm font-medium text-gray-900 ">Sub link
                                                ({{$sub_link}})</label>
                                            <textarea rows="4"
                                                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 "
                                                      name="{{$key}}[{{$field_label}}][{{$i}}][subLinks][{{$j}}]"
                                                      placeholder="{{$sub_link}}"></textarea>
                                        </div>
                                    </div>
                                    @php($j++)
                                @endforeach
                            @else

                                <div class="block w-full p-6 bg-white border border-gray-200 rounded-lg shadow m-4">

                                    <h5 class="mb-4 text-2xl font-bold tracking-tight text-gray-900 "> Card</h5>
                                    <div class="font-normal text-gray-700 dark:text-gray-400">
                                        <label for="message"
                                               class="block mb-2 text-sm font-medium text-gray-900 ">card title
                                            ({{$sub_field_value->title}})</label>
                                        <textarea rows="4"
                                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 "
                                                  name="{{$key}}[{{$field_label}}][{{$i}}][title]"
                                                  placeholder="{{$sub_field_value->title}}"></textarea>
                                    </div>
                                    <div class="font-normal text-gray-700 dark:text-gray-400">
                                        <label for="message"
                                               class="block mb-2 text-sm font-medium text-gray-900 ">card description
                                            ({{$sub_field_value->desc}})</label>
                                        <textarea rows="4"
                                                  class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 "
                                                  name="{{$key}}[{{$field_label}}][{{$i}}][desc]"
                                                  placeholder="{{$sub_field_value->desc}}"></textarea>
                                    </div>
                                </div>

                            @endif

                        @endif
                        @php($i++)
                    @endforeach
                @endif

            @endforeach
        </div>
    @endforeach
    <div class="container py-10 px-10 mx-0 min-w-full flex flex-col items-center">
        <button type="submit"
                class="focus:outline-none w-72 text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">
            Submit
        </button>
    </div>


</form>
{{--{{$form_fields}}--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

</body>
</html>
