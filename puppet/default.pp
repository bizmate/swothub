node default {

	exec { 'apt-update':
		command => '/usr/bin/apt-get update',
	}

	include core::acl
	include core::apache2
  include core::mysql
  include core::git
	include core::curl
	include core::php5
	include core::symfony
	include core::composer

	if $mode == 'dev' {
		include dev::vim
		include dev::xdebug
	  notice("Running advanced xdebug config")
      dev::xdebug::config { 'default':
          profiler_output_name => 'xdebug.log',
          remote_connect_back => 1,
          remote_enable => 1,
          remote_port => 9000,
          max_nesting_level => 250
      }
	}
}