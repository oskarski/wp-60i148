#!/usr/bin/env bash

WP_USER=TDadmin
WP_PASS='[{Tit@nD3$ign}]'
WP_EMAIL=example@example.com

function log {
    echo -e "    \e[33m" [echo] "\e[0m" $1
}

function download_composer_and_wp_cli {
    log "Downloading Composer and WP CLI"
    curl -sS https://getcomposer.org/installer | php
    curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar wp-cli.phar
}

function prepare_project_dir {
    log "Preparing to download WordPress"
    rm -rf wp-includes wp-admin index.php license.txt readme.html ./*.php wp-content/plugins/aksimet wp-content/plugins/index.php wp-content/plugins/hello.php wp-content/themes/twenty* wp-content/themes/index.php wp-content/upgrade
}

function set_up_wordpress {
    log "Downloading WordPress Core"
    php wp-cli.phar core download --skip-content --force

    log "Creating config file"
    echo DB name:
    read db_name
    echo DB user:
    read db_user
    echo DB pass:
    read db_pass

    php wp-cli.phar config create --dbname=${db_name} --dbuser=${db_user}  --dbpass=${db_pass} --dbprefix='td_' --force
    php wp-cli.phar config set FS_METHOD direct --type=constant --add
    php wp-cli.phar config set DISALLOW_FILE_EDIT true --type=constant --raw --add
    php wp-cli.phar config set WP_DEBUG true --type=constant --raw --add
    php wp-cli.phar config set WP_CACHE false --type=constant --raw --add

    log "Creating Data Base"
    php wp-cli.phar db create

    log "Installing WordPress core"
    php wp-cli.phar core install --url=localhost/${PWD##*/} --title=${PWD##*/} --admin_user=${WP_USER} --admin_password=${WP_PASS} --admin_email=${WP_EMAIL} --skip-email
}

function set_up_theme {
    log "Running composer install"
    cd wp-content/themes/custom-theme
    php ../../../composer.phar install

    log "Installing node_modules"
    pwd
    cd front
    npm install
    log "Building styles and scripts"
    npm run build

    cd ..
    cp .env-sample .env -f
    cd ../../..
    cp .htaccess-sample .htaccess -f

    log "Activating theme"
    php wp-cli.phar theme activate custom-theme
}

function activate_plugins {
    log "Activating plugins"
    php wp-cli.phar plugin activate --all
    php wp-cli.phar plugin update --all
}

download_composer_and_wp_cli
prepare_project_dir
set_up_wordpress
set_up_theme
activate_plugins

log "Your project is set up and ready for developing. Remember to activate ACF PRO plugin, update .htaccess file and update wp-content/themes/custom_theme/.env file !"
log "Username: ${WP_USER}"
log "Password: ${WP_PASS}"