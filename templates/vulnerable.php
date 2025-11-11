<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LFI Demo - Page Viewer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
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
            border-bottom: 2px solid #f44336;
            padding-bottom: 10px;
        }
        .vulnerability-note {
            background-color: #ffebee;
            border-left: 4px solid #f44336;
            padding: 15px;
            margin: 20px 0;
        }
        .content {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        .error {
            background-color: #ffebee;
            border: 1px solid #f44336;
            padding: 15px;
            border-radius: 4px;
            color: #c62828;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-link:hover {
            background-color: #0b7dda;
        }
        pre {
            background-color: #263238;
            color: #aed581;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vulnerable Page Viewer</h1>
        
        <div class="vulnerability-note">
            <strong>🔴 INTENTIONAL VULNERABILITY:</strong> This file contains a Local File Inclusion (LFI) vulnerability. 
            The code below uses unsanitized user input to include files without proper validation:
            <pre style="margin: 10px 0; background: #fff; color: #333; border: 1px solid #ddd;">include __DIR__ . '/includes/' . $_GET['page'];</pre>
            This is intentionally insecure for demonstration purposes.
        </div>

        <div class="content">
            <?php
            // ============================================
            // INTENTIONAL VULNERABILITY: Local File Inclusion (LFI)
            // ============================================
            // This code is intentionally vulnerable to demonstrate LFI attacks.
            // In production, you MUST validate and sanitize user input.
            // ============================================
            
            if (isset($_GET['page']) && !empty($_GET['page'])) {
                $page = $_GET['page'];
                
                // VULNERABLE CODE: Direct concatenation without sanitization
                // This allows path traversal attacks (e.g., ../../etc/passwd)
                $file_path = __DIR__ . '/includes/' . $page;
                
                // Check if file exists before including
                if (file_exists($file_path)) {
                    echo "<h2>Content of: " . htmlspecialchars($page) . "</h2>";
                    echo "<hr>";
                    // VULNERABLE: Using include without proper validation
                    include $file_path;
                } else {
                    echo '<div class="error">';
                    echo '<strong>Error:</strong> The requested file "' . htmlspecialchars($page) . '" does not exist in the includes directory.';
                    echo '</div>';
                }
            } else {
                echo '<div class="error">';
                echo '<strong>Error:</strong> No page parameter provided. Please specify a page to view.';
                echo '</div>';
            }
            ?>
        </div>

        <a href="index.php" class="back-link">← Back to Home</a>
    </div>
</body>
</html>

