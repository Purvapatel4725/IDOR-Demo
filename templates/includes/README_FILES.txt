===========================================
LFI Demo - Available Files
===========================================

This directory contains safe demo files for the Local File Inclusion (LFI) vulnerability demonstration.

Available Files:
----------------
1. about.php       - About page demo content
2. contact.php     - Contact page demo content (fake contact info)
3. help.php        - Help page demo content
4. README_FILES.txt - This file listing all available demo files

Security Note:
--------------
These files are intentionally included in the project to demonstrate LFI vulnerabilities.
All content is safe and contains no sensitive or real information.

Path Traversal Demo:
--------------------
You can attempt to read this file using path traversal:
  ?page=../../templates/includes/README_FILES.txt

However, due to container isolation, you can only access files within this project.
The container does not have access to host system files.

===========================================

