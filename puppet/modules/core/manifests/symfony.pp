class core::symfony {
		exec { 'create symfony project':
				cwd => '/var/www',
				command => '/usr/local/bin/composer create-project symfony/framework-standard-edition symfony "2.7.*" --prefer-dist --no-interaction -vvv',
				require => [ Exec["download_composer"], Package['mysql-server'], Package['php5-mysql'], Package['php5-cli'], Package['acl'] ],
				onlyif => [
						"/usr/bin/test ! -f /var/www/symfony/composer.json",
				##"/usr/local/bin/composer show -i | grep -c \"symfony/symfony\"",
						"/usr/bin/test ! -d /var/www/symfony"
				],
				timeout => 0
		}

		file { "/var/symfonyCache":
				ensure => "directory",
		}

		file { "/var/log/symfonyLogs":
				ensure => "directory",
		}

		/*file {'remove_default_logs':
				ensure => absent,
				path => '/var/www/symfony/app/logs',
				recurse => true,
				purge => true,
				force => true,
				require => Exec['create symfony project']
		}

		file {'remove_default_cache':
				ensure => absent,
				path => '/var/www/symfony/app/cache',
				recurse => true,
				purge => true,
				force => true,
				require => Exec['create symfony project']
		}*/

		file{'symfony_cache_link':
				path  => '/var/www/symfony/app/cache',
				ensure => link,
				target => '/var/symfonyCache',
				purge => true,
				force => true,
				require => [File[ '/var/symfonyCache'],Exec['create symfony project']]
		}

		file{'symfony_log_link':
				path  => '/var/www/symfony/app/logs',
				ensure => link,
				target => '/var/log/symfonyLogs',
				purge => true,
				force => true,
				require => [File[ '/var/log/symfonyLogs'], Exec['create symfony project']]
		}

/*
symfony permissions are tricky, we need to use ACL and set up permissions as described at

http://symfony.com/doc/current/book/installation.html

HTTPDUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
$ sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs
$ sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs

*/
		exec { 'clean_log_cache_dirs':
				cwd => '/var/www/symfony',
				command => 'sudo /bin/rm -rf app/cache/*;sudo /bin/rm -rf app/logs/*;',
				require => File['symfony_log_link', 'symfony_cache_link'] ,
				path => [ "/bin/", "/usr/bin"]
		}

		exec { 'set_symfony_permissions':
				cwd => '/var/www/symfony',
				command =>
						#'ls;HTTPDUSER=$(ps aux | grep -E \'[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx\' | grep -v root | head -1 | cut -d\  -f1);sudo /usr/bin/setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs;sudo /usr/bin/setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX app/cache app/logs',
						'/usr/bin/setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX -m u:vagrant:rwX app/cache app/logs;\
						/usr/bin/setfacl -dR -m u:www-data:rwX -m u:`whoami`:rwX -m u:vagrant:rwX app/cache app/logs',
				require => [ Package['acl'], Exec['clean_log_cache_dirs'] ] ,
				path => [ "/bin/", "/usr/bin"]
		}

		exec { 'create_symfony_acme_bundle':
				cwd => '/var/www/symfony',
				command =>
						'php app/console generate:bundle --namespace=Acme/Bundle/BlogBundle --bundle-name=AcmeBlogBundle --no-interaction --structure --dir=src',
				require =>  Exec['set_symfony_permissions'] ,
				onlyif => 'php app/console router:match /hello/name | grep -c "None of the routes match"',
				path => [ "/bin/", "/usr/bin"]
		}

		exec { 'create symfony db':
				unless  => '/usr/bin/mysql -uroot symfony',
				command => '/usr/bin/mysql -uroot -e "create database symfony;"',
				require => Service['mysql'],
		}

		exec { 'create symfony user':
				unless  => '/usr/bin/mysql -usymfony -psymfony symfony',
				command => '/usr/bin/mysql -uroot -e "\
              create user \'symfony\'@\'localhost\' identified by \'symfony\';\
              create user \'symfony\'@\'10.0.2.2\' identified by \'symfony\';\
              create user \'symfony\'@\'%\' identified by \'symfony\';\
              grant all privileges on symfony.* to \'symfony\'@\'localhost\' identified by \'symfony\';\
              grant all privileges on symfony.* to \'symfony\'@\'10.0.2.2\' identified by \'symfony\';\
              grant all privileges on symfony.* to \'symfony\'@\'%\' identified by \'symfony\';\
              flush privileges;"',
				require => Exec['create symfony db']
		}
}
