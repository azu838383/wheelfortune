<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="!text-lg pr-3 whitespace-nowrap text-white mb-2">User Error Log</h3>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <x-bladewind.table divider="thin" compact="true" search_placeholder="Find data..." class_search="w-1/3">
                        <x-slot name="header" class="text-center">
                            <th class="!text-center">IP address</th>
                            <th class="!text-center">activity</th>
                            <th class="!text-center">detail</th>
                            <th class="!text-center">time</th>
                        </x-slot>
                        @foreach ($logs as $data)
                            <tr class="text-center">
                                <td>{{ $data->from }}</td>
                                <td class="text-left w-[500px]">{{ $data->activity }}</td>
                                <td class="text-left w-[320px]">{{ $data->error_detail }}</td>
                                <td class="">{{ $data->created_at ? $data->created_at->timezone('Asia/Jakarta') : '' }}</td>
                            </tr>
                        @endforeach
                    </x-bladewind.table>
                    @if (!$form)
                        <div class="mt-4">
                            {{ $logs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@if (session('success'))
    <div id="timeout" class="fixed top-4 right-4 w-[350px]">
        <x-bladewind.alert type="success">
            {{ session('success') }}
        </x-bladewind.alert>
    </div>
@endif
@if (session('error'))
    <div id="timeout" class="fixed top-4 right-4 w-[350px]">
        <x-bladewind.alert type="error">
            {{ session('error') }}
        </x-bladewind.alert>
    </div>
@endif

<script>
    if ($('#timeout').length) {
        setTimeout(function() {
            $('#timeout').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }
</script>
