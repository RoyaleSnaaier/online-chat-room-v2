<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're Banned</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        h1 {
            margin-top: 20px;
        }
        p {
            margin-top: 10px;
            font-size: 18px;
        }
        #secret {
            display: none;
            margin-top: 20px;
            font-size: 24px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }
        button:hover {
            background-color: #45a049;
        }
        form {
            margin-top: 30px;
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #222;
            color: #fff;
            font-size: 16px;
            transition: border-color 0.3s ease;
            animation: fadeIn 1s ease;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        textarea:focus {
            border-color: #4CAF50;
            outline: none;
        }
        textarea {
            height: 150px;
            resize: vertical;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Oops, looks like you've been banned!</h1>
        <p>But here's something special for you:</p>
        <button onclick="showSecret()">Unlock Secret</button>
        <div id="secret">
            <p>Congratulations, you've unlocked the secret!</p>
            <p>Here's a hidden message: <span id="hiddenMessage">Keep smiling :)</span></p>
        </div>

        <form action="mailto:your_email@example.com" method="post" enctype="text/plain">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit">Send Email</button>
        </form>
    </div>

    <script>
        // Function to show the secret and reveal hidden messages
        function showSecret() {
            var secretDiv = document.getElementById('secret');
            var hiddenMessageSpan = document.getElementById('hiddenMessage');
            secretDiv.style.display = 'block';

            // Play spooky sound effect
            var audio = new Audio('button.mp3');
            audio.play();

            // Change the hidden message after a delay
            setTimeout(function() {
                hiddenMessageSpan.textContent = "You're awesome!";
            }, 3000);

            // Shake the button
            var button = document.querySelector('button');
            button.classList.add('shake');
        }

        // Function to animate the inputs when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            var inputs = document.querySelectorAll('input, textarea');
            inputs.forEach(function(input) {
                input.style.animation = 'fadeIn 1s ease';
            });
        });

        
    </script>

    <style>
        /* Shake animation */
        .shake {
            animation: shake 0.5s infinite alternate;
        }

        @keyframes shake {
            from { transform: translateX(-5px); }
            to { transform: translateX(5px); }
        }

        /* Fade in animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</body>
</html>
