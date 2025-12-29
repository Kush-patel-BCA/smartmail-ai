#!/bin/bash

# SmartMail AI - Quick Start Script

echo "🚀 Starting SmartMail AI..."
echo ""

# Check if PHP is available
if ! command -v php &> /dev/null; then
    echo "❌ PHP not found in PATH"
    echo "Please install PHP or use MAMP/XAMPP"
    echo ""
    echo "For macOS: brew install php"
    echo "Or use MAMP: https://www.mamp.info/"
    exit 1
fi

# Get the directory where script is located
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd "$DIR"

# Check PHP version
PHP_VERSION=$(php -r 'echo PHP_VERSION;')
echo "✅ PHP Version: $PHP_VERSION"
echo ""

# Start PHP built-in server
echo "📧 SmartMail AI is starting..."
echo "🌐 Open your browser: http://localhost:8000"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

php -S localhost:8000

