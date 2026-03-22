@extends('layouts.app')
@section('title', 'Admin Dashboard | ReelTime')


@section('content')
<div class="min-h-screen py-8 px-4 md:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
       <div class="bg-gradient-to-r from-[#1e1e2d] to-[#2a2a3e] rounded-2xl p-6 md:p-8 mb-8 border border-[#8a2be2]/20 shadow-lg">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="relative">
                    <img src="" alt="Admin" class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-[#8a2be2] object-cover shadow-xl">
                     </div>
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Welcome back</h1>
                    <p class="text-[#8a2be2] font-medium">Administrator</p>
                      </div>
                <div class="flex gap-3">
                    <button onclick="openModal('addMovieModal')" class="bg-[#8a2be2] hover:bg-[#7a1fd1] text-white px-5 py-2.5 rounded-xl font-semibold transition duration-300 hover:scale-105 hover:shadow-lg flex items-center gap-2">
                        <i class="fas fa-plus"></i> Add Movie
                    </button>
                    <button onclick="openModal('addGameModal')" class="bg-transparent border-2 border-[#8a2be2] text-[#8a2be2] hover:bg-[#8a2be2] hover:text-white px-5 py-2.5 rounded-xl font-semibold transition duration-300 hover:scale-105 flex items-center gap-2">
                        <i class="fas fa-plus"></i> Add Game
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-[#1e1e2d] rounded-xl p-5 border border-white/10 hover:border-[#8a2be2] transition duration-300 hover:scale-105 hover:shadow-xl group cursor-pointer">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#8a2be2]/20 rounded-xl flex items-center justify-center group-hover:bg-[#8a2be2]/30 transition">
                        <i class="fas fa-film text-2xl text-[#8a2be2]"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Movies</p>
                    </div>
                </div>
            </div>
            <div class="bg-[#1e1e2d] rounded-xl p-5 border border-white/10 hover:border-[#8a2be2] transition duration-300 hover:scale-105 hover:shadow-xl group cursor-pointer">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#8a2be2]/20 rounded-xl flex items-center justify-center group-hover:bg-[#8a2be2]/30 transition">
                        <i class="fas fa-gamepad text-2xl text-[#8a2be2]"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Games</p>
                    </div>
                </div>
            </div>
            <div class="bg-[#1e1e2d] rounded-xl p-5 border border-white/10 hover:border-[#8a2be2] transition duration-300 hover:scale-105 hover:shadow-xl group cursor-pointer">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#8a2be2]/20 rounded-xl flex items-center justify-center group-hover:bg-[#8a2be2]/30 transition">
                        <i class="fas fa-users text-2xl text-[#8a2be2]"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Users</p>
                    </div>
                </div>
            </div>
            <div class="bg-[#1e1e2d] rounded-xl p-5 border border-white/10 hover:border-[#8a2be2] transition duration-300 hover:scale-105 hover:shadow-xl group cursor-pointer">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#8a2be2]/20 rounded-xl flex items-center justify-center group-hover:bg-[#8a2be2]/30 transition">
                        <i class="fas fa-ticket-alt text-2xl text-[#8a2be2]"></i>
                    </div>
                    <div>
                        <p class="text-gray-400 text-sm">Total Bookings</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-b border-white/10 mb-6">
            <div class="flex gap-6">
                <button class="tab-btn pb-3 text-white border-b-2 border-[#8a2be2] font-medium" id="moviesTabBtn" onclick="switchTab('movies')">
                    <i class="fas fa-film mr-2"></i> Movies
                </button>
                <button class="tab-btn pb-3 text-gray-400 hover:text-white font-medium" id="gamesTabBtn" onclick="switchTab('games')">
                    <i class="fas fa-gamepad mr-2"></i> Games
                </button>
                <button class="tab-btn pb-3 text-gray-400 hover:text-white font-medium" id="bookingsTabBtn" onclick="switchTab('bookings')">
                    <i class="fas fa-ticket-alt mr-2"></i> Bookings
                </button>
                <button class="tab-btn pb-3 text-gray-400 hover:text-white font-medium" id="usersTabBtn" onclick="switchTab('users')">
                    <i class="fas fa-users mr-2"></i> Users
                </button>
            </div>
        </div>

        <div id="moviesTab" class="tab-pane">
            <div class="bg-[#1e1e2d] rounded-xl border border-white/10 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#8a2be2]/20 border-b border-[#8a2be2]">
                            <tr><th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">ID</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Poster</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Title</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Rating</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Duration</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
        <div id="gamesTab" class="tab-pane hidden">
            <div class="bg-[#1e1e2d] rounded-xl border border-white/10 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#8a2be2]/20 border-b border-[#8a2be2]">
                            <tr><th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">ID</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Icon</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Title</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Type</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Questions</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Actions</th>
                            </tr>
                        </thead>
                       <tbody>

                       </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Bookings Tab -->
        <div id="bookingsTab" class="tab-pane hidden">
            <div class="bg-[#1e1e2d] rounded-xl border border-white/10 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#8a2be2]/20 border-b border-[#8a2be2]">
                            <tr><th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">ID</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">User</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Movie</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Date</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Status</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Users Tab -->
        <div id="usersTab" class="tab-pane hidden">
            <div class="bg-[#1e1e2d] rounded-xl border border-white/10 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#8a2be2]/20 border-b border-[#8a2be2]">
                            <tr><th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">ID</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Avatar</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Username</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Email</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Member Since</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Role</th>
                                <th class="text-left py-4 px-4 text-[#8a2be2] font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="addMovieModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden items-center p-4">
   
</div>

<div id="addGameModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden items-center  p-4" >
  
</div>

<script>
    // This function bas la taghyir l style for which tab selected ma3 l content
    function switchTab(tab) {
        const tabs = ['movies', 'games', 'bookings', 'users']; 
        const tabIds = {
            movies: 'moviesTab',
            games: 'gamesTab',
            bookings: 'bookingsTab',
            users: 'usersTab'
        };
        const btnIds = {
            movies: 'moviesTabBtn',
            games: 'gamesTabBtn',
            bookings: 'bookingsTabBtn',
            users: 'usersTabBtn'
        };
        tabs.forEach(t => {
            const content = document.getElementById(tabIds[t]);
            const btn = document.getElementById(btnIds[t]);
            if (t === tab) {
                content.classList.remove('hidden');
                btn.classList.add('text-white', 'border-b-2', 'border-[#8a2be2]');
                btn.classList.remove('text-gray-400');
            } else {
                content.classList.add('hidden');
                btn.classList.remove('text-white', 'border-b-2', 'border-[#8a2be2]');
                btn.classList.add('text-gray-400');
            }
        });
    }


    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.getElementById(modalId).classList.add('flex');
        document.body.style.overflow = 'hidden';
    }


    document.addEventListener('DOMContentLoaded', () => switchTab('movies'));
</script>
@endsection