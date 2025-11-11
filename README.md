# Local File Inclusion (LFI) Vulnerability Demo

## Overview

This repository contains a minimal, intentionally-vulnerable web application designed to demonstrate **Local File Inclusion (LFI)** vulnerabilities in PHP. This demo is intended for educational purposes in a classroom or lab environment.

### What is LFI?

**Local File Inclusion (LFI)** is a web application vulnerability that occurs when an application includes files from the local filesystem without proper validation of user input. Attackers can exploit this vulnerability to:

- Read sensitive files from the server (e.g., configuration files, source code)
- Execute malicious code if file upload is also possible
- Access system files through path traversal attacks (e.g., `../../etc/passwd`)

### Why is LFI Dangerous?

LFI vulnerabilities can lead to:
- **Information Disclosure**: Attackers can read configuration files, source code, or other sensitive data
- **Remote Code Execution (RCE)**: If combined with file upload capabilities, attackers can upload and execute malicious PHP code
- **System Compromise**: In some cases, LFI can be escalated to gain full control of the server

### What This Demo Does

This application demonstrates a classic LFI vulnerability where user input (via the `page` GET parameter) is directly concatenated into a file path without sanitization. The vulnerable code is clearly marked and intentionally insecure for educational purposes.

## ⚠️ Security Warning

**IMPORTANT**: This application contains intentional security vulnerabilities and must **ONLY** be run in an isolated lab environment. The application is configured to bind only to `127.0.0.1:8080` and should **NEVER** be exposed to the internet or used in production.

## Quick Start

### Prerequisites

- Docker and Docker Compose installed on your system
- Access to a terminal/command line

### Running the Demo

1. **Build and start the container:**
   ```bash
   docker compose up --build
   ```

2. **Access the application:**
   - Open your browser and navigate to: `http://127.0.0.1:8080`
   - Or use curl: `curl http://127.0.0.1:8080`

3. **Stop the container:**
   ```bash
   docker compose down
   ```

## Demo Script

### Step 1: Access the Homepage

```bash
curl http://127.0.0.1:8080/
```

Or open `http://127.0.0.1:8080` in your browser.

### Step 2: View a Safe Page

```bash
curl http://127.0.0.1:8080/vulnerable.php?page=about.php
```

This should display the content of `about.php` from the `includes/` directory.

### Step 3: Try Path Traversal

Attempt to read the `README_FILES.txt` file using path traversal:

```bash
curl "http://127.0.0.1:8080/vulnerable.php?page=../../templates/includes/README_FILES.txt"
```

**Note**: Due to container isolation, you can only access files within the project. The container does not have access to host system files, making this demo safe for educational use.

### Step 4: Try a Non-Existent File

```bash
curl "http://127.0.0.1:8080/vulnerable.php?page=nonexistent.php"
```

This should display a friendly error message indicating the file does not exist.

### Step 5: View Other Demo Files

```bash
curl http://127.0.0.1:8080/vulnerable.php?page=contact.php
curl http://127.0.0.1:8080/vulnerable.php?page=help.php
```

## Browser-Based Demo

1. Navigate to `http://127.0.0.1:8080` in your browser
2. Click on the "About", "Contact", or "Help" links
3. Try entering different file names in the form (e.g., `about.php`, `contact.php`)
4. Attempt path traversal: `../../templates/includes/README_FILES.txt`
5. Try a non-existent file to see the error handling

## Project Structure

```
.
├── README.md                 # This file
├── docker-compose.yml        # Docker Compose configuration
├── Dockerfile               # Docker image definition
├── demo_commands.txt        # Exact commands for reproduction
├── screenshot_samples.txt   # Suggested screenshots for reports
└── templates/
    ├── index.php            # Homepage with navigation
    ├── vulnerable.php       # The vulnerable file inclusion handler
    └── includes/
        ├── about.php        # Demo file: About page
        ├── contact.php      # Demo file: Contact page
        ├── help.php         # Demo file: Help page
        └── README_FILES.txt # List of available demo files
```

## The Vulnerability

The vulnerable code is located in `templates/vulnerable.php`:

```php
// VULNERABLE CODE: Direct concatenation without sanitization
$file_path = __DIR__ . '/includes/' . $_GET['page'];
include $file_path;
```

This code directly concatenates user input (`$_GET['page']`) into a file path without any validation or sanitization, allowing attackers to use path traversal sequences (e.g., `../`) to access files outside the intended directory.

## Remediation

Here are several ways to fix the LFI vulnerability:

### 1. Whitelist Approach (Recommended)

Only allow specific, predefined files:

```php
$allowed = ['about.php', 'contact.php', 'help.php'];
$page = $_GET['page'] ?? '';

if (!in_array($page, $allowed)) {
    http_response_code(403);
    exit('Forbidden: Invalid page requested');
}

$file_path = __DIR__ . '/includes/' . $page;
include $file_path;
```

### 2. Use `basename()` to Prevent Path Traversal

Strip directory components from the filename:

```php
$page = basename($_GET['page'] ?? '');
$file_path = __DIR__ . '/includes/' . $page;

if (!file_exists($file_path)) {
    http_response_code(404);
    exit('File not found');
}

include $file_path;
```

### 3. Use `realpath()` with Validation

Validate that the resolved path is within the allowed directory:

```php
$page = $_GET['page'] ?? '';
$base_dir = __DIR__ . '/includes/';
$file_path = realpath($base_dir . $page);

if ($file_path === false || strpos($file_path, realpath($base_dir)) !== 0) {
    http_response_code(403);
    exit('Forbidden: Invalid file path');
}

include $file_path;
```

### 4. Configure `open_basedir` in PHP

Restrict PHP to only access files within a specific directory by setting `open_basedir` in `php.ini`:

```ini
open_basedir = /var/www/html
```

This provides an additional layer of protection at the PHP configuration level.

## Container Isolation

This demo is safe because:
- The container only binds to `127.0.0.1:8080` (localhost only)
- The container does not mount any host directories
- All files are contained within the Docker image
- Even if path traversal succeeds, it can only access files within the project

## Educational Use

This demo is designed for:
- Security courses and training
- Understanding LFI vulnerabilities
- Learning about secure coding practices
- Demonstrating the importance of input validation

## License

This project is provided for educational purposes only. Use at your own risk in isolated lab environments.

## Support

For questions or issues related to this demo, please refer to your course instructor or lab documentation.

