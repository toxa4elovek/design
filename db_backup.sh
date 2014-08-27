#!/bin/bash
########################
# Variables
########################
dest="/var/db/"
archive_file=$(date +%Y-%m-%d_%H-%M-%S)

########################
# Generic dump function
########################
function genmysql () {
/usr/bin/nice -n15 /usr/bin/ionice -c3 /usr/bin/mysqldump -hlocalhost --quick --verbose --add-drop-table -u$db_user -p$db_pass $db_ $ignore \
| gzip -c > $dest/$file\_$archive_file.sql.gz
}

##########
# example.com
##########
#db_user='root';
#db_pass='HboJFSaN';
#db_='newtutdesign';
#file='newtutdesign';
#ignore="--ignore-table=$db_.wp_posts";
#genmysql

##########
# next.com
##########
db_user='root';
db_pass='HboJFSaN';
db_='tutdesign';
file='tutdesign';
genmysql

##########
# dev
##########
#db_user='root';
#db_pass='HboJFSaN';
#db_='godesigner';
#file='godesigner';
#ignore="--ignore-table=$db_.sendemails";
#genmysql
