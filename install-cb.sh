#!/bin/bash
# Installation script
# NOTE: Always verify software sources before installation

# Define variables upfront
MYSQL_USER="root"
MYSQL_PASS="pf6hCHwMXBJvZxuU8VDA7a"
MYSQL_HOST="localhost"
APACHE_CONF="/etc/apache2/sites-available/000-default.conf"
WWW_DIR="/var/www/html"

# Update package lists and switch to faster mirrors
sudo sed -i 's/deb.debian.org/cloudfront.debian.net/g' /etc/apt/sources.list
sudo apt-get update
sudo apt-get upgrade -y

# Install required packages
sudo apt-get install -y apache2 unzip net-tools mysql-server

# Add PHP repository and install PHP packages
sudo apt-get install -y software-properties-common
sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get update
sudo apt-get install -y php8.1 php8.1-mbstring php8.1-mysql libapache2-mod-php8.1

# Configure Apache
sudo cat > "$APACHE_CONF" << 'EOF'
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html

    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

# Enable Apache modules
sudo a2enmod rewrite
sudo systemctl restart apache2

# Configure MySQL
sudo mysql -u "$MYSQL_USER" -h "$MYSQL_HOST" <<EOF
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '$MYSQL_PASS';
CREATE DATABASE IF NOT EXISTS coinbase;
FLUSH PRIVILEGES;
EOF

# Deploy application
cd "$WWW_DIR" || exit
rm -f index.html
if [ -f "CB_1.8.zip" ]; then
    unzip -o CB_1.8.zip
    rm CB_1.8.zip
else
    echo "Error: CB_1.8.zip not found"
    exit 1
fi

# Import database schema if exists
if [ -f "test.sql" ]; then
    mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" -h "$MYSQL_HOST" coinbase < "test.sql"
else
    echo "Warning: test.sql not found"
fi

echo "Installation completed!"