<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="!text-lg pr-3 whitespace-nowrap text-white mb-2">Admin Log Activity</h3>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <x-bladewind.table divider="thin" compact="true" search_placeholder="Find data..." class_search="w-1/3">
                        <x-slot name="header" class="text-center">
                            <th class="!text-center">admin username</th>
                            <th class="!text-center">act on page</th>
                            <th class="!text-center">activity</th>
                            <th class="!text-center">detail</th>
                            <th class="!text-center">act time</th>
                        </x-slot>
                        @foreach ($logs as $data)
                            <tr class="text-center">
                                <td>{{ $data->admin->username }}</td>
                                <td>{{ $data->act_on }}</td>
                                <td>{{ $data->activity }}</td>
                                <td class="text-left w-[500px]">{{ $data->detail }}</td>
                                <td class="w-[120px]">
                                    {{ $data->created_at ? $data->created_at->timezone('Asia/Jakarta') : '' }}</td>
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

    <x-collapse collapsed="false">
        <x-slot name="title">
            Admin Management
        </x-slot>
        <div class="block">
            <x-bladewind.button size="small" type="primary" onclick="window.location='{{ route('register') }}'">
                Register New Admin
            </x-bladewind.button>
            <x-bladewind.table divider="thin" compact="true" class_search="w-1/3">
                <x-slot name="header" class="text-center">
                    <th class="!text-center">id</th>
                    <th class="!text-center">name</th>
                    <th class="!text-center">username</th>
                    <th class="!text-center">email</th>
                    <th class="!text-center">level</th>
                    <th class="!text-center">action</th>
                </x-slot>
                @foreach ($users as $data)
                    <tr class="text-center">
                        <td>{{ $data->id }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->username }}</td>
                        <td>{{ $data->email }}</td>
                        <td>{{ $data->level === 10 ? 'Super Admin' : 'Admin' }}</td>
                        <td>
                            <div class="flex justify-center gap-2">
                                <x-bladewind.button.circle class="w-8 h-8 !p-0" size="tiny" icon="pencil"
                                    tooltip="Edit" onclick="editUser({{ $data }})" />
                                <x-bladewind::button.circle class="w-8 h-8 !p-0" size="tiny" icon="trash" color="red"
                                    tooltip="Delete" onclick="deleteUser({{ $data->id }})" />
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

    let userIdToDelete;

    function deleteUser(userId) {
        userIdToDelete = userId;
        showModal('delete-user');
    }

    function editUser(dataUser) {
        // Set the data to the form fields
        $('input[name="id"]').val(dataUser.id);
        $('input[name="name"]').val(dataUser.name);
        $('input[name="username"]').val(dataUser.username);
        $('input[name="email"]').val(dataUser.email);
        $('select[name="level"]').val(dataUser.level);


        // Show the modal
        showModal('update-profile-admin');
    }

    function confirmDelete() {
        fetch(`{{ url('/system/admin/') }}/${userIdToDelete}`, {
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

    function saveProfile() {
        document.getElementById('profile-form').submit();
    }
</script>

<x-bladewind.modal size="big" center_action_buttons="true" type="warning" title="Confirm User Deletion"
    ok_button_action="confirmDelete()" close_after_action="false" name="delete-user" ok_button_label="Yes, delete"
    cancel_button_label="don't delete">
    Are you sure you want to delete this user? This action cannot be undone.
</x-bladewind.modal>

<x-bladewind::modal backdrop_can_close="false" name="update-profile-admin" center_action_buttons="true"
    ok_button_action="saveProfile()" ok_button_label="Update" close_after_action="true">

    <form method="post" action="{{ route('users.update') }}" id="profile-form" class="profile-form-ajax">
        @csrf
        <b>Edit Admin Profile</b>
        <x-bladewind.input name="id" class="hidden" />
        <div class="grid grid-cols-2 gap-4 mt-6">
            <x-bladewind.input required="true" name="name" label="Name"
                error_message="Please enter your first name" value="" />
            <x-bladewind.input required="true" name="username" label="Username"
                error_message="Please enter your last name" value="" />
        </div>
        <x-bladewind.input required="true" name="email" label="Email address" error_message="Please enter your email"
            value="" />

        <select name="level" id="level" class="w-full bg-inherit mb-3">
            <option value="10">Super Admin</option>
            <option value="1">Admin</option>
        </select>

        <x-bladewind.input type="password" name="password" label="Password"
            error_message="Please enter new password" />
    </form>
</x-bladewind::modal>
