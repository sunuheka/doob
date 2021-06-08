#!/usr/bin/env bash

LOCAL_DB=`get_config_value 'local' false`

if [[ "$LOCAL_DB" = "True" ]]; then
	echo -e "\nLocal development set, creating database '${VVV_SITE_NAME}' (if it's not already there)"

	DB_HOST="127.0.0.1"
	DB_USER="root"
	DB_PASS="${DB_USER}"
	DB_NAME="${VVV_SITE_NAME}"

	mysql -u root --password=root -e "CREATE DATABASE IF NOT EXISTS \`${VVV_SITE_NAME}\`"
else
	DB_HOST=`get_config_value 'db_host' "127.0.0.1"`
	DB_USER=`get_config_value 'db_user' "${VVV_SITE_NAME}"`
	DB_PASS=`get_config_value 'db_pass'`

	DB_NAME=`get_config_value 'db_name' "${DB_USER}"`
fi

SITE_DOMAIN=''

for line in $(cat "$VM_DIR/provision/vvv-hosts"); do
	if [[ "#" != ${line:0:1} ]]; then
		SITE_DOMAIN=$line
		break
	fi
done

if [[ ! -z $SITE_DOMAIN ]]; then
	HTTP_PROTOCOL="http://"

	SSL=`get_config_value 'ssl' True`

	if [[ "$SSL" = "True" ]]; then
		HTTP_PROTOCOL="https://"
	fi

	mkdir -p $VVV_PATH_TO_SITE/provision/ssl/

	cd $VVV_PATH_TO_SITE/provision/ssl/

	if [[ ! -f $SITE_DOMAIN.cert ]]; then
		openssl req -newkey rsa:2048 -x509 -nodes -keyout $SITE_DOMAIN.key -new -out $SITE_DOMAIN.cert -subj /CN=$SITE_DOMAIN -reqexts SAN -extensions SAN -config <(cat /etc/ssl/openssl.cnf <(printf '[SAN]\nsubjectAltName=DNS:%s' $SITE_DOMAIN)) -sha256 -days 3650
	fi

	cd -

	noroot wp core config --dbhost=$DB_HOST --dbuser=$DB_USER --dbpass=$DB_PASS --dbname=$DB_NAME --force --extra-php <<PHP
define( 'WP_SITEURL', '${HTTP_PROTOCOL}${SITE_DOMAIN}' );
define( 'WP_HOME', '${HTTP_PROTOCOL}${SITE_DOMAIN}' );

define( 'WP_POST_REVISIONS', 3 );
PHP
fi
