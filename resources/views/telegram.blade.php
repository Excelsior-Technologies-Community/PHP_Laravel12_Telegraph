<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Message Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 8px;
        }

        .header p {
            color: #666;
            font-size: 0.9rem;
        }

        .grid-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e0e0e0;
        }

        .card h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.3rem;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-weight: 500;
            font-size: 0.9rem;
        }

        textarea, input[type="text"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        textarea:focus, input[type="text"]:focus, select:focus {
            outline: none;
            border-color: #4a90e2;
        }

        button {
            background: #4a90e2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        button:hover {
            background: #357abd;
        }

        button:active {
            transform: translateY(1px);
        }

        .btn-block {
            width: 100%;
        }

        .btn-danger {
            background: #e74c3c;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-warning {
            background: #f39c12;
        }

        .btn-warning:hover {
            background: #e67e22;
        }

        .btn-secondary {
            background: #95a5a6;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
        }

        .btn-purple {
            background: #9b59b6;
        }

        .btn-purple:hover {
            background: #8e44ad;
        }

        .btn-small {
            padding: 5px 12px;
            font-size: 12px;
        }

        .alert {
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .char-counter {
            text-align: right;
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }

        .char-counter.warning {
            color: #f39c12;
        }

        .char-counter.danger {
            color: #e74c3c;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 12px;
            text-transform: uppercase;
        }

        .badge-blue { background: #deb887; color: #8b4513; }
        .badge-green { background: #d4edda; color: #155724; }
        .badge-purple { background: #e8daef; color: #6c3483; }

        .search-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .search-bar input {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
            flex-wrap: wrap;
            align-items: center;
        }

        .select-all {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .table-responsive {
            overflow-x: auto;
            max-height: 300px;
            overflow-y: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
            font-size: 14px;
        }

        th {
            background: #fafafa;
            color: #555;
            font-weight: 600;
            font-size: 0.85rem;
        }

        tr:hover {
            background: #f9f9f9;
        }

        .message-content {
            max-width: 300px;
            word-wrap: break-word;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: white;
            margin: 50px auto;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
        }

        .modal-header {
            margin-bottom: 20px;
        }

        .modal-header h3 {
            color: #333;
        }

        .modal-footer {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .audio-player {
            width: 160px;
            height: 28px;
        }

        @media (max-width: 768px) {
            body { padding: 10px; }
            .grid-layout { grid-template-columns: 1fr; }
            .card { padding: 15px; }
            th, td { padding: 8px; }
            .toolbar { flex-direction: column; align-items: stretch; }
        }

        .loading {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Telegram Message Manager</h1>
        <p>Send and manage your Telegram messages</p>
    </div>

    <div class="grid-layout">
        <div class="card">
            <h2>⚙️ Auto-Reply Rules Manager</h2>
            <form method="POST" action="{{ route('auto_replies.store') }}">
                @csrf
                <div class="form-group grid-2">
                    <div>
                        <label>Keyword</label>
                        <input type="text" name="keyword" placeholder="e.g. price" required>
                    </div>
                    <div>
                        <label>Match Type</label>
                        <select name="match_type">
                            <option value="exact">Exact Match</option>
                            <option value="contains">Contains Word</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Bot Response Text</label>
                    <textarea name="reply_text" placeholder="Type what bot will auto reply..." required style="min-height:70px;"></textarea>
                </div>
                <button type="submit" class="btn-block">Add Auto Reply Rule</button>
            </form>

            <div class="table-responsive" style="margin-top:15px;">
                <table>
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>Reply text</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($autoReplies ?? [] as $rule)
                        <tr>
                            <td><span class="badge badge-purple">{{ $rule->keyword }}</span> <small>({{ $rule->match_type }})</small></td>
                            <td>{{ $rule->reply_text }}</td>
                            <td>
                                <form action="{{ route('auto_replies.destroy', $rule->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn-danger btn-small">&times;</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <h2>📜 Bot Command Manager GUI</h2>
            <form method="POST" action="{{ route('commands.store') }}">
                @csrf
                <div class="form-group grid-2">
                    <div>
                        <label>Select Bot</label>
                        <select name="telegraph_bot_id">
                            @foreach($bots ?? [] as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Command</label>
                        <input type="text" name="command" placeholder="e.g. /help" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Command Description</label>
                    <input type="text" name="description" placeholder="Description of what command does..." required>
                </div>
                <button type="submit" class="btn-purple btn-block">Register & Sync Command</button>
            </form>

            <div class="table-responsive" style="margin-top:15px;">
                <table>
                    <thead>
                        <tr>
                            <th>Bot</th>
                            <th>Command</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commands ?? [] as $cmd)
                        <tr>
                            <td>{{ $cmd->bot->name ?? 'Bot' }}</td>
                            <td><strong style="color:#9b59b6;">{{ $cmd->command }}</strong></td>
                            <td>{{ $cmd->description }}</td>
                            <td>
                                <form action="{{ route('commands.destroy', $cmd->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="btn-danger btn-small">&times;</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <h2>Send New Message</h2>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <form id="sendMessageForm" method="POST" action="/send-message">
            @csrf
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" placeholder="Enter your message here..." maxlength="1000" required></textarea>
                <div class="char-counter" id="charCounter">0 / 1000 characters</div>
            </div>
            <button type="submit" id="sendBtn">Send Message</button>
        </form>
    </div>

    <div class="card">
        <h2>Message History Stream (AJAX Live Polling)</h2>
        
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search messages..." value="{{ request('search', '') }}">
            <button onclick="searchMessages()" class="btn-secondary">Search</button>
            <button onclick="clearSearch()" class="btn-secondary">Clear</button>
        </div>
        
        <div class="toolbar">
            <div class="select-all">
                <input type="checkbox" id="selectAllCheckbox">
                <label>Select All</label>
            </div>
            <button onclick="bulkDelete()" class="btn-danger btn-small" id="bulkDeleteBtn" style="display:none">
                Delete Selected
            </button>
            <button onclick="clearAllMessages()" class="btn-warning btn-small">
                Clear All
            </button>
        </div>
        
        <div id="messagesContainer">
            <div class="table-responsive">
                <table id="messages-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;">Select</th>
                            <th style="width: 60px;">ID</th>
                            <th>Chat Detail</th>
                            <th>Message Content</th>
                            <th>Media Attached</th>
                            <th>Direction</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="messages-body">
                        @forelse($messages ?? [] as $msg)
                        <tr class="message-row" data-id="{{ $msg->id }}">
                            <td><input type="checkbox" class="message-checkbox" value="{{ $msg->id }}"></td>
                            <td><strong>{{ $msg->id }}</strong></td>
                            <td>
                                <div><strong>{{ $msg->telegraphChat->name ?? 'Chat Room' }}</strong></div>
                                <div style="font-size:11px; color:#999; font-family:monospace;">ID: {{ $msg->telegraphChat->chat_id ?? 'N/A' }}</div>
                            </td>
                            <td class="message-content">{{ $msg->text ?? $msg->message }}</td>
                            <td>
                                @if(isset($msg->file_type) && $msg->file_type === 'image')
                                    <a href="{{ $msg->file_path }}" target="_blank" class="badge badge-purple" style="text-decoration:none;">🖼️ View Photo</a>
                                @elseif(isset($msg->file_type) && $msg->file_type === 'voice')
                                    <audio src="{{ $msg->file_path }}" controls class="audio-player"></audio>
                                @elseif(isset($msg->file_type) && $msg->file_type === 'pdf')
                                    <a href="{{ $msg->file_path }}" target="_blank" class="badge badge-blue" style="text-decoration:none;">📄 View PDF</a>
                                @else
                                    <span style="color:#aaa; font-size:12px;">None</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ ($msg->direction ?? 'inbound') === 'inbound' ? 'badge-blue' : 'badge-green' }}">
                                    {{ $msg->direction ?? 'inbound' }}
                                </span>
                            </td>
                            <td style="font-size:12px; color:#666;">{{ $msg->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="action-buttons">
                                    <form method="POST" action="/messages/reply/{{ $msg->telegraph_chat_id }}" style="display:flex; gap:3px; margin-bottom:0;">
                                        @csrf
                                        <input type="text" name="text" placeholder="Reply..." required style="padding:4px; font-size:12px; width:100px;">
                                        <button class="btn-small" style="padding:4px 8px;">Send</button>
                                    </form>
                                    <button onclick="editMessage({{ $msg->id }}, '{{ addslashes($msg->text ?? $msg->message) }}')" class="btn-secondary btn-small">Edit</button>
                                    <button onclick="deleteMessage({{ $msg->id }})" class="btn-danger btn-small">Delete</button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <h3>No messages found</h3>
                                <p>History stream is currently empty Room.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($messages) && method_exists($messages, 'links'))
                <div class="pagination">
                    {{ $messages->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Message</h3>
        </div>
        <textarea id="editMessageText" rows="4"></textarea>
        <div class="modal-footer">
            <button onclick="closeModal()" class="btn-secondary btn-small">Cancel</button>
            <button onclick="updateMessage()" class="btn-small">Update</button>
        </div>
    </div>
</div>

<script>
    let currentEditId = null;
    let lastMessageId = {{ isset($messages) && $messages->first() ? $messages->first()->id : 0 }};

    const messageTextarea = document.getElementById('message');
    const charCounter = document.getElementById('charCounter');
    
    if (messageTextarea) {
        messageTextarea.addEventListener('input', function() {
            const length = this.value.length;
            const max = 1000;
            charCounter.textContent = length + ' / ' + max + ' characters';
            
            charCounter.classList.remove('warning', 'danger');
            if (length > max * 0.9) {
                charCounter.classList.add('warning');
            }
            if (length >= max) {
                charCounter.classList.add('danger');
            }
        });
    }
    
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.message-checkbox:checked').length;
        
        if (checkedCount > 0) {
            bulkDeleteBtn.style.display = 'inline-block';
            bulkDeleteBtn.textContent = 'Delete Selected (' + checkedCount + ')';
        } else {
            bulkDeleteBtn.style.display = 'none';
        }
    }
    
    function deleteMessage(id) {
        if (confirm('Are you sure you want to delete this message?')) {
            fetch('/delete-message/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    location.reload();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error deleting message!', 'error');
            });
        }
    }
    
    function editMessage(id, message) {
        currentEditId = id;
        document.getElementById('editMessageText').value = message;
        document.getElementById('editModal').style.display = 'block';
    }
    
    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
        currentEditId = null;
    }
    
    function updateMessage() {
        const newMessage = document.getElementById('editMessageText').value;
        
        if (!newMessage.trim()) {
            showNotification('Message cannot be empty!', 'error');
            return;
        }
        
        fetch('/update-message/' + currentEditId, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: newMessage })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                closeModal();
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error updating message!', 'error');
        });
    }
    
    function bulkDelete() {
        const checkboxes = document.querySelectorAll('.message-checkbox:checked');
        const selectedIds = Array.from(checkboxes).map(cb => cb.value);
        
        if (selectedIds.length === 0) {
            showNotification('Please select messages to delete!', 'error');
            return;
        }
        
        if (confirm('Are you sure you want to delete ' + selectedIds.length + ' message(s)?')) {
            fetch('/bulk-delete-messages', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message_ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    location.reload();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error deleting messages!', 'error');
            });
        }
    }
    
    function clearAllMessages() {
        if (confirm('Warning: This will delete ALL messages. Are you sure?')) {
            fetch('/clear-all-messages', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    location.reload();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error clearing messages!', 'error');
            });
        }
    }
    
    function searchMessages() {
        const searchTerm = document.getElementById('searchInput').value;
        window.location.href = '?search=' + encodeURIComponent(searchTerm);
    }
    
    function clearSearch() {
        window.location.href = window.location.pathname;
    }
    
    function attachEventListeners() {
        const newSelectAll = document.getElementById('selectAllCheckbox');
        if (newSelectAll) {
            newSelectAll.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.message-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = newSelectAll.checked;
                });
                updateBulkDeleteButton();
            });
        }
        
        const checkboxes = document.querySelectorAll('.message-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkDeleteButton);
        });
    }
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = 'alert alert-' + type;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '10000';
        notification.style.maxWidth = '300px';
        notification.innerHTML = message;
        
        document.body.appendChild(notification);
        
        setTimeout(function() {
            notification.remove();
        }, 3000);
    }
    
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeModal();
        }
    }
    
    setInterval(async () => {
        try {
            const response = await fetch(`/messages/api/realtime?last_id=${lastMessageId}`);
            const data = await response.json();
            
            if (data.messages && data.messages.length > 0) {
                const tbody = document.getElementById('messages-body');
                const emptyTr = tbody.querySelector('.empty-state');
                if (emptyTr) {
                    tbody.innerHTML = '';
                }

                data.messages.forEach(msg => {
                    if (msg.id > lastMessageId) {
                        lastMessageId = msg.id;
                    }

                    let mediaHtml = '<span style="color:#aaa; font-size:12px;">None</span>';
                    if (msg.file_type === 'image') {
                        mediaHtml = `<a href="${msg.file_path}" target="_blank" class="badge badge-purple" style="text-decoration:none;">🖼️ View Photo</a>`;
                    } else if (msg.file_type === 'voice') {
                        mediaHtml = `<audio src="${msg.file_path}" controls class="audio-player"></audio>`;
                    } else if (msg.file_type === 'pdf') {
                        mediaHtml = `<a href="${msg.file_path}" target="_blank" class="badge badge-blue" style="text-decoration:none;">📄 View PDF</a>`;
                    }

                    const directionClass = msg.direction === 'outbound' ? 'badge-green' : 'badge-blue';

                    const rowHtml = `
                        <tr class="message-row" data-id="${msg.id}" style="background:#fffde7;">
                            <td><input type="checkbox" class="message-checkbox" value="${msg.id}"></td>
                            <td><strong>${msg.id}</strong></td>
                            <td>
                                <div><strong>${msg.telegraph_chat ? msg.telegraph_chat.name : 'Chat Room'}</strong></div>
                                <div style="font-size:11px; color:#999; font-family:monospace;">ID: ${msg.telegraph_chat ? msg.telegraph_chat.chat_id : 'N/A'}</div>
                            </td>
                            <td class="message-content">${msg.text ? msg.text : msg.message}</td>
                            <td>${mediaHtml}</td>
                            <td><span class="badge ${directionClass}">${msg.direction ? msg.direction : 'inbound'}</span></td>
                            <td style="font-size:12px; color:#666;">Just Now</td>
                            <td>
                                <div class="action-buttons">
                                    <form method="POST" action="/messages/reply/${msg.telegraph_chat_id}" style="display:flex; gap:3px; margin-bottom:0;">
                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                        <input type="text" name="text" placeholder="Reply..." required style="padding:4px; font-size:12px; width:100px;">
                                        <button class="btn-small" style="padding:4px 8px;">Send</button>
                                    </form>
                                    <button onclick="editMessage(${msg.id}, '${(msg.text ? msg.text : msg.message).replace(/'/g, "\\'")}')" class="btn-secondary btn-small">Edit</button>
                                    <button onclick="deleteMessage(${msg.id})" class="btn-danger btn-small">Delete</button>
                                </div>
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('afterbegin', rowHtml);
                });
                attachEventListeners();
            }
        } catch (error) {
            console.error("Realtime fetching error:", error);
        }
    }, 4000);

    document.addEventListener('DOMContentLoaded', function() {
        attachEventListeners();
        
        const sendForm = document.getElementById('sendMessageForm');
        if (sendForm) {
            sendForm.addEventListener('submit', function() {
                const sendBtn = document.getElementById('sendBtn');
                sendBtn.disabled = true;
                sendBtn.innerHTML = '<span class="loading"></span> Sending...';
            });
        }
    });
</script>

</body>
</html>