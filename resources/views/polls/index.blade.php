<table class="min-w-full">
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($polls as $poll)
        <tr>
            <td>{{ $poll->title }}</td>
            <td>{{ $poll->user->name }}</td>
            <td>
                <span class="{{ $poll->is_active ? 'text-green-500' : 'text-red-500' }}">
                    {{ $poll->is_active ? 'Live' : 'Closed' }}
                </span>
            </td>
            <td>
                <form action="{{ route('polls.destroy', $poll) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="text-red-600">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>