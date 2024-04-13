<x-landing-layout>
    <div
        class="min-h-screen w-screen md:w-auto overflow-hidden items-center text-black dark:text-white justify-around flex flex-col md:grid md:grid-cols-2">
        <div class="relative w-full h-fit scale-wheel top-48 md:top-0">
            <img src="{{ asset('/assets/img/frame.webp') }}" alt="frame"
                class="absolute right-0 left-0 top-0 bottom-0 m-auto z-50">

            {{-- this wheel must be rotate with items prize --}}
            <div id="wheel-rotate" class="w-full h-full absolute right-0 left-0 top-1 bottom-0 m-auto -rotate-[108deg]">
                <img src="{{ asset('/assets/img/wheel.webp') }}" alt="wheel"
                    class="absolute right-0 left-0 top-0 bottom-0 m-auto wheel-size">
                <div class="absolute right-0 left-0 top-0 bottom-0 m-auto">
                    @foreach ($list_prize as $data_prize)
                        <div class="prize-wheel-slice text-lg right-0 mx-auto break-keep"
                            style="transform: rotate({{ $data_prize->id * 36 }}deg)">{{ $data_prize->title }}</div>
                    @endforeach
                </div>
            </div>
        </div>
        <div
            class="relative form-voucher w-[300px] md:w-[400px] mx-auto text-center border bg-black bg-opacity-50 mt-10 md:mt-0 border-yellow-400 py-4 px-6 md:p-8 rounded-xl">
            <button onclick="showModal('guide-modal')"
                class="rounded-full bg-black border-2 border-yellow-400 text-yellow-400 w-8 h-8 absolute -top-8 right-2 animate-bounce">
                <i class="fa fa-info" aria-hidden="true"></i>
            </button>

            <span class="uppercase text-lg text-white">Masukan kode kupon</span>

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

<audio id="spinningSound" src="{{ asset('/assets/sound/spinning_sound.mp3?v=1') }}"></audio>
{{-- <audio id="audioPlayer" autoplay class="absolute z-10">
    <source src="{{ asset('/assets/sound/backsound.mp3?v=1') }}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio> --}}

@if (session('success'))
    <div id="timeout" class="fixed top-4 right-4 w-[350px] z-[9999]">
        <x-bladewind.alert type="success">
            {{ session('success') }}
        </x-bladewind.alert>
    </div>
@endif
@if (session('error'))
    <div id="timeout" class="fixed top-4 right-4 w-[350px] z-[9999]">
        <x-bladewind.alert type="error">
            {{ session('error') }}
        </x-bladewind.alert>
    </div>
@endif
@if (session('warning'))
    <div id="timeout" class="fixed top-4 right-4 w-[350px] z-[9999]">
        <x-bladewind.alert type="warning" class="!bg-yellow-400">
            {{ session('warning') }}
        </x-bladewind.alert>
    </div>
@endif

<div id="error-ajax" class="fixed hidden top-8 right-4 w-[350px] z-[9999]">
    <x-bladewind.alert type="error">
        <span id="error-message"></span>
    </x-bladewind.alert>
</div>

<div id="warning-ajax" class="fixed hidden top-8 right-4 w-[350px] z-[9999]">
    <x-bladewind.alert type="warning">
        <span id="warning-message"></span>
    </x-bladewind.alert>
</div>

<x-bladewind.modal size="medium" name="wellcome-modal" center_action_buttons="true" cancel_button_label="">
    <div class="block">
        {!! $welcome->value !!}
    </div>
</x-bladewind.modal>

<x-bladewind.modal size="medium" name="guide-modal" center_action_buttons="true" cancel_button_label="">
    <div class="block">
        {!! $guide->value !!}
    </div>
</x-bladewind.modal>

<x-bladewind.modal size="medium" name="result-modal" backdrop_can_close="false" center_action_buttons="true" ok_button_action="resetSpinButton()"
    cancel_button_label="">
    <div class="text-center">
        {!! $text_win->value !!}
    </div>
</x-bladewind.modal>

<x-bladewind.modal size="medium" name="zonk-modal" backdrop_can_close="false" center_action_buttons="true" ok_button_action="resetSpinButton()"
    cancel_button_label="">
    <div class="text-center">
        {!! $text_lose->value !!}
    </div>
</x-bladewind.modal>


<script>
    function resetSpinButton() {
        $('#submitBtn').prop('disabled', false);
    }

    $(document).ready(function() {
        showModal('wellcome-modal');
    });

    if ($('#timeout').length) {
        setTimeout(function() {
            $('#timeout').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const audioPlayer = new Audio('{{ asset('/assets/sound/backsound.mp3?v=1') }}');
        audioPlayer.play();

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
                            $('#voucher-code').val('')
                            $('#submitBtn').prop('disabled', true);
                            audioPlayer.volume = 0.5;
                            $('#spinningSound')[0].currentTime = 0;
                            $('#spinningSound')[0].play();
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
                                duration: 11200,
                                easing: $.easing.easeInOutQuart,
                                callback: function() {
                                    $('#reward-platform').text(data.data.platform)
                                    $('#lose-platform').text(data.data.platform)
                                    $('#reward-username').text(data.data.username)
                                    $('#lose-username').text(data.data.username)
                                    $('#reward-value').text(data.data.title)
                                    $('#voucher-code').val('')
                                    $('#voucher').val('')
                                    audioPlayer.volume = 1;
                                    if (data.data.value !== 0) {
                                        showModal('result-modal')
                                    } else {
                                        showModal('zonk-modal')
                                    }
                                    $('#spinningSound')[0].stop();
                                }
                            });
                        }
                    })
                    .catch(error => {
                        $('#error-message').text(data.message);
                    });
            } else {
                $('#warning-ajax').removeClass('hidden');
                if ($('#warning-ajax').length) {
                    setTimeout(function() {
                        $('#warning-ajax').addClass('hidden');
                    }, 5000);
                }
                $('#warning-message').text('Mohon isi kode kupon anda!');
            }
        });
    });
</script>
