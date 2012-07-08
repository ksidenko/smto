echo -ne "dumping database...";
mysqldump smto_new -h localhost -u sks --password=qazqaz --ignore-table=smto_new.machine_data -r dump_13.10.2011.sql
mysqldump smto_new machine_data -h localhost -u sks --password=qazqaz --no-data >> dump_13.10.2011.sql

mysqldump smto_07.07.2012 -h localhost -u root --password=1 \
p2_auth_assignment \
p2_auth_item \
p2_auth_item_child \
p2_cell \
p2_config \
p2_file \
p2_html \
p2_info \
p2_log \
p2_page \
p2_user \
-r dump_p2.sql

echo -ne "end";