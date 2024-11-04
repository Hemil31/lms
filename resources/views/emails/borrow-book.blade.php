<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Book Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f0f4f8;
        }
        .container {
            text-align: center;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
        }
        h1 {
            font-size: 1.4em;
            color: #333;
        }
        p {
            color: #666;
            font-size: 1em;
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hello, {{ $book['name'] }}</h1>
        <p><strong>Book Title:</strong> {{ $book['title'] }}</p>
        <p><strong>Due Date:</strong> {{ $book['duedate'] }}</p>
    </div>
</body>
</html>
