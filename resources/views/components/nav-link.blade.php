@props(['active' => false, 'type' => 'anchor', 'menu_item' => false])

@if($type === 'button')
<button {{$attributes}} class="group flex items-center w-full p-2  rounded-lg {{$menu_item ? 'pl-11' : ''}}  {{$active ? 'text-white bg-[#3A7CE0]' : '' }} text-gray-900 dark:text-white hover:bg-[#3A7CE0] "> {{$slot}} </button>

@else
<a {{$attributes}} class="group flex items-center w-full p-2  rounded-lg {{$menu_item ? 'pl-11' : ''}}   {{$active ? 'text-white bg-[#3A7CE0]' : 'text-gray-900 dark:text-white hover:bg-[#3A7CE0]'}} " >{{$slot}}</a>

@endif
