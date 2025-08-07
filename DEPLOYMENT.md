# Moodle Deployment Guide

This guide will help you set up automatic deployment from GitHub to your live server using GitHub Actions.

## 🚀 Quick Setup

### Option 1: SSH Deployment (Recommended for VPS/Dedicated Servers)

#### 1. Generate SSH Key Pair

First, generate an SSH key pair for secure server access:

```bash
# Generate SSH key (replace 'your-email@example.com' with your email)
ssh-keygen -t rsa -b 4096 -C "your-email@example.com" -f ~/.ssh/moodle_deploy

# This creates two files:
# ~/.ssh/moodle_deploy (private key)
# ~/.ssh/moodle_deploy.pub (public key)
```

#### 2. Add Public Key to Server

Copy the public key to your server:

```bash
# Copy the public key content
cat ~/.ssh/moodle_deploy.pub

# SSH into your server and add the key to authorized_keys
ssh your-username@your-server.com
echo "YOUR_PUBLIC_KEY_CONTENT" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

#### 3. Set Up GitHub Secrets

Go to your GitHub repository → Settings → Secrets and variables → Actions, then add these secrets:

| Secret Name | Description | Example |
|-------------|-------------|---------|
| `SSH_PRIVATE_KEY` | Your private SSH key content | Copy content of `~/.ssh/moodle_deploy` |
| `SERVER_HOST` | Your server hostname or IP | `your-server.com` |
| `SERVER_USER` | SSH username | `your-username` |
| `SERVER_PATH` | Path where Moodle should be deployed | `/var/www/html/moodle` |
| `BACKUP_PATH` | Path for backups | `/var/www/backups` |
| `SITE_URL` | Your website URL | `https://your-domain.com` |

### Option 2: FTP Deployment (Recommended for Shared Hosting)

#### 1. Get FTP Credentials

Contact your hosting provider to get your FTP credentials:
- FTP Server hostname
- FTP Username
- FTP Password
- Server directory path

#### 2. Set Up GitHub Secrets

Go to your GitHub repository → Settings → Secrets and variables → Actions, then add these secrets:

| Secret Name | Description | Example |
|-------------|-------------|---------|
| `FTP_SERVER` | Your FTP server hostname | `your-domain.com` |
| `FTP_USERNAME` | Your FTP username | `your-ftp-username` |
| `FTP_PASSWORD` | Your FTP password | `your-ftp-password` |
| `SITE_URL` | Your website URL | `https://your-domain.com` |

#### 3. Configure Server Directory

The deployment is configured to upload files to `/public_html/learn/` directory. Ensure this directory exists on your server:

```bash
# Create the directory if it doesn't exist
mkdir -p /public_html/learn
```

For different hosting providers, the path might be:

```bash
# Example for cPanel hosting
/public_html/learn/

# Example for GoDaddy hosting
/learn/

# Example for Bluehost hosting
/public_html/learn/
```

### 4. Test Deployment

The deployment will automatically trigger when you push to the `main` branch. You can also manually trigger it:

1. Go to your GitHub repository
2. Click "Actions" tab
3. Select "Deploy to Live Server" (SSH) or "Deploy to Live Server (FTP)"
4. Click "Run workflow"

## 🔧 Advanced Configuration

### Local FTP Deployment

You can use the included FTP deployment script locally:

```bash
# Copy the example config
cp deploy-ftp.config.example deploy-ftp.config

# Edit the configuration
nano deploy-ftp.config

# Test deployment (dry run)
./scripts/deploy-ftp.sh --dry-run

# Deploy to server
./scripts/deploy-ftp.sh
```

### Environment-Specific Configurations

Create different configurations for different environments:

```bash
# Development
cp deploy-ftp.config.example deploy-ftp.dev.config
# Edit deploy-ftp.dev.config with dev server details

# Staging
cp deploy-ftp.config.example deploy-ftp.staging.config
# Edit deploy-ftp.staging.config with staging server details

# Production
cp deploy-ftp.config.example deploy-ftp.prod.config
# Edit deploy-ftp.prod.config with production server details
```

### Multiple Server Deployment

To deploy to multiple servers, create separate workflow files:

```yaml
# .github/workflows/deploy-ftp-dev.yml
name: Deploy to Development (FTP)

on:
  push:
    branches: [ develop ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
    - uses: actions/checkout@v4
    - uses: SamKirkland/FTP-Deploy-Action@v4.3.4
      with:
        server: ${{ secrets.FTP_DEV_SERVER }}
        username: ${{ secrets.FTP_DEV_USERNAME }}
        password: ${{ secrets.FTP_DEV_PASSWORD }}
        server-dir: ${{ secrets.FTP_DEV_SERVER_DIR }}
```

## 🔒 Security Best Practices

### 1. SSH Key Security (SSH Deployment)

- Use a dedicated SSH key for deployment
- Set proper permissions: `chmod 600 ~/.ssh/moodle_deploy`
- Never commit private keys to the repository
- Rotate keys regularly

### 2. FTP Security (FTP Deployment)

