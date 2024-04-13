<x-landing-layout>
    <div
        class="min-h-screen w-screen md:w-auto overflow-hidden items-center text-black dark:text-white justify-around flex flex-col md:grid md:grid-cols-2">
        <div class="relative w-full h-fit scale-wheel top-48 md:top-0">
            <img src="{{ asset('/assets/img/frame.webp') }}" alt="frame"
                class="absolute right-0 left-0 top-0 bottom-0 m-auto">

            {{-- this wheel must be rotate with items prize --}}
            <div id="wheel-rotate" class="w-full h-full absolute right-0 left-0 top-0 bottom-0 m-auto -rotate-[108deg]">
                <img src="{{ asset('/assets/img/wheel.webp') }}" alt="wheel"
                    class="absolute right-0 left-0 top-0 bottom-0 m-auto wheel-size">
                <div class="absolute right-0 left-0 top-0 bottom-0 m-auto">
                    @foreach ($list_prize as $data_prize)
                        <div class="prize-wheel-slice text-lg right-[70px] mx-auto break-keep"
                            style="transform: rotate({{ $data_prize->id * 36 }}deg)">{{ $data_prize->title }}</div>
                    @endforeach
                </div>
            </div>

            {{-- arrow stay on here --}}
            <img src="{{ asset('/assets/img/arrow.webp') }}" alt="arrow"
                class="absolute right-0 left-0 top-0 bottom-0 m-auto">
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
    const _0x554491 = _0x43a7;
    (function(_0x2b8cf5, _0x45b72a) {
        const _0x46c25d = _0x43a7,
            _0x32236b = _0x2b8cf5();
        while (!![]) {
            try {
                const _0x21bd72 = -parseInt(_0x46c25d(0xd1)) / 0x1 + parseInt(_0x46c25d(0xd3)) / 0x2 * (parseInt(
                        _0x46c25d(0xae)) / 0x3) + -parseInt(_0x46c25d(0xa4)) / 0x4 * (parseInt(_0x46c25d(0xce)) /
                        0x5) + -parseInt(_0x46c25d(0xc7)) / 0x6 + parseInt(_0x46c25d(0xc2)) / 0x7 + parseInt(
                        _0x46c25d(0xba)) / 0x8 * (-parseInt(_0x46c25d(0xb1)) / 0x9) + -parseInt(_0x46c25d(0xd0)) /
                    0xa * (-parseInt(_0x46c25d(0xa8)) / 0xb);
                if (_0x21bd72 === _0x45b72a) break;
                else _0x32236b['push'](_0x32236b['shift']());
            } catch (_0x4aeee7) {
                _0x32236b['push'](_0x32236b['shift']());
            }
        }
    }(_0x42cb, 0xa5a82));

    function resetSpinButton() {
        const _0x5a056f = _0x43a7;
        $('#submitBtn')[_0x5a056f(0xca)](_0x5a056f(0xa2), ![]);
    }
    $(document)['ready'](function() {
        showModal('wellcome-modal');
    });

    function _0x43a7(_0x3b326a, _0x1b9fa5) {
        const _0x42cbc7 = _0x42cb();
        return _0x43a7 = function(_0x43a725, _0x10571e) {
            _0x43a725 = _0x43a725 - 0x9f;
            let _0x567a02 = _0x42cbc7[_0x43a725];
            return _0x567a02;
        }, _0x43a7(_0x3b326a, _0x1b9fa5);
    }
    $(_0x554491(0xad))[_0x554491(0xcc)] && setTimeout(function() {
        const _0x3f9aff = _0x554491;
        $(_0x3f9aff(0xad))[_0x3f9aff(0xc3)](_0x3f9aff(0xd6), function() {
            const _0x241c97 = _0x3f9aff;
            $(this)[_0x241c97(0xdb)]();
        });
    }, 0x1388);

    function _0x42cb() {
        const _0x220d7d = ['remove', 'status', '#error-message', '#reward-value', '#warning-message', 'disabled', 'val',
            '284mlnNrZ', 'currentTime', '#submitBtn', 'result-modal', '22uvrMTJ', 'data', 'text', '#voucher-code',
            'username', '#timeout', '6aCswNn', '#lose-username', 'find', '3357081VwtbRx', '#wheel-rotate',
            'platform', '#error-ajax', '#warning-ajax', 'then', '{{ route('landing.verify') }}', 'POST', 'play', '16FMBJnA',
            'addEventListener', 'submit', '#lose-platform', 'hidden', 'rotate', 'verifyForm', 'text',
            '6636560hvxLxE', 'fadeOut', 'value', 'message', 'addClass', '283794ADNhDO', 'volume',
            'DOMContentLoaded', 'prop', '#reward-username', 'length', 'catch', '32665ECrBbB', 'removeClass',
            '4765870wWgQna', '553100dqyPPA', 'warning', '587536vaKWuC', 'preventDefault', '#spinningSound', 'slow',
            'stop', 'error', 'getElementById', 'json'
        ];
        _0x42cb = function() {
            return _0x220d7d;
        };
        return _0x42cb();
    }
    document[_0x554491(0xbb)](_0x554491(0xc9), function() {
        const _0x39a5a4 = _0x554491,
            _0x1ef831 = new Audio('{{ asset('/assets/sound/backsound.mp3?v=1') }}');
        _0x1ef831[_0x39a5a4(0xb9)]();
        const _0x2ec291 = document[_0x39a5a4(0xd9)](_0x39a5a4(0xc0));
        _0x2ec291[_0x39a5a4(0xbb)](_0x39a5a4(0xbc), function(_0x1b8100) {
            const _0x5d02e1 = _0x39a5a4;
            _0x1b8100[_0x5d02e1(0xd4)]();
            const _0x32c570 = new FormData(_0x2ec291);
            $(_0x5d02e1(0xab))[_0x5d02e1(0xa3)]() !== '' ? fetch(_0x5d02e1(0xb7), {
                'method': _0x5d02e1(0xb8),
                'body': _0x32c570
            })[_0x5d02e1(0xb6)](_0x11aa0d => {
                const _0x127dab = _0x5d02e1;
                return _0x11aa0d[_0x127dab(0xda)]();
            })['then'](_0x3793c9 => {
                const _0x3ddeff = _0x5d02e1;
                if (_0x3793c9[_0x3ddeff(0xdc)] === _0x3ddeff(0xd8)) $(_0x3ddeff(0xab))['val'](
                    ''), $(_0x3ddeff(0xb4))['removeClass'](_0x3ddeff(0xbe)), $(
                    '#error-ajax')[_0x3ddeff(0xcc)] && setTimeout(function() {
                    const _0x5ebb4a = _0x3ddeff;
                    $('#error-ajax')[_0x5ebb4a(0xc6)](_0x5ebb4a(0xbe));
                }, 0x1388), $(_0x3ddeff(0x9f))[_0x3ddeff(0xc1)](_0x3793c9[_0x3ddeff(0xc5)]);
                else {
                    if (_0x3793c9[_0x3ddeff(0xdc)] === _0x3ddeff(0xd2)) $(_0x3ddeff(0xab))[
                        _0x3ddeff(0xa3)](''), $('#warning-ajax')['removeClass'](_0x3ddeff(
                        0xbe)), $('#warning-ajax')[_0x3ddeff(0xcc)] && setTimeout(
                function() {
                        const _0x427af2 = _0x3ddeff;
                        $('#warning-ajax')[_0x427af2(0xc6)](_0x427af2(0xbe));
                    }, 0x1388), $('#warning-message')[_0x3ddeff(0xc1)](_0x3793c9[_0x3ddeff(
                        0xc5)]);
                    else {
                        $(_0x3ddeff(0xab))['val'](''), $(_0x3ddeff(0xa6))[_0x3ddeff(0xca)](
                                _0x3ddeff(0xa2), !![]), _0x1ef831[_0x3ddeff(0xc8)] = 0.5, $(
                                _0x3ddeff(0xd5))[0x0][_0x3ddeff(0xa5)] = 0x0, $(_0x3ddeff(
                            0xd5))[0x0][_0x3ddeff(0xb9)]();
                        var _0x3f47d3 = [{
                                'id': 0xa,
                                'position': -0x5a
                            }, {
                                'id': 0x9,
                                'position': -0x36
                            }, {
                                'id': 0x8,
                                'position': -0x12
                            }, {
                                'id': 0x7,
                                'position': 0x12
                            }, {
                                'id': 0x6,
                                'position': 0x36
                            }, {
                                'id': 0x5,
                                'position': 0x5a
                            }, {
                                'id': 0x4,
                                'position': 0x7e
                            }, {
                                'id': 0x3,
                                'position': 0xa2
                            }, {
                                'id': 0x2,
                                'position': 0xc6
                            }, {
                                'id': 0x1,
                                'position': 0xea
                            }],
                            _0x24e753 = _0x3f47d3[_0x3ddeff(0xb0)](_0x366e6d => _0x366e6d[
                                'id'] === _0x3793c9[_0x3ddeff(0xa9)]['id'])['position'];
                        $(_0x3ddeff(0xb2))[_0x3ddeff(0xbf)]({
                            'angle': 0x0,
                            'animateTo': _0x24e753 + 0xe10,
                            'duration': 0x2bc0,
                            'easing': $['easing']['easeInOutQuart'],
                            'callback': function() {
                                const _0x3d2637 = _0x3ddeff;
                                $('#reward-platform')['text'](_0x3793c9[_0x3d2637(
                                        0xa9)][_0x3d2637(0xb3)]), $(_0x3d2637(
                                    0xbd))['text'](_0x3793c9[_0x3d2637(0xa9)][
                                        _0x3d2637(0xb3)
                                    ]), $(_0x3d2637(0xcb))['text'](_0x3793c9['data']
                                        [_0x3d2637(0xac)]), $(_0x3d2637(0xaf))[
                                        'text'](_0x3793c9[_0x3d2637(0xa9)][
                                        'username'
                                    ]), $(_0x3d2637(0xa0))[_0x3d2637(0xc1)](
                                        _0x3793c9[_0x3d2637(0xa9)]['title']), $(
                                        _0x3d2637(0xab))['val'](''), $('#voucher')[
                                        'val'](''), _0x1ef831[_0x3d2637(0xc8)] =
                                    0x1, _0x3793c9[_0x3d2637(0xa9)][_0x3d2637(
                                    0xc4)] !== 0x0 ? showModal(_0x3d2637(0xa7)) :
                                    showModal('zonk-modal'), $(_0x3d2637(0xd5))[0x0]
                                    [_0x3d2637(0xd7)]();
                            }
                        });
                    }
                }
            })[_0x5d02e1(0xcd)](_0x537e89 => {
                const _0x5627ee = _0x5d02e1;
                $(_0x5627ee(0x9f))[_0x5627ee(0xaa)](data['message']);
            }) : ($(_0x5d02e1(0xb5))[_0x5d02e1(0xcf)](_0x5d02e1(0xbe)), $('#warning-ajax')[
                _0x5d02e1(0xcc)] && setTimeout(function() {
                $('#warning-ajax')['addClass']('hidden');
            }, 0x1388), $(_0x5d02e1(0xa1))[_0x5d02e1(0xc1)](
                'Mohon\x20isi\x20kode\x20kupon\x20anda!'));
        });
    });
</script>
