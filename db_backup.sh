#!/bin/bash
########################
# Variables
########################
dest="/path/to/backup"
archive_file=$(date +%Y-%m-%d_%H-%M-%S)

########################
# Generic dump function
########################
function genmysql () {
/usr/bin/nice -n15 /usr/bin/ionice -c3 /usr/bin/mysqldump -hlocalhost --quick --add-drop-table -u$db_user -p$db_pass $db_ \
| gzip -c > $dest/$file\_$archive_file.sql.gz
}

##########
# example.com
##########
db_user='example_user';
db_pass='example_password';
db_='example_db';
file='example_output_file_prefix';
genmysql

##########
# next.com
##########
db_user='next_user';
db_pass='next_password';
db_='next_db';
file='next_output_file_prefix';
genmysql
