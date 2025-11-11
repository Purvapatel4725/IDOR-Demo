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
            <strong>DEMO ONLY:</strong> This vulnerability allows reading files from anywhere in the container, 
            including system files like /etc/passwd, /etc/hostname, and configuration files. 
            This is intentionally insecure for demonstration purposes.
        </div>

        <div class="content">
            <?php
            // ============================================
            // INTENTIONAL VULNERABILITY: Local File Inclusion (LFI)
            // ============================================
            // This code is intentionally vulnerable to demonstrate LFI attacks.
            // In production, you MUST validate and sanitize user input.
            // DEMO ONLY: This allows reading files from anywhere in the container.
            // ============================================
            
            if (isset($_GET['page']) && !empty($_GET['page'])) {
                $page = $_GET['page'];
                $file_path = null;
                
                // VULNERABLE CODE: Direct concatenation without sanitization
                // This allows path traversal attacks to read ANY file in the container
                // Examples: ../../etc/passwd, ../../etc/hostname, ../../proc/version
                
                // DEMO: Check if it's an absolute path (starts with /)
                // This allows reading container system files directly
                if (strpos($page, '/') === 0) {
                    // Absolute path - try to read directly (for container files like /etc/passwd)
                    if (file_exists($page) && is_file($page) && is_readable($page)) {
                        $file_path = $page;
                    }
                } else {
                    // Relative path - construct from includes directory
                    $file_path = __DIR__ . '/includes/' . $page;
                    
                    // Resolve path traversal sequences
                    $resolved_path = realpath($file_path);
                    if ($resolved_path && file_exists($resolved_path) && is_file($resolved_path)) {
                        $file_path = $resolved_path;
                    }
                }
                
                // Check if file exists and is readable
                if ($file_path && file_exists($file_path) && is_file($file_path) && is_readable($file_path)) {
                    echo "<h2>Content of: " . htmlspecialchars($page) . "</h2>";
                    echo "<p><strong>Resolved path:</strong> <code>" . htmlspecialchars($file_path) . "</code></p>";
                    echo "<hr>";
                    
                    // For PHP files, include them; for text files, read and display
                    $extension = pathinfo($file_path, PATHINFO_EXTENSION);
                    if ($extension === 'php' && strpos($file_path, __DIR__) === 0) {
                        // Only include PHP files from within the application directory
                        // VULNERABLE: Using include without proper validation
                        include $file_path;
                    } else {
                        // Read and display file contents (for text files, configs, system files, etc.)
                        echo "<pre style='background: #263238; color: #aed581; padding: 15px; border-radius: 4px; overflow-x: auto;'>";
                        echo htmlspecialchars(file_get_contents($file_path));
                        echo "</pre>";
                    }
                } else {
                    echo '<div class="error">';
                    echo '<strong>Error:</strong> The requested file "' . htmlspecialchars($page) . '" does not exist or is not readable.';
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

