<x-app-layout>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('admin.profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('admin.profile.partials.update-password-form')
                </div>
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
