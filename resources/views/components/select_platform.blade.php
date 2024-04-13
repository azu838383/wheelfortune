<x-landing-layout>
    <div
        class="min-h-screen w-screen md:w-auto overflow-hidden items-center text-black dark:text-white justify-around flex flex-col md:grid md:grid-cols-2">
        <div class="relative w-full h-fit scale-wheel top-48 md:top-0">
            <img src="{{ asset('/assets/img/frame.png') }}" alt="frame"
                class="absolute right-0 left-0 top-0 bottom-0 m-auto">

            {{-- this wheel must be rotate with items prize --}}
            <div id="wheel-rotate" class="w-full h-full absolute right-0 left-0 top-0 bottom-0 m-auto -rotate-[108deg]">
                <img src="{{ asset('/assets/img/wheel.png') }}" alt="wheel"
                    class="absolute right-0 left-0 top-0 bottom-0 m-auto wheel-size">
                <div class="absolute right-0 left-0 top-0 bottom-0 m-auto">
                    @foreach ($list_prize as $data_prize)
                        <div class="prize-wheel-slice text-lg right-[70px] mx-auto break-keep"
                            style="transform: rotate({{ $data_prize->id * 36 }}deg)">{{ $data_prize->title }}</div>
                    @endforeach
                </div>
            </div>

            {{-- arrow stay on here --}}
            <img src="{{ asset('/assets/img/arrow.png') }}" alt="arrow"
                class="absolute right-0 left-0 top-0 bottom-0 m-auto">
        </div>
        <div
            class="relative form-voucher w-[300px] md:w-[400px] mx-auto text-center border bg-black bg-opacity-50 mt-10 md:mt-0 border-yellow-400 py-4 px-6 md:p-8 rounded-xl">
            <span class="uppercase text-lg">Masukan kode kupon</span>
            <form id="verifyForm" class="signup-form w-full mt-2">
                @csrf
                <x-bladewind.input name="voucher" id="voucher-code" class="w-full rounded-full bg-white text-black"
                    placeholder="Kode kupon" />
                <button id="submitBtn" size="small" can_submit="true"
                    class="py-2 mx-auto block w-full rounded-full bg-gradient-to-r from-yellow-600 to-yellow-300 text-black font-bold text-lg">
                    <span>SPIN</span>
                </button>
            </form>

        </div>
    </div>
</x-landing-layout>

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
@if (session('warning'))
    <div id="timeout" class="fixed top-4 right-4 w-[350px]">
        <x-bladewind.alert type="warning" class="!bg-yellow-400">
            {{ session('warning') }}
        </x-bladewind.alert>
    </div>
@endif

<div id="error-ajax" class="fixed hidden top-4 right-4 w-[350px]">
    <x-bladewind.alert type="error">
        <span id="error-message"></span>
    </x-bladewind.alert>
</div>

<div id="warning-ajax" class="fixed hidden top-4 right-4 w-[350px]">
    <x-bladewind.alert type="warning">
        <span id="warning-message"></span>
    </x-bladewind.alert>
</div>

<x-bladewind.modal size="small" title="Pilih situs yang anda mainkan" name="select-platform-modal"
    ok_button_action="spinReward()" ok_button_label="Yes" close_after_action="true">
    <form id="spinForm" class="signup-form w-full mt-2">
        @csrf
        <x-bladewind.input name="voucher" id="voucher" class="hidden" />
        <div class="flex flex-col gap-4">
            @foreach ($platform as $list_select)
                <div class="flex items-center border px-4 py-2 rounded-lg">
                    <x-bladewind.radio-button value="{{ $list_select->id }}" name="platform_id" />
                    <img src="{{ $list_select->logo ? asset('/storage/' . $list_select->logo) : '' }}" class="h-14"
                        alt="Example Image">
                </div>
            @endforeach
        </div>
    </form>
</x-bladewind.modal>

<x-bladewind.modal size="small" title="Selamat Anda Menang" name="reward-modal">
    {{-- Place an image here to beautify this modal --}}
    <div>"Wow! Selamat <span id="text-reward-username"></span>! Anda mendapatkan Hadiah <span
            id="text-reward-value"></span>! Kami Akan Proses Hadiah Anda dalam 1 Hari Kerja</div>
</x-bladewind.modal>

<script>
    if ($('#timeout').length) {
        setTimeout(function() {
            $('#timeout').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('verifyForm');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(form);
            if ($('#voucher-code').val() !== '') {
                fetch('{{ route('landing.verify') }}', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'error') {
                            $('#voucher-code').val('')
                            $('#error-ajax').removeClass('hidden');
                            if ($('#error-ajax').length) {
                                setTimeout(function() {
                                    $('#error-ajax').addClass('hidden');
                                }, 5000);
                            }
                            $('#error-message').text(data.message);
                        } else if (data.status === 'warning') {
                            $('#voucher-code').val('')
                            $('#warning-ajax').removeClass('hidden');
                            if ($('#warning-ajax').length) {
                                setTimeout(function() {
                                    $('#warning-ajax').addClass('hidden');
                                }, 5000);
                            }
                            $('#warning-message').text(data.message);
                        } else {
                            $('#voucher').val($('#voucher-code').val())
                            showModal('select-platform-modal');
                        }
                    })
                    .catch(error => {
                        $('#error-message').text(data.message);
                    });
            }
        });
    });

    function spinReward() {
        const formSpin = document.getElementById('spinForm');
        const formData = new FormData(formSpin);
        if ($('input[name="platform_id"]').val().length > 0) {

            fetch('{{ route('landing.spin') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'error') {
                        $('#voucher-code').val('')
                        $('#voucher').val('')
                        $('#error-ajax').removeClass('hidden');
                        if ($('#error-ajax').length) {
                            setTimeout(function() {
                                $('#error-ajax').addClass('hidden');
                            }, 5000);
                        }
                        $('#error-message').text(data.message);
                    } else if (data.status === 'warning') {
                        $('#voucher-code').val('')
                        $('#warning-ajax').removeClass('hidden');
                        if ($('#warning-ajax').length) {
                            setTimeout(function() {
                                $('#warning-ajax').addClass('hidden');
                            }, 5000);
                        }
                        $('#warning-message').text(data.message);
                    } else {
                        var piePosition = [{
                                id: 10,
                                position: -90
                            },
                            {
                                id: 9,
                                position: -54
                            },
                            {
                                id: 8,
                                position: -18
                            },
                            {
                                id: 7,
                                position: 18
                            },
                            {
                                id: 6,
                                position: 54
                            },
                            {
                                id: 5,
                                position: 90
                            },
                            {
                                id: 4,
                                position: 126
                            },
                            {
                                id: 3,
                                position: 162
                            },
                            {
                                id: 2,
                                position: 198
                            },
                            {
                                id: 1,
                                position: 234
                            },
                        ]
                        var randomAngle = piePosition.find((f) => f.id === data.data.id)
                            .position;
                        $('#wheel-rotate').rotate({
                            angle: 0,
                            animateTo: randomAngle +
                                3600,
                            duration: 5000,
                            easing: $.easing.easeInOutQuart,
                            callback: function() {
                                showModal('reward-modal');
                                $('#text-reward-username').text(data.data.username)
                                $('#text-reward-value').text(data.data.title)
                                $('#voucher-code').val('')
                                $('#voucher').val('')
                            }
                        });
                    }
                })
                .catch(error => {
                    $('#error-message').text(data.message);
                });
        }
    };
</script>
