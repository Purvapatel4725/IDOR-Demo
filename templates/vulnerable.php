<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LFI Demo - Page Viewer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background: #ecf0f1;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 15px 0;
            border-bottom: 1px solid #bdc3c7;
            margin-bottom: 30px;
            font-size: 12px;
            color: #7f8c8d;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border: 1px solid #ddd;
        }
        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #34495e;
            font-weight: 400;
        }
        .warning {
            background: #fff3cd;
            border-left: 3px solid #ffc107;
            padding: 12px 15px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #856404;
        }
        .warning strong {
            font-weight: 600;
        }
        .content {
            margin: 25px 0;
            padding: 20px;
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
        }
        .content h2 {
            font-size: 22px;
            margin-bottom: 10px;
            color: #34495e;
            font-weight: 400;
        }
        .content p {
            margin-bottom: 10px;
            color: #555;
            font-size: 14px;
        }
        .content code {
            background: #e8e8e8;
            padding: 2px 6px;
            font-size: 13px;
            color: #c0392b;
        }
        .content h3 {
            font-size: 18px;
            margin: 15px 0 10px 0;
            color: #34495e;
            font-weight: 400;
        }
        .content ul {
            margin: 10px 0 15px 20px;
        }
        .content li {
            margin-bottom: 6px;
            color: #555;
        }
        .content hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 15px 0;
        }
        .content em {
            color: #7f8c8d;
            font-style: italic;
        }
        .error {
            background: #f8d7da;
            border-left: 3px solid #dc3545;
            padding: 12px 15px;
            color: #721c24;
            font-size: 14px;
        }
        .error strong {
            font-weight: 600;
        }
        pre {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            overflow-x: auto;
            font-size: 13px;
            line-height: 1.5;
            margin: 15px 0;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 16px;
            background: #3498db;
            color: white;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="header">
        Copyright 2025 by Purva Patel
    </div>
    
    <div class="container">
        <h1>Page Viewer</h1>
        
        <div class="warning">
            <strong>Warning:</strong> This application contains intentional security vulnerabilities for educational purposes only. Do not expose this application to the internet or use it in production environments.
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
                        echo "<pre>";
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
