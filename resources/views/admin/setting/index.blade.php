<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Setting Apps') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Update your setting for this apps.') }}
                    </p>
                </header>
                <form method="post" action="{{ route('setting.update') }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="block w-full">
                            <div class="flex flex-col">
                                <x-input-label for="title" :value="__('Seo Title')" />
                                <x-bladewind.input name="seo_title" placeholder="Title" class="rounded-md"
                                    value="{{ $setting_title->value }}" />
                            </div>

                            <div class="flex flex-col">
                                <x-input-label for="setting_seo_key_word" :value="__('Seo Keyword')" />
                                <x-bladewind.input name="seo_key_words" placeholder="Seo Keyword" class="rounded-md"
                                    value="{{ $setting_seo_key_word->value }}" />
                            </div>

                            <div class="flex flex-col">
                                <x-input-label for="setting_seo_description" :value="__('Seo Description')" />
                                <x-bladewind.input name="seo_description" placeholder="Seo Description"
                                    class="rounded-md" value="{{ $setting_seo_description->value }}" />
                            </div>

                            <div class="flex flex-col">
                                <x-input-label for="setting_hyperlink" :value="__('Hyperlink')" />
                                <span class="text-white text-opacity-40 text-sm">This column is to include outgoing
                                    links from this application, for ex: https://heylink.com or directly to the platform
                                    https://buah4d.com</span>
                                <x-bladewind.input name="hyperlink" placeholder="Hyperlink" class="rounded-md"
                                    value="{{ $setting_hyperlink->value }}" />
                            </div>

                            <div class="flex flex-col">
                                <x-input-label for="setting_ip_white_list" :value="__('IP Whitelist')" />
                                <span class="text-white text-opacity-40 text-sm">If want to have more than one of IP
                                    address in whitelist, IP must be separate by "," (coma without space between) ex:
                                    127.0.0.1,131.24.34.211,124.135.34.210</span>
                                <x-bladewind.input name="ip_white_list" placeholder="IP Whitelist" class="rounded-md"
                                    value="{{ $setting_ip_white_list->value }}" />
                            </div>
                            
                            <div class="flex flex-col mt-3 mb-4">
                                <x-input-label for="wellcome_text" :value="__('Wellcome Text on Popup')" />
                                <span class="text-white text-opacity-40 text-sm">Organize content using HTML tags, so it looks pretty</span>
                                <textarea id="wellcome_text" name="wellcome_text" rows="4">{{ trim($setting_wellcome_modal->value) }}</textarea>
                            </div>

                            <div class="flex flex-col mb-4">
                                <x-input-label for="guide_text" :value="__('Guide Text on Popup')" />
                                <span class="text-white text-opacity-40 text-sm">Organize content using HTML tags, so it looks pretty</span>
                                <textarea id="guide_text" name="guide_text" rows="4">{{ trim($setting_guide_modal->value) }}</textarea>
                            </div>

                            <div class="flex items-center
                                gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                            </div>
                        </div>
                        <div class="block w-full">
                            <div class="w-full grid grid-cols-2 gap-4">
                                <div class="block">
                                    <x-bladewind.filepicker label="Favicon" name="favicon" placeholder="Favicon"
                                        selected_value_class="h-40" accepted_file_types=".png, .ico"
                                        url="{{ $setting_favicon->value ? asset('/storage/' . $setting_favicon->value) : '' }}" />
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <x-input-label for="win_text" :value="__('Text Win Popup')" />
                                <span class="text-white text-opacity-40 text-sm">Dynamic key must be following below example</span>
                                <span class="text-white text-opacity-40 text-sm">Username = {{'<span id="reward-username"></span>'}}</span>
                                <span class="text-white text-opacity-40 text-sm">Platform / Situs = {{'<span id="reward-platform"></span>'}}</span>
                                <span class="text-white text-opacity-40 text-sm">Reward Value = {{'<span id="reward-value"></span>'}}</span>
                                <textarea id="win_text" name="win_text" rows="4">{{ trim($setting_win_modal->value) }}</textarea>
                            </div>

                            <div class="flex flex-col mt-4">
                                <x-input-label for="lose_text" :value="__('Text Lose Popup')" />
                                <span class="text-white text-opacity-40 text-sm">Dynamic key must be following below example</span>
                                <span class="text-white text-opacity-40 text-sm">Username = {{'<span id="lose-username"></span>'}}</span>
                                <span class="text-white text-opacity-40 text-sm">Platform / Situs = {{'<span id="lose-platform"></span>'}}</span>
                                <textarea id="lose_text" name="lose_text" rows="4">{{ trim($setting_lose_modal->value) }}</textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
            <x-bladewind.alert type="warning">
                {{ session('warning') }}
            </x-bladewind.alert>
        </div>
    @endif
</x-app-layout>

<script>
    if ($('#timeout').length) {
        setTimeout(function() {
            $('#timeout').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 5000);
    }
</script>
