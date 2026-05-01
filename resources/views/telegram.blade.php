<!DOCTYPE html>
<html>
<head>
    <title>Telegram Message Sender</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            font-family: Arial;
            background: #f3f4f6;
            padding: 30px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
        }

        button {
            background: #4f46e5;
            color: white;
            border: none;
            cursor: pointer;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background: #4f46e5;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Send Message Box -->
    <div class="box">
        <h2>Send Telegram Message</h2>

        @if(session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif

        @if(session('error'))
            <p class="error">{{ session('error') }}</p>
        @endif

        <form method="POST" action="/send-message">
            @csrf
            <input type="text" name="message" placeholder="Enter message..." required>
            <button type="submit">Send</button>
        </form>
    </div>

    <!-- Message History -->
    <div class="box">
        <h3>Message History</h3>

        <table>
            <tr>
                <th>ID</th>
                <th>Message</th>
                <th>Time</th>
            </tr>

            @forelse($messages as $msg)
            <tr>
                <td>{{ $msg->id }}</td>
                <td>{{ $msg->message }}</td>
                <td>{{ $msg->created_at }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3">No messages found</td>
            </tr>
            @endforelse
        </table>
    </div>

</div>

</body>
</html>