<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="!text-lg pr-3 whitespace-nowrap text-white mb-2">Player Rewards</h3>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between mb-4">
                        <div class="w-[200px]"></div>

                        <form method="post" action="{{ route('reward.search.bydate') }}" class="flex items-start">
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
                                <a href="{{ route('reward.index') }}">
                                    <x-bladewind.button class="h-11 !w-11 !p-0" size="tiny" color="red"
                                        tooltip="Delete">
                                        <i class="fa fa-ban text-sm" aria-hidden="true"></i>
                                    </x-bladewind.button>
                                </a>
                            </div>
                        </form>

                        <form method="post" action="{{ route('reward.search') }}" class="flex items-start">
                            @csrf
                            <x-bladewind.input name="search" value="{{ $form ?? '' }}" placeholder="Search"
                                prefix="<i class='fa fa-search text-white text-opacity-50 pr-1' aria-hidden='true'></i>"
                                class="w-[300px] rounded-full" />
                            {{-- <x-bladewind.button can_submit="true" size="small" type="primary" class="h-10 w-12 !p-0">
                                <i class='fa fa-search' aria-hidden='true'></i>
                            </x-bladewind.button> --}}
                        </form>
                    </div>
                    <x-bladewind.table divider="thin" compact="true" class_search="w-1/3">
                        <x-slot name="header" class="text-center">
                            <th class="!text-center">username</th>
                            <th class="!text-center">platform</th>
                            {{-- <th class="!text-center">prize id</th> --}}
                            <th class="!text-center">prize</th>
                            <th class="!text-center">value</th>
                            <th class="!text-center">voucher</th>
                            <th class="!text-center">delivery status</th>
                            <th class="!text-center">data time</th>
                            <th class="!text-center">proced</th>
                        </x-slot>
                        @foreach ($rewards as $data)
                            <tr class="text-center">
                                <td>{{ $data->userVoucher ? $data->userVoucher->username : 'Unknown' }}</td>
                                <td>{{ $data->Platform ? $data->Platform->name : '' }}</td>
                                {{-- <td>{{ $data->realPrize->title }}</td> --}}
                                <td>{{ $data->prize_title }}</td>
                                <td>Rp.{{ number_format($data->prize_value) }}</td>
                                <td>{{ $data->userVoucher ? $data->userVoucher->code_voucher : 'Unknown' }}</td>
                                <td>
                                    @if ($data->prize_value === 0)
                                        <div class="flex flex-col justify-center items-center">
                                            <x-bladewind.tag label="no prize" shade="dark" color="blue"
                                                class="w-fit !mb-0" />
                                        </div>
                                    @elseif ($data->delivery_status)
                                        <div class="flex flex-col justify-center items-center">
                                            <x-bladewind.tag label="delivered" shade="dark" color="green"
                                                class="w-fit !mb-0" />
                                            {{-- <span>{{ $data->updated_at ? $data->updated_at->timezone('Asia/Jakarta') : '' }}</span> --}}
                                        </div>
                                    @else
                                        <x-bladewind.tag label="pending" shade="dark" color="yellow" class="!mb-0" />
                                    @endif
                                </td>
                                <td class="!w-[125px]">
                                    <span>
                                        {{ $data->created_at ? $data->created_at->timezone('Asia/Jakarta') : '' }}</td>
                                    </span>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        @if ($data->prize_value !== 0)
                                            @if ($data->delivery_status)
                                                <x-bladewind.button class="w-8 h-8 !p-0" size="tiny" type="secondary"
                                                    onclick="infoDelivery('{{ $data->updatedBy->name }}', '{{ $data->updated_at ? $data->updated_at->timezone('Asia/Jakarta') : '' }}')">
                                                    <i class="fa fa-info" aria-hidden="true"></i>
                                                </x-bladewind.button>
                                                @if (Auth::user()->level === 10)
                                                    <x-bladewind.button class="w-8 h-8 !p-0" size="tiny"
                                                        color="red" tooltip="Delete"
                                                        onclick="cancelDelivery({{ $data->id }})">
                                                        <i class="fa fa-ban" aria-hidden="true"></i>
                                                    </x-bladewind.button>
                                                @else
                                                    @if ($data->count_changes < 3)
                                                        <x-bladewind.button class="w-8 h-8 !p-0" size="tiny"
                                                            color="red" tooltip="Delete"
                                                            onclick="cancelDelivery({{ $data->id }})">
                                                            <i class="fa fa-ban" aria-hidden="true"></i>
                                                        </x-bladewind.button>
                                                    @endif
                                                @endif
                                            @else
                                                <x-bladewind.button class="w-8 h-8 !p-0" size="tiny" tooltip="Edit"
                                                    onclick="deliverPrize({{ $data->id }})">
                                                    <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                </x-bladewind.button>
                                            @endif
                                        @else
                                            <x-bladewind.button class="w-8 h-8 !p-0" size="tiny" type="secondary"
                                                onclick="showModal('no-prize')">
                                                <i class="fa fa-info" aria-hidden="true"></i>
                                            </x-bladewind.button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-bladewind.table>
                    @if (!$form)
                        <div class="mt-4">
                            {{ $rewards->links() }}
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

    if ($('#timeout').length) {
        setTimeout(function() {
            $('#timeout').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

    function deliverPrize(data) {
        $('input[name="id"]').val(data);
        showModal('delivery-modal');
    }

    function submitDelivery() {
        document.getElementById('delivery-form').submit();
    }

    function cancelDelivery(data) {
        $('input[name="id"]').val(data);
        showModal('cancel_delivery-modal');
    }

    function submitCancelDelivery() {
        document.getElementById('delivery-cancel-form').submit();
    }

    function infoDelivery(admin, updateAt) {
        $('#d-user').text(admin);
        $('#d-time').text(updateAt);
        showModal('info-delivery-modal');
    }
</script>

<x-bladewind.modal size="small" title="Delivery Reward" center_action_buttons="true" name="delivery-modal"
    ok_button_action="submitDelivery()" ok_button_label="Yes" close_after_action="true">
    <form method="post" action="{{ route('reward.delivery') }}" id="delivery-form">
        @csrf
        <x-bladewind.input name="id" class="hidden" />
    </form>
    Are you sure want to delivery this reward?
</x-bladewind.modal>

<x-bladewind.modal size="small" title="Cancel Delivery Reward" center_action_buttons="true"
    name="cancel_delivery-modal" ok_button_action="submitCancelDelivery()" ok_button_label="Yes"
    close_after_action="true">
    <form method="post" action="{{ route('cancel.delivery') }}" id="delivery-cancel-form">
        @csrf
        <x-bladewind.input name="id" class="hidden" />
    </form>
    You want to cancel delivery status for this reward?
</x-bladewind.modal>

<x-bladewind.modal size="small" title="Last update by" name="info-delivery-modal" center_action_buttons="true" ok_button_label="Okey" cancel_button_label="">
    <div class="flex flex-col">
        <div class="inline-block">
            <span id="d-user"></span>
            <span>at</span>
        </div>
        <span id="d-time"></span>
    </div>
</x-bladewind.modal>

<x-bladewind.modal size="small" title="Zonk Reward" name="no-prize" center_action_buttons="true" ok_button_label="Okey" cancel_button_label="">
    <span>There are no reward to send</span>
</x-bladewind.modal>