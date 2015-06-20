class core::composer {
	exec { "download_composer":
		cwd => '/var/www',
		command => "/usr/bin/curl -sS https://getcomposer.org/installer | /usr/bin/php; mv composer.phar /usr/local/bin/composer",
		creates => "/usr/local/bin/composer",
		require => Package['curl', 'git', 'php5-cli']
	}
}
