<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="!text-lg pr-3 whitespace-nowrap text-white mb-2">Prize & Spinner Configuration</h3>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-bladewind.table divider="thin" compact="true" searchable="true" search_placeholder="Find data..."
                        class_search="w-1/3">
                        <x-slot name="header" class="text-center">
                            <th class="!text-center">title</th>
                            <th class="!text-center">value</th>
                            <th class="!text-center">group 1</th>
                            <th class="!text-center">group 2</th>
                            <th class="!text-center">category</th>
                            <th class="!text-center">last update</th>
                            <th class="!text-center">action</th>
                        </x-slot>
                        @foreach ($prize as $data)
                            <tr class="text-center">
                                <td>{{ $data->title }}</td>
                                <td>Rp.{{ number_format($data->value) }}</td>
                                <td>{{ $data->first_prob + 0 }}%</td>
                                <td>{{ $data->second_prob + 0 }}%</td>
                                <td>{{ $data->catPrize->title }}</td>
                                <td>{{ $data->updated_at ? $data->updated_at->timezone('Asia/Jakarta') : '' }}</td>
                                <td>
                                    <div class="flex justify-center gap-2">
                                        <x-bladewind.button.circle class="w-8 h-8 !p-0" size="tiny" icon="pencil"
                                            tooltip="Edit" onclick="editDataPrize({{ $data }})" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-bladewind.table>
                </div>
            </div>
        </div>
    </div>

    <x-collapse collapsed="false">
        <x-slot name="title">
            Category Configuration
        </x-slot>
        <div class="block">
            <x-bladewind.table divider="thin" compact="true" search_placeholder="Find data..." class_search="w-1/3">
                <x-slot name="header" class="text-center">
                    <th class="!text-center">id</th>
                    <th class="!text-center">title</th>
                    <th class="!text-center">unit</th>
                    <th class="!text-center">action</th>
                </x-slot>
                @foreach ($cat_prize as $data)
                    <tr class="text-center">
                        <td>{{ $data->id }}</td>
                        <td>{{ $data->title }}</td>
                        <td>{{ $data->unit }}</td>
                        <td>
                            <div class="flex justify-center gap-2">
                                <x-bladewind.button.circle class="w-8 h-8 !p-0" size="tiny" icon="pencil"
                                    tooltip="Edit" onclick="editDataCatPrize({{ $data }})" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-bladewind.table>
        </div>
    </x-collapse>
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

    function editDataPrize(data) {
        $('input[name="id"]').val(data.id);
        $('input[name="title"]').val(data.title);
        $('input[name="value"]').val(data.value);
        $('input[name="first_prob"]').val(data.first_prob);
        $('input[name="second_prob"]').val(data.second_prob);
        $('select[name="cat_id"]').val(data.cat_id);
        showModal('crud-prize');
    }

    function editDataCatPrize(data) {
        $('input[name="id"]').val(data.id);
        $('input[name="title"]').val(data.title);
        $('select[name="unit"]').val(data.unit);
        showModal('crud-cat-prize');
    }

    function saveDataPrize() {
        $('#data-form').submit();
    }

    function saveDataCat() {
        $('#cat-data-form').submit();
    }
</script>

<x-bladewind::modal backdrop_can_close="false" name="crud-prize" center_action_buttons="true"
    ok_button_action="saveDataPrize()" ok_button_label="Save" close_after_action="true">

    <form method="post" action="{{ route('prize.update') }}" id="data-form" class="data-form-ajax">
        @csrf
        <b>Configuration Prize</b>
        <x-bladewind.input name="id" class="hidden" value="" />

        <x-bladewind.input required="true" name="title" label="Prize" error_message="Please enter title of Prize"
            value="" />

        <x-bladewind.input required="true" numeric="true" name="value" label="Value"
            error_message="Please enter value of Prize" value="" />

        <x-bladewind.input required="true" numeric="true" name="first_prob" label="First Probability"
            error_message="Please enter first probability" value="" />

        <x-bladewind.input required="true" numeric="true" name="second_prob" label="Second Probability"
            error_message="Please enter second probability" value="" />

        <select name="cat_id" id="cat_id" class="w-full bg-inherit">
            @foreach ($cat_prize as $data)
                <option value="{{ $data->id }}">{{ $data->title }}</option>
            @endforeach
        </select>
    </form>
</x-bladewind::modal>

<x-bladewind::modal backdrop_can_close="false" name="crud-cat-prize" center_action_buttons="true"
    ok_button_action="saveDataCat()" ok_button_label="Save" close_after_action="true">

    <form method="post" action="{{ route('spinconfig.update') }}" id="cat-data-form" class="data-form-ajax">
        @csrf
        <b>Category Prize</b>
        <x-bladewind.input name="id" class="hidden" value="" />

        <x-bladewind.input required="true" name="title" label="Title Category"
            error_message="Please enter title category" value="" />

        <select name="unit" id="unit" class="w-full bg-inherit">
            <option value="pcs">pcs</option>
            <option value="amount">amount</option>
        </select>
    </form>
</x-bladewind::modal>
