@props([
    'collapsed' => true,
])
@php
    $collapsed = filter_var($collapsed, FILTER_VALIDATE_BOOLEAN);
@endphp
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div id="container-collapse">
            <div class="flex justify-between items-center text-white">
                <h3 class="!text-lg pr-3 whitespace-nowrap text-white">
                    {{ $title }}
                </h3>
                <span class="w-full border-b border-white"></span>
                <button id="prize-item-btn" class="border border-white rounded-full px-3 ml-3">
                    <span id="label-opened" class="!text-sm whitespace-nowrap">Click to collapse</span>
                    <span id="label-closed" class="!text-sm whitespace-nowrap hidden">Collapsed, click to
                        expand</span>
                </button>
            </div>
            <div id="prize-item-content" class="collapsible-content hidden mt-2">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <!-- Content goes here -->
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        @if ($collapsed)
            $('#prize-item-content').hide();
        @else
            $('#prize-item-content').show();
        @endif
        $('#prize-item-btn').click(function() {
            $('.collapsible-content').slideToggle();

            // Toggle the visibility of label-opened and label-closed spans
            $('#label-opened').toggleClass('hidden');
            $('#label-closed').toggleClass('hidden');
        });
    });
</script>
