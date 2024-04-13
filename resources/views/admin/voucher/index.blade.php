<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="!text-lg pr-3 whitespace-nowrap text-white mb-2">Voucher Generator</h3>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between mb-4">
                        <x-bladewind.button size="small" type="primary" onclick="storeVoucher()" class="h-11">
                            Generate New Voucher
                        </x-bladewind.button>
                        <form method="post" action="{{ route('voucher.search.bydate') }}" class="flex items-start">
                            @csrf
                            <div class="flex gap-2">
                                <div
                                    class="flex items-center gap-2 border border-white border-opacity-30 rounded-full overflow-hidden">
                                    <label for="start_date" class="px-4">From</label>
                                    <input type="date" id="start_date" class="border-0 bg-inherit focus:shadow-none"
                                        name="start_date" value="{{ $start_date }}" />
                                </div>
                                <div
                                    class="flex items-center gap-2 border border-white border-opacity-30 rounded-full overflow-hidden">
                                    <label for="to_date" class="px-4">To</label>
                                    <input type="date" id="to_date" class="border-0 bg-inherit focus:shadow-none"
                                        name="to_date" value="{{ $end_date }}" />
                                </div>
                                <x-bladewind.button size="small" type="primary" class="h-11 !w-11 !p-0"
                                    can_submit="true">
                                    <i class='fa fa-search text-sm text-white' aria-hidden='true'></i>
                                </x-bladewind.button>
                                <a href="{{ route('voucher.index') }}">
                                    <x-bladewind.button class="h-11 !w-11 !p-0" size="tiny" color="red"
                                        tooltip="Delete">
                                        <i class="fa fa-ban text-sm" aria-hidden="true"></i>
                                    </x-bladewind.button>
                                </a>
                            </div>
                        </form>
                        <form method="post" action="{{ route('voucher.search') }}" class="flex items-start">
                            @csrf
                            <x-bladewind.input name="search" value="{{ $form ?? '' }}" placeholder="Search"
                                prefix="<button><i class='fa fa-search text-white text-opacity-50 pr-1' aria-hidden='true'></i></button>"
                                class="w-[300px] rounded-full" />
                        </form>
                    </div>
                    <x-bladewind.table divider="thin" compact="true" class_search="w-1/3">
                        <x-slot name="header" class="text-center">
                            <th class="!text-center">username</th>
                            <th class="!text-center">platform</th>
                            <th class="!text-left"><span class="pl-16">voucher code</span></th>
                            <th class="!text-center">group probability</th>
                            <th class="!text-center">status</th>
                            <th class="!text-center">issued by</th>
                            <th class="!text-center">issued at</th>
                            <th class="!text-center">action</th>
                        </x-slot>
                        @foreach ($voucher as $data)
                            <tr class="text-center">
                                <td>{{ $data->username }}</td>
                                <td>{{ $data->Platform ? $data->Platform->name : 'Unknown' }}</td>
                                <td>
                                    <div class="flex items-center justify-left w-[240px]">
                                        <x-bladewind.button class="mr-1 w-8 h-8 !p-0" size="tiny" tooltip="copy"
                                            onclick="copyToClipboard('{{ $data->code_voucher }}')">
                                            <i class="fa fa-clone" aria-hidden="true"></i>
                                        </x-bladewind.button>
                                        <x-bladewind::button class="mr-2 w-8 h-8 !p-0" size="tiny" type="secondary"
                                            border_width="2" onclick="toggleVisibility({{ $data->id }})">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </x-bladewind::button>
                                        <span class="block"
                                            id="hiddenCodeVoucher_{{ $data->id }}">**********</span>
                                        <span class="hidden"
                                            id="codeVoucher_{{ $data->id }}">{{ $data->code_voucher }}</span>
                                    </div>
                                </td>
                                <td>
                                    <x-bladewind.tag
                                        label="{{ $data->set_prob === 'prob_one' ? 'Group 1' : 'Group 2' }}"
                                        shade="dark" color="blue" class="!mb-0" />
                                </td>
                                <td>
                                    @if ($data->is_available === 'used')
                                        <x-bladewind.tag label="Voucher used" shade="dark" color="green"
                                            class="!mb-0" />
                                    @elseif ($data->is_available === 'available')
                                        <x-bladewind.tag label="Voucher waiting" shade="dark" color="yellow"
                                            class="!mb-0" />
                                    @else
                                        <x-bladewind.tag label="Expire" shade="dark" color="red" class="!mb-0" />
                                    @endif
                                </td>
                                <td>{{ $data->issuedBy->name }}</td>
                                <td>{{ $data->created_at ? $data->created_at->timezone('Asia/Jakarta') : '' }}</td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <x-bladewind.button class="w-8 h-8 !p-0" size="tiny" tooltip="Edit"
                                            onclick="editVoucher({{ $data }})">
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </x-bladewind.button>
                                        @if ($data->is_available)
                                            <x-bladewind.button class="w-8 h-8 !p-0" size="tiny" color="red"
                                                tooltip="Delete" onclick="deleteVoucher({{ $data->id }})">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </x-bladewind.button>
                                        @endif
                                        @if ($data->updatedBy)
                                            <x-bladewind.button class="w-8 h-8 !p-0" size="tiny" type="secondary"
                                                onclick="infoUpdate('{{ $data->updatedBy->name }}', '{{ $data->updated_at ? $data->updated_at->timezone('Asia/Jakarta') : '' }}')">
                                                <i class="fa fa-info" aria-hidden="true"></i>
                                            </x-bladewind.button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-bladewind.table>
                    @if (!$form)
                        @if (!$bydatemode)
                            <div class="mt-4">
                                {{ $voucher->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-collapse collapsed="false">
        <x-slot name="title">
            Platform Setting
        </x-slot>
        <div class="block">
            <x-bladewind.button size="small" type="primary" onclick="storePlatform()" class="h-11">
                Add new platform
            </x-bladewind.button>
            <x-bladewind.table divider="thin" compact="true" searchable="true" search_placeholder="Find data..."
                class_search="w-1/3">
                <x-slot name="header" class="text-center">
                    <th class="!text-center">id</th>
                    <th class="!text-center">name</th>
                    <th class="!text-center">logo</th>
                    <th class="!text-center">status</th>
                    <th class="!text-center">action</th>
                </x-slot>
                @foreach ($platform as $data)
                    <tr class="text-center">
                        <td>{{ $data->id }}</td>
                        <td>{{ $data->name }}</td>
                        <td class="flex items-center justify-center">
                            <img src="{{ $data->logo ? asset('/storage/' . $data->logo) : '' }}" class="h-16"
                                alt="Example Image">
                        </td>
                        <td>
                            @if ($data->is_active)
                                <x-bladewind.tag label="Active" shade="dark" color="green" class="!mb-0" />
                            @else
                                <x-bladewind.tag label="Not-Active" shade="dark" color="yellow" class="!mb-0" />
                            @endif
                        </td>
                        <td>
                            <div class="flex justify-center gap-2">
                                <x-bladewind.button.circle class="w-8 h-8 !p-0" size="tiny" icon="pencil"
                                    tooltip="Edit" onclick="editPlatform({{ $data }})" />
                                <x-bladewind.button class="w-8 h-8 !p-0" size="tiny" color="red"
                                    tooltip="Delete" onclick="deletePlatform({{ $data->id }})">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </x-bladewind.button>
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
    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
    var day = currentDate.getDate().toString().padStart(2, '0');

    var dateString = year + '-' + month + '-' + day;

    @if ($start_date === null)
        $('#start_date').val(dateString);
    @endif
    @if ($end_date === null)
        $('#to_date').val(dateString);
    @endif

    if ($('#timeout').length) {
        setTimeout(function() {
            $('#timeout').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

    function toggleVisibility(voucherId) {
        var codeVoucher = document.getElementById('codeVoucher_' + voucherId);
        var hiddenCodeVoucher = document.getElementById('hiddenCodeVoucher_' + voucherId);

        if (codeVoucher.classList.contains('hidden')) {
            codeVoucher.classList.remove('hidden');
            hiddenCodeVoucher.classList.add('hidden');
        } else {
            codeVoucher.classList.add('hidden');
            hiddenCodeVoucher.classList.remove('hidden');
        }
    }

    function copyToClipboard(value) {
        navigator.clipboard.writeText(value)
    }

    let dataIdToDelete;

    function deleteVoucher(dataId) {
        dataIdToDelete = dataId;
        showModal('delete-user');
    }

    function deletePlatform(dataId) {
        dataIdToDelete = dataId;
        showModal('delete-platform');
    }

    function storeVoucher() {
        // Set the data to the form fields
        $('input[name="username"]').val('');
        $('input[name="code_voucher"]').val('');

        // Show the modal
        showModal('data-store-modal');
    }

    function storePlatform() {
        // Set the data to the form fields
        $('input[name="name"]').val('');
        $('input[name="logo"]').val('');
        $('select[name="is_active"]').val(false);

        // Show the modal
        showModal('data-store-platform-modal');
    }

    function editVoucher(data) {
        // Set the data to the form fields
        $('input[name="id"]').val(data.id);
        $('input[name="username"]').val(data.username);
        $('select[name="set_prob"]').val(data.set_prob);
        $('select[name="platform_id"]').val(data.platform_id);
        $('input[name="code_voucher"]').val(data.code_voucher);
        // Show the modal
        showModal('data-update-modal');
    }

    function editPlatform(data) {
        // Set the data to the form fields
        $('input[name="id"]').val(data.id);
        $('input[name="name"]').val(data.name);
        $('select[name="is_active"]').val(data.is_active);

        // Show the modal
        showModal('data-update-platform-modal');
    }

    function infoUpdate(admin, updateTime) {
        // Set the data to the form fields
        $('#admin-name').text(admin);
        $('#date-update').text(updateTime);

        // Show the modal
        showModal('info-update');
    }

    function confirmDelete() {
        fetch(`{{ url('/system/voucher/') }}/${dataIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                window.location.reload();
            })
            .catch(error => console.error('Error:', error));
    }

    function confirmDeletePlatform() {
        fetch(`{{ url('/system/voucher/platform') }}/${dataIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                window.location.reload();
            })
            .catch(error => console.error('Error:', error));
    }

    function getRandomCode() {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let code = '';

        for (let i = 0; i < 18; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            code += characters[randomIndex];
        }

        $('input[name="code_voucher"]').val(code);
    }

    function storeData() {
        document.getElementById('data-store-form').submit();
    }

    function storeDataPlatform() {
        document.getElementById('data-store-platform').submit();
    }

    function updateData() {
        document.getElementById('data-update-form').submit();
    }

    function updateDataPlatform() {
        document.getElementById('data-update-platform').submit();
    }
</script>

<x-bladewind.modal size="big" center_action_buttons="true" type="warning" title="Confirm Deletion"
    ok_button_action="confirmDelete()" close_after_action="false" name="delete-user" ok_button_label="Yes, delete"
    cancel_button_label="don't delete">
    Are you sure you want to delete this data? This action cannot be undone.
</x-bladewind.modal>

<x-bladewind.modal size="big" center_action_buttons="true" type="warning" title="Confirm Deletion"
    ok_button_action="confirmDeletePlatform()" close_after_action="false" name="delete-platform"
    ok_button_label="Yes, delete" cancel_button_label="don't delete">
    Are you sure you want to delete this data? This action cannot be undone.
</x-bladewind.modal>

<x-bladewind.modal size="small" title="Last update by" name="info-update" center_action_buttons="true"
    ok_button_label="Okey" cancel_button_label="">
    <div class="flex flex-col">
        <div class="inline-block">
            <span id="admin-name"></span>
            <span>at</span>
        </div>
        <span id="date-update"></span>
    </div>
</x-bladewind.modal>

<x-bladewind::modal backdrop_can_close="false" name="data-store-modal" center_action_buttons="true"
    ok_button_action="storeData()" ok_button_label="Save Voucher" close_after_action="true">
    <b>Generate Voucher</b>
    <div class="flex flex-col items-center">
        <form method="post" action="{{ route('voucher.store') }}" id="data-store-form"
            class="data-store-form-ajax w-full">
            @csrf
            <div class="mt-4">
                <x-bladewind.input required="true" name="username" placeholder="Username"
                    error_message="Please enter Username" value="" />
            </div>
            <div class="mt-4">
                <select name="set_prob" id="set_prob" class="w-full bg-inherit">
                    <option value="prob_one">Group 1</option>
                    <option value="prob_two">Group 2</option>
                </select>
            </div>
            <div class="mt-4">
                <select name="platform_id" id="platform_id" class="w-full bg-inherit">
                    @foreach ($platform as $select)
                        <option value="{{ $select->id }}">{{ $select->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-4">
                <x-bladewind.input required="true" name="code_voucher" placeholder="Voucher Code"
                    error_message="Please click generate code" value="" />
            </div>
        </form>
        <x-bladewind.button size="small" type="primary" onclick="getRandomCode()">
            Generate Voucher Code
        </x-bladewind.button>
    </div>
</x-bladewind::modal>

<x-bladewind::modal backdrop_can_close="false" name="data-update-modal" center_action_buttons="true"
    ok_button_action="updateData()" ok_button_label="Update" close_after_action="true">
    <b>Generate Voucher</b>
    <div class="flex flex-col items-center">
        <form method="post" action="{{ route('voucher.update') }}" id="data-update-form"
            class="data-update-form-ajax w-full">
            @csrf
            <x-bladewind.input name="id" class="hidden" />
            <div class="mt-4">
                <x-bladewind.input required="true" name="username" placeholder="Username"
                    error_message="Please enter Username" value="" />
            </div>
            <div class="mt-4">
                <select name="set_prob" id="set_prob" class="w-full bg-inherit">
                    <option value="prob_one">Group 1</option>
                    <option value="prob_two">Group 2</option>
                </select>
            </div>
            <div class="mt-4">
                <select name="platform_id" id="platform_id" class="w-full bg-inherit">
                    @foreach ($platform as $select)
                        <option value="{{ $select->id }}">{{ $select->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-4">
                <x-bladewind.input required="true" name="code_voucher" placeholder="Voucher Code"
                    error_message="Please click generate code" value="" />
            </div>
        </form>
        <x-bladewind.button size="small" type="primary" onclick="getRandomCode()">
            Generate Voucher Code
        </x-bladewind.button>
    </div>
</x-bladewind::modal>


<x-bladewind::modal backdrop_can_close="false" name="data-store-platform-modal" center_action_buttons="true"
    ok_button_action="storeDataPlatform()" ok_button_label="Save Voucher" close_after_action="true">
    <b>Register New Platform</b>
    <div class="flex flex-col items-center">
        <form method="post" action="{{ route('voucher.store.platform') }}" id="data-store-platform"
            enctype="multipart/form-data" class="data-store-form-ajax w-full">
            @csrf
            <div class="mt-4">
                <x-bladewind.input required="true" name="name" placeholder="Name of platform"
                    error_message="Please enter name of platform" value="" />
            </div>
            <div class="block">
                <x-bladewind.filepicker name="logo" placeholder="Logo Platform" selected_value_class="h-40"
                    accepted_file_types=".png, .ico, .webp" url="" />
                {{-- url="{{ $setting_favicon->value ? asset('/storage/' . $setting_favicon->value) : '' }}" /> --}}
            </div>
            <div class="mt-2">
                <label for="is_active" class="text-sm">Status</label>
                <select name="is_active" id="is_active" class="w-full bg-inherit">
                    <option value="{{ true }}">Active</option>
                    <option value="{{ false }}">Not-Active</option>
                </select>
            </div>
        </form>
    </div>
</x-bladewind::modal>

<x-bladewind::modal backdrop_can_close="false" name="data-update-platform-modal" center_action_buttons="true"
    ok_button_action="updateDataPlatform()" ok_button_label="Save Voucher" close_after_action="true">
    <b>Edit Platform</b>
    <div class="flex flex-col items-center">
        <form method="post" action="{{ route('voucher.update.platform') }}" id="data-update-platform"
            class="data-update-form-ajax w-full">
            @csrf
            <x-bladewind.input name="id" class="hidden" />
            <div class="mt-4">
                <x-bladewind.input required="true" name="name" placeholder="Name of platform"
                    error_message="Please enter name of platform" value="" />
            </div>
            <div class="mt-2">
                <label for="is_active" class="text-sm">Status</label>
                <select name="is_active" id="is_active" class="w-full bg-inherit">
                    <option value="{{ true }}">Active</option>
                    <option value="{{ false }}">Not-Active</option>
                </select>
            </div>
        </form>
    </div>
</x-bladewind::modal>
