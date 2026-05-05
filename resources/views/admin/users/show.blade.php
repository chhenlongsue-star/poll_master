<x-app-layout>
    <div class="py-12 bg-gray-100">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-8">
                
                <!-- Profile Header -->
                <div class="flex flex-col md:flex-row items-center justify-between border-b pb-8 mb-8">
                    <div class="flex items-center">
                        <img src="{{ $user->avatar }}" class="w-24 h-24 rounded-full border-4 border-indigo-50 shadow-sm">
                        <div class="ml-6">
                            <h2 class="text-3xl font-black text-gray-800">{{ $user->name }}</h2>
                            <p class="text-gray-500">{{ $user->email }}</p>
                            <div class="flex items-center mt-2 gap-2">
                                <span class="px-3 py-1 bg-indigo-600 text-white text-[10px] font-black rounded-full uppercase">{{ $user->role }}</span>
                                @if($user->is_banned)
                                    <span class="px-3 py-1 bg-red-100 text-red-600 text-[10px] font-black rounded-full uppercase">BANNED</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Danger Zone Actions -->
                    <div class="flex gap-3 mt-6 md:mt-0">
                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="px-5 py-2 rounded-md text-xs font-bold text-white shadow-sm {{ $user->is_banned ? 'bg-green-500' : 'bg-orange-500' }}">
                                {{ $user->is_banned ? 'UNBLOCK ACCOUNT' : 'BLOCK ACCOUNT' }}
                            </button>
                        </form>
                        
                        @if(Auth::user()->role === 'admin')
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('DELETE USER PERMANENTLY? This cannot be undone.');">
                            @csrf @method('DELETE')
                            <button class="px-5 py-2 bg-red-600 text-white rounded-md text-xs font-bold shadow-sm">DELETE USER</button>
                        </form>
                        @endif
                    </div>
                </div>

                <!-- User Content Section (Polls Only) -->
                <h3 class="text-lg font-black text-gray-700 mb-6 uppercase tracking-widest border-l-4 border-indigo-500 pl-3">Polls Created</h3>
                <div class="space-y-4">
                    @forelse($polls as $poll)
                        <div class="p-5 border border-gray-100 rounded-xl bg-gray-50/50 flex justify-between items-center group hover:border-indigo-200 transition">
                            <div>
                                <h4 class="font-bold text-gray-800 group-hover:text-indigo-600">{{ $poll->title }}</h4>
                                <p class="text-xs text-gray-400">Created: {{ $poll->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="bg-white px-4 py-2 rounded-lg border border-gray-100 text-center shadow-sm">
                                <span class="block text-lg font-black text-indigo-600 leading-none">{{ $poll->votes_count }}</span>
                                <span class="text-[9px] text-gray-400 uppercase font-bold">Total Votes</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed">
                            <p class="text-gray-400 italic">This user hasn't created any polls yet.</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-6">
                    {{ $polls->links() }}
                </div>
                
                <div class="mt-12 text-center">
                    <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-gray-400 hover:text-indigo-600 transition uppercase tracking-widest">← Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>