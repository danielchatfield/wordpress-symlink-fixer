# WordPress symlink fixer

## The problem

Symlinks are a great way to be able to test a WordPress plugin against multiple 
WordPress versions whilst in development. Unfortunately because of the way 
WordPress and PHP work things don't exactly go as planned when you do this.


## Installation

1. Put `symlink-fixer.php` into `wp-content/mu-plugins/`.
2. Profit

## Limitations

Doesn't work with 'register-activation-hook' as this does not use plugin_url()
