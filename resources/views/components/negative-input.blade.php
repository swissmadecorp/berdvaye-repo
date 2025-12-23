@props(['label','text','model','format'])
<div class="flex pb-2.5 items-center">
    <label for="{{$label}}" class="block text-sm font-medium text-gray-900 dark:text-white w-32">{{$text}}</label>
    <input id="{{$label}}" type="number" wire:model="{{$model}}" class="text-right bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
    
</div>