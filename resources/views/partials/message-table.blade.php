<div class="table-responsive">
    @if($messages->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">
                        <input type="checkbox" id="selectAllCheckbox">
                    </th>
                    <th style="width: 60px;">ID</th>
                    <th>Message</th>
                    <th style="width: 160px;">Time</th>
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $msg)
                <tr>
                    <td>
                        <input type="checkbox" class="message-checkbox" value="{{ $msg->id }}">
                    </td>
                    <td>{{ $msg->id }}</td>
                    <td class="message-content">{{ $msg->message }}</td>
                    <td>{{ $msg->created_at->format('Y-m-d H:i:s') }}</td>
                    <td>
                        <div class="action-buttons">
                            <button onclick='editMessage({{ $msg->id }}, "{{ addslashes($msg->message) }}")' 
                                    class="btn-secondary btn-small">Edit</button>
                            <button onclick="deleteMessage({{ $msg->id }})" 
                                    class="btn-danger btn-small">Delete</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="pagination">
            {{ $messages->appends(['search' => request('search')])->links() }}
        </div>
    @else
        <div class="empty-state">
            <h3>No messages found</h3>
            <p>Start by sending your first message above</p>
        </div>
    @endif
</div>