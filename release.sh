#!/bin/sh

# The script updates the Wordpress.org SVN repository after pushing
# the latest release from Github
# Credit: https://guh.me/how-to-publish-a-wordpress-plugin-from-github
# Semantic Versioning: http://semver.org/

BASE_DIR=`pwd`
TMP_DIR=$BASE_DIR/tmp

mkdir $TMP_DIR
svn co http://plugins.svn.wordpress.org/multisite-login-logos/ $TMP_DIR
cd $TMP_DIR/trunk
git clone --recursive https://github.com/prontotools/multisite-login-logos.git tmp
cp -r tmp/* .
rm -rf tmp
rm -rf .git*
version=`head -n 1 VERSION`
cd $TMP_DIR
svn add trunk/* --force
svn ci -m "Release $version"
svn cp trunk tags/$version
svn ci -m "Tagging version $version"
rm -rf $TMP_DIR
