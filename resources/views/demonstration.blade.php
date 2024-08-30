@extends('layout')

@section('content')
    <div class="container">
        <h1>Users List</h1>
        <div id="users-list" class="user-list">
            @if(isset($users))
                @forelse ($users as $user)
                    <div class="user-card">
                        @if($user->photo)
                            <img src="storage/{{ $user->photo }}" alt="{{ $user->name }}" class="user-avatar">
                        @else
                            <div class="avatar-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                        @endif
                        <div class="user-info">
                            <h2>{{ $user->name }}</h2>
                            <p class="user-position">Position: {{ $user->position->name ?? 'No position specified' }}</p>
                            <p>Email: {{ $user->email }}</p>
                            <p>Phone: {{ $user->phone }}</p>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; color: #718096;">No users found.</p>
                @endforelse
            @endif
        </div>

        <button id="show-more" class="show-more-btn">Show More</button>
    </div>
    <x-userform>
    </x-userform>
    <script>
            let page = 1;
            document.getElementById('show-more').addEventListener('click', function () {
                page++;
                fetch(`/api/users?page=${page}&count=6`)
                    .then(response => response.json())
                    .then(data => {
                        data.users.forEach(user => {
                            const userCard = document.createElement('div');
                            userCard.className = 'user-card';
                            userCard.innerHTML = `
                            ${user.photo
                                ? `<img src="storage/${user.photo}" alt="${user.name}" class="user-avatar">`
                                : `<div class="avatar-placeholder">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                   </div>`
                            }
                            <div class="user-info">
                                <h2>${user.name}</h2>
                                <p class="user-position">Position : ${user.position || 'No position specified'}</p>
                                <p>Phone number: ${user.phone || user.email}</p>
                            </div>
                        `;
                            document.getElementById('users-list').appendChild(userCard);
                        });
                    });
            });
    </script>
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2rem;
        }
        .user-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .user-card {
            display: flex;
            align-items: center;
            padding: 1rem;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .user-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            margin-right: 1rem;
            object-fit: cover;
        }
        .avatar-placeholder {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background-color: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }
        .user-info h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1a202c;
            margin: 0 0 0.25rem 0;
        }
        .user-info p {
            margin: 0;
            color: #4a5568;
        }
        .user-info .user-position {
            color: #2d3748;
        }
        .show-more-btn {
            display: block;
            width: 200px;
            margin: 0 auto;
            padding: 0.5rem 1rem;
            background-color: #4299e1;
            color: white;
            text-align: center;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .show-more-btn:hover {
            background-color: #3182ce;
        }
    </style>
@endsection
