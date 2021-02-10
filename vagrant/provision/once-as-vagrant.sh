#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Import script args ==

github_token=$(echo "$1")
domain_frontend=$(echo "$2")
domain_backend=$(echo "$3")
admin_pass=$(echo "$4")

#== Provision script ==

info "Provision-script user: `whoami`"

info "Configure composer"
composer config --global github-oauth.github.com ${github_token}
echo "Done!"

info "Install project dependencies"

cd /app
#== В первую очередь запускаем update. Что бы получить последние версии пакетов. ==#
#== Upgrade your existing composer.lock at first ==#
composer --no-progress update
#== После обновления запускаем триггер установки, что бы сработали события ожидающие именно установку.  ==#
#== After the composer update, we start the events waiting for the installation ==#
composer --no-progress install

info "Init project. Run init script."
./init --env=Development --overwrite=y


info "Create bash-alias 'app' for vagrant user"
echo 'alias app="cd /app"' | tee /home/vagrant/.bash_aliases

info "Enabling colorized prompt for guest console"
sed -i "s/#force_color_prompt=yes/force_color_prompt=yes/" /home/vagrant/.bashrc
