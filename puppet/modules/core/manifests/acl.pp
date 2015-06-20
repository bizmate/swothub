class core::acl {
		package { 'acl':
				ensure => 'present',
				require => Exec['apt-update'],
				notify  => Service['apache2']
		}
}