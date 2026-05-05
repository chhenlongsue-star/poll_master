<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900 uppercase tracking-widest">
            Profile Information
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            View your account details and profile picture synced from Google.
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <!-- Profile Picture: Matches Dashboard/Admin Table Logic -->
        <div class="flex items-center space-x-6">
            <div class="shrink-0">
                <img class="h-24 w-24 object-cover rounded-full border-4 border-indigo-50 shadow-md" 
                     src="{{ Auth::user()->avatar ?? Auth::user()->google_avatar ?? asset('images/default-avatar.png') }}" 
                     alt="Profile Photo" />
            </div>
            <div>
                <span class="block text-sm font-black text-indigo-600 uppercase tracking-tighter">Syncing from Google</span>
                <p class="text-xs text-gray-500">Your profile picture is managed by your Google Account.</p>
            </div>
        </div>

        <!-- Read-Only Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-bold text-gray-700 text-xs uppercase">Name</label>
                <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-md text-gray-600 shadow-sm font-medium">
                    {{ Auth::user()->name }}
                </div>
            </div>

            <div>
                <label class="block font-bold text-gray-700 text-xs uppercase">Email Address</label>
                <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-md text-gray-600 shadow-sm font-medium">
                    {{ Auth::user()->email }}
                </div>
            </div>
        </div>

        <!-- Role Badge -->
        <div>
            <label class="block font-bold text-gray-700 text-xs uppercase">Account Role</label>
            <div class="mt-2">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-black uppercase bg-indigo-600 text-white shadow-sm">
                    {{ Auth::user()->role }}
                </span>
            </div>
        </div>
    </div>
</section>