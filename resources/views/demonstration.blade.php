@extends('layout')

@section('content')
    <h1>Users List</h1>
    <div id="users-list">
        @if(isset($users) && $users->isEmpty())
            @foreach ($users as $user)
                <div>
                    <strong>{{ $user->name }}</strong> - {{ $user->email }}
                </div>
            @endforeach
        @endif

    </div>
    <button id="show-more">Show more</button>

    <script>
        let page = 1;
        $('#show-more').click(function () {
            page++;
            $.ajax({
                url: '/api/users',
                data: {page: page, count: 6},
                success: function (response) {
                    response.users.forEach(user => {
                        $('#users-list').append(`
                            <div>
                                <strong>${user.name}</strong> - ${user.email}
                            </div>
                        `);
                    });
                }
            });
        });
    </script>
@endsection
