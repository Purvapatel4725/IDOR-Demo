<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LFI Demo - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .links {
            margin: 20px 0;
        }
        .links a {
            display: inline-block;
            margin: 5px 10px 5px 0;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .links a:hover {
            background-color: #45a049;
        }
        .form-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        input[type="text"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 8px 15px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Local File Inclusion (LFI) Demo</h1>
        
        <div class="warning">
            <strong>⚠️ Warning:</strong> This application contains intentional security vulnerabilities for educational purposes only. 
            Do not expose this application to the internet or use it in production environments.
        </div>

        <p>This demo application demonstrates a Local File Inclusion (LFI) vulnerability. 
        Use the links below or the form to view different pages.</p>

        <div class="links">
            <h3>Quick Links:</h3>
            <a href="vulnerable.php?page=about.php">About</a>
            <a href="vulnerable.php?page=contact.php">Contact</a>
            <a href="vulnerable.php?page=help.php">Help</a>
        </div>

        <div class="form-section">
            <h3>Custom Page Loader:</h3>
            <form method="GET" action="vulnerable.php">
                <label for="page">Page to load:</label>
                <input type="text" name="page" id="page" placeholder="e.g., about.php" value="<?php echo htmlspecialchars($_GET['page'] ?? ''); ?>">
                <button type="submit">Load Page</button>
            </form>
            <p><small>Try entering different file names to see how the application handles file inclusion.</small></p>
        </div>
    </div>
</body>
</html>

