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
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Header */
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

        /* Cards */
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

        /* Form Elements */
        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-weight: 500;
            font-size: 0.9rem;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            min-height: 100px;
        }

        textarea:focus {
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

        .btn-small {
            padding: 5px 12px;
            font-size: 12px;
        }

        /* Alert Messages */
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

        /* Character Counter */
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

        /* Search Bar */
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

        .search-bar input:focus {
            outline: none;
            border-color: #4a90e2;
        }

        /* Toolbar */
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

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
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
            max-width: 400px;
            word-wrap: break-word;
            font-size: 14px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Checkbox */
        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            color: #666;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* Pagination */
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .pagination nav {
            display: inline-block;
        }

        .pagination .relative {
            display: inline-block;
            margin: 0 2px;
        }

        .pagination a, .pagination span {
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #4a90e2;
            font-size: 14px;
        }

        .pagination span {
            background: #4a90e2;
            color: white;
            border-color: #4a90e2;
        }

        /* Modal */
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

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .card {
                padding: 15px;
            }
            
            th, td {
                padding: 8px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* Loading */
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

    <!-- Send Message Card -->
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

    <!-- Messages Card -->
    <div class="card">
        <h2>Message History</h2>
        
        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search messages..." value="{{ request('search', '') }}">
            <button onclick="searchMessages()" class="btn-secondary">Search</button>
            <button onclick="clearSearch()" class="btn-secondary">Clear</button>
        </div>
        
        <!-- Toolbar -->
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
        
        <!-- Messages Table -->
        <div id="messagesContainer">
            @include('partials.message-table', ['messages' => $messages])
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Message</h3>
        </div>
        <textarea id="editMessageText" rows="4" style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; font-family:inherit;"></textarea>
        <div class="modal-footer">
            <button onclick="closeModal()" class="btn-secondary btn-small">Cancel</button>
            <button onclick="updateMessage()" class="btn-small">Update</button>
        </div>
    </div>
</div>

<script>
    let currentEditId = null;

    // Character counter
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
    
    // Select All functionality
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    function updateBulkDeleteButton() {
        const checkboxes = document.querySelectorAll('.message-checkbox');
        const checkedCount = document.querySelectorAll('.message-checkbox:checked').length;
        
        if (checkedCount > 0) {
            bulkDeleteBtn.style.display = 'inline-block';
            bulkDeleteBtn.textContent = 'Delete Selected (' + checkedCount + ')';
        } else {
            bulkDeleteBtn.style.display = 'none';
        }
    }
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.message-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateBulkDeleteButton();
        });
    }
    
    // Delete single message
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
    
    // Edit message
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
    
    // Bulk delete
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
    
    // Clear all messages
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
    
    // Search messages
    function searchMessages() {
        const searchTerm = document.getElementById('searchInput').value;
        
        fetch('/search-messages?search=' + encodeURIComponent(searchTerm), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('messagesContainer').innerHTML = html;
            attachEventListeners();
        });
    }
    
    function clearSearch() {
        document.getElementById('searchInput').value = '';
        searchMessages();
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
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeModal();
        }
    }
    
    // Initial attachment of event listeners
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