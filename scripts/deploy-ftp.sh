#!/bin/bash

# Moodle FTP Deployment Script
# This script deploys Moodle to a live server via FTP

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Function to show usage
show_usage() {
    echo "Usage: $0 [OPTIONS]"
    echo ""
    echo "Options:"
    echo "  -s, --server SERVER      FTP server hostname"
    echo "  -u, --username USER      FTP username"
    echo "  -p, --password PASS      FTP password"
    echo "  -d, --directory DIR      Server directory"
    echo "  -k, --key PATH           SSH key path (if using SFTP)"
    echo "  -d, --dry-run            Show what would be done without executing"
    echo "  --help                   Show this help message"
    echo ""
    echo "Environment variables:"
    echo "  FTP_SERVER               FTP server hostname"
    echo "  FTP_USERNAME             FTP username"
    echo "  FTP_PASSWORD             FTP password"
    echo "  FTP_SERVER_DIR           Server directory"
    echo ""
    echo "Examples:"
    echo "  $0 -s ftp.example.com -u user -p pass -d /public_html/moodle"
}

# Parse command line arguments
DRY_RUN=false
USE_SFTP=false

while [[ $# -gt 0 ]]; do
    case $1 in
        -s|--server)
            FTP_SERVER="$2"
            shift 2
            ;;
        -u|--username)
            FTP_USERNAME="$2"
            shift 2
            ;;
        -p|--password)
            FTP_PASSWORD="$2"
            shift 2
            ;;
        -d|--directory)
            FTP_SERVER_DIR="$2"
            shift 2
            ;;
        -k|--key)
            SSH_KEY_PATH="$2"
            USE_SFTP=true
            shift 2
            ;;
        --dry-run)
            DRY_RUN=true
            shift
            ;;
        --help)
            show_usage
            exit 0
            ;;
        *)
            print_error "Unknown option: $1"
            show_usage
            exit 1
            ;;
    esac
done

# Validate required variables
if [[ -z "$FTP_SERVER" || -z "$FTP_USERNAME" || -z "$FTP_PASSWORD" ]]; then
    print_error "Missing required variables. Please set FTP_SERVER, FTP_USERNAME, and FTP_PASSWORD"
    show_usage
    exit 1
fi

# Set default server directory if not provided
if [[ -z "$FTP_SERVER_DIR" ]]; then
    FTP_SERVER_DIR="/"
fi

# Function to execute command with dry-run support
execute() {
    if [[ "$DRY_RUN" == "true" ]]; then
        print_warning "DRY RUN: $1"
    else
        eval "$1"
    fi
}

# Function to create lftp script
create_lftp_script() {
    local script_file="/tmp/lftp_script_$$"
    
    cat > "$script_file" << EOF
set ssl:verify-certificate no
set ftp:ssl-allow no
set net:timeout 30
set net:max-retries 3
set net:reconnect-interval-base 5
set net:reconnect-interval-multiplier 1

open -u $FTP_USERNAME,$FTP_PASSWORD $FTP_SERVER
cd $FTP_SERVER_DIR

# Mirror the local directory to server
mirror --reverse --delete --verbose --exclude-glob .git* --exclude-glob .github* --exclude-glob config.php --exclude-glob moodledata* --exclude-glob *.log --exclude-glob *.sql --exclude-glob *.sqlite --exclude-glob temp* --exclude-glob cache* --exclude-glob vendor* --exclude-glob node_modules* --exclude-glob .DS_Store --exclude-glob *.tmp --exclude-glob *.bak --exclude-glob deploy.config* --exclude-glob DEPLOYMENT.md --exclude-glob scripts* . .

bye
EOF

    echo "$script_file"
}

# Function to create sftp script
create_sftp_script() {
    local script_file="/tmp/sftp_script_$$"
    
    cat > "$script_file" << EOF
cd $FTP_SERVER_DIR
put -r ./*
bye
EOF

    echo "$script_file"
}

# Main deployment function
deploy() {
    print_status "Starting Moodle FTP deployment..."
    
    if [[ "$USE_SFTP" == "true" ]]; then
        print_status "Using SFTP for deployment..."
        
        # Create SFTP script
        local script_file=$(create_sftp_script)
        
        # Deploy using SFTP
        if [[ -n "$SSH_KEY_PATH" ]]; then
            execute "sftp -i $SSH_KEY_PATH -b $script_file $FTP_USERNAME@$FTP_SERVER"
        else
            execute "sftp -b $script_file $FTP_USERNAME@$FTP_SERVER"
        fi
        
        # Clean up
        rm -f "$script_file"
    else
        print_status "Using FTP for deployment..."
        
        # Check if lftp is available
        if ! command -v lftp &> /dev/null; then
            print_error "lftp is not installed. Please install it first:"
            print_error "  Ubuntu/Debian: sudo apt-get install lftp"
            print_error "  macOS: brew install lftp"
            exit 1
        fi
        
        # Create lftp script
        local script_file=$(create_lftp_script)
        
        # Deploy using lftp
        execute "lftp -f $script_file"
        
        # Clean up
        rm -f "$script_file"
    fi
    
    print_success "FTP deployment completed successfully!"
    print_status "Your Moodle site should be available at your configured domain"
}

# Main execution
deploy 