- Use SFTP when possible (more secure than FTP)
- Use strong, unique passwords
- Consider using SSH keys with SFTP
- Regularly change FTP passwords

### 3. Server Security

```bash
# For SSH deployments - Restrict SSH access
sudo nano /etc/ssh/sshd_config

# Add these lines:
PermitRootLogin no
PasswordAuthentication no
PubkeyAuthentication yes

# Restart SSH
sudo systemctl restart sshd
```

### 4. File Permissions

The deployment scripts automatically set proper permissions:

- Directories: 755
- Files: 644
- Executable files: 755

### 5. Backup Strategy

- Automatic backups before each deployment (SSH only)
- Manual backups for FTP deployments
- Backup retention policy
- Test backup restoration regularly

## 🚨 Troubleshooting

### SSH Deployment Issues

#### 1. SSH Connection Failed

```bash
# Test SSH connection manually
ssh -i ~/.ssh/moodle_deploy your-username@your-server.com

# Check SSH key permissions
chmod 600 ~/.ssh/moodle_deploy
chmod 644 ~/.ssh/moodle_deploy.pub
```

#### 2. Permission Denied

```bash
# Check server directory permissions
ls -la /var/www/html/moodle

# Fix ownership
sudo chown -R your-username:your-username /var/www/html/moodle
```

### FTP Deployment Issues

#### 1. FTP Connection Failed

```bash
# Test FTP connection manually
ftp your-ftp-server.com

# Check credentials and server details
# Verify server directory exists
```

#### 2. Upload Permission Denied

```bash
# Check FTP directory permissions
# Contact hosting provider for proper permissions
# Ensure directory exists on server
```

#### 3. File Transfer Errors

```bash
# Check file sizes (some hosts have limits)
# Verify server has enough space
# Check for special characters in filenames
```

### Common Issues

#### 1. Rsync Not Found (SSH)

```bash
# Install rsync on server
sudo apt-get update
sudo apt-get install rsync
```

#### 2. lftp Not Found (FTP)

```bash
# Install lftp locally
# Ubuntu/Debian: sudo apt-get install lftp
# macOS: brew install lftp
```

#### 3. Cache Issues

```bash
# Manually clear cache on server
cd /path/to/moodle
php admin/cli/purge_caches.php
```

### Debug Mode

Enable debug mode in the workflow:

```yaml
- name: Deploy to server
  run: |
    set -x  # Enable debug mode
    # ... deployment commands
```

## 📊 Monitoring

### GitHub Actions Monitoring

- Check Actions tab for deployment status
- Set up notifications for failed deployments
- Monitor deployment times

### Server Monitoring

```bash
# Check disk space
df -h

# Check backup directory (SSH)
ls -la /var/www/backups

# Monitor logs
tail -f /var/log/apache2/error.log
```

## 🔄 Rollback Procedure

### SSH Deployment Rollback

```bash
# Automatic rollback
./scripts/deploy.sh --rollback

# Manual rollback
ssh your-username@your-server.com
ls -la /var/www/backups/
cd /var/www/html/moodle
tar -xzf /var/www/backups/moodle-backup-YYYYMMDD-HHMMSS.tar.gz
php admin/cli/purge_caches.php
```

### FTP Deployment Rollback

For FTP deployments, you'll need to manually restore from a backup:

1. Download your backup from hosting control panel
2. Upload the backup files via FTP
3. Clear cache manually

## 📝 Customization

### Custom Exclusions

Edit the workflow file to exclude additional files:

```yaml
# SSH deployment
rsync -avz --delete \
  --exclude='.git/' \
  --exclude='.github/' \
  --exclude='config.php' \
  --exclude='moodledata/' \
  --exclude='your-custom-file.txt' \  # Add your exclusions
  ./ $SERVER_USER@$SERVER_HOST:$SERVER_PATH/

# FTP deployment
exclude: |
  **/.git*
  **/.git*/**
  **/node_modules/**
  **/.github/**
  **/config.php
  **/moodledata/**
  **/your-custom-file.txt  # Add your exclusions
```

### Pre/Post Deployment Hooks

Add custom commands before or after deployment:

```yaml
- name: Pre-deployment tasks
  run: |
    echo "Running pre-deployment tasks..."
    # Your custom commands here

- name: Deploy to server
  run: |
    # ... deployment commands

- name: Post-deployment tasks
  run: |
    echo "Running post-deployment tasks..."
    # Your custom commands here
```

## 🎯 Best Practices

1. **Always test in development first**
2. **Use meaningful commit messages**
3. **Monitor deployment logs**
4. **Keep backups organized**
5. **Document server-specific configurations**
6. **Regular security audits**
7. **Performance monitoring**
8. **Automated testing before deployment**

## 📞 Support

If you encounter issues:

1. Check the troubleshooting section
2. Review GitHub Actions logs
3. Test connection manually (SSH or FTP)
4. Verify server permissions
5. Check file paths and configurations

For additional help, refer to the Moodle documentation or create an issue in the repository. 