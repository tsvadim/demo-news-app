#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Import script args ==

timezone=$(echo "$1")
domain_frontend=$(echo "$2")
domain_backend=$(echo "$3")
system_locale=$(echo "$4")

db_name="db2app"
db_name_test=${db_name}"_test"

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Config locale OS"
locale-gen ${system_locale}.UTF-8
sed -i 's/en_US/'${system_locale}'/g'  /etc/default/locale
cat << EOF > /etc/default/locale
LANG="${system_locale}.UTF-8"
LC_ALL="${system_locale}.UTF-8"
EOF
echo "Config locale Done!"

info "Prepare root password for MySQL"
debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password \"''\""
debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password \"''\""
echo "Done!"

info "Update OS software"
apt-get update
apt-get upgrade -y
apt -y install software-properties-common
add-apt-repository ppa:ondrej/php
apt-get update

info "Install additional software"
apt-get install -y php7.4-curl php7.4-cli php7.4-intl php7.4-mysqlnd php7.4-gd php7.4-fpm php7.4-mbstring php7.4-xml unzip nginx mysql-server-5.7 php.xdebug

info "Configure MySQL"
sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/mysql.conf.d/mysqld.cnf
mysql -uroot <<< "CREATE USER 'root'@'%' IDENTIFIED BY ''"
mysql -uroot <<< "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'"
mysql -uroot <<< "DROP USER 'root'@'localhost'"
mysql -uroot <<< "FLUSH PRIVILEGES"
echo "Done!"

info "Configure PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.4/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.4/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.4/fpm/pool.d/www.conf
cat << EOF > /etc/php/7.4/mods-available/xdebug.ini
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.remote_port=9000
xdebug.remote_autostart=1
EOF

echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Set frontend domain name is "${domain_frontend}
sed -i 's/frontendDomain/'${domain_frontend}'/g' /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Set backend domain name is "${domain_backend}
sed -i 's/backendDomain/'${domain_backend}'/g' /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Initailize databases for MySQL. Table:"${db_name}" Test table:"${db_name_test}
mysql -uroot <<< "DROP DATABASE IF EXISTS "${db_name}
mysql -uroot <<< "DROP DATABASE IF EXISTS "${db_name_test}
mysql -uroot <<< "CREATE DATABASE IF NOT EXISTS "${db_name}
mysql -uroot <<< "CREATE DATABASE IF NOT EXISTS "${db_name_test}
echo "Done!"


info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer