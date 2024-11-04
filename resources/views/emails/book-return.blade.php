<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Book Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #0073e6;
            margin-top: 0;
        }
        p {
            line-height: 1.6;
        }
        .details {
            margin: 20px 0;
            padding: 10px;
            background: #f9f9f9;
            border-left: 4px solid #0073e6;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #0073e6;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            font-size: 0.8em;
            color: #777;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello {{ $book['name'] }},</h2>
        <p>Thank you for borrowing a book from our library!</p>

        <div class="details">
            <p><strong>Book Title:</strong> {{ $book['title'] }}</p>
            <p><strong>Penalty (if applicable):</strong> {{ $book['penalty'] }}</p>
        </div>
        <p>Please return the book by the due date to avoid any further penalties. If you have any questions, feel free to contact us.</p>
    </div>
</body>
</html>
