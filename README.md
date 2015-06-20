vagrant-lamp-symfony
====================

Vagrant Lamp Symfony blank project - just another way to quickly start a symfony project from scratch

====================
DEPENDENCIES

- install vagrant
- install virtual box
====================

Steps

1) git clone https://github.com/bizmate/vagrant-lamp-symfony.git
2) cd vagrant-lamp-symfony
3) vagrant up

====================

FACTS:

http connection on port 8889 by default
mysql can be accessed from your remote client on port 3334
mysql user name, password and db name are all set as "symfony"
symfony is installed in a /var/www/symfony
====================

KNOWN ISSUES
- remove sudo from steps
- move from using Execs to facts "have puppet manage the user name, or use a fact to discover it" for instance instead
of hardcoding www-data user, find apache user with a fact
- several execs do not check for conditions to be run, instead they run even if the step is not required again, for instance setfacl

