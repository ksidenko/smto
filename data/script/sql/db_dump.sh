echo -ne "dumping database...";
mysqldump smto -h localhost -uroot -p1 \
--ignore-table=smto.machine_data \
--ignore-table=smto.machine_data_old \
--ignore-table=smto.machine_data_old2 \
--ignore-table=smto.machine_data_old3 \
> dump_07.01.2013.sql

mysqldump smto -h localhost -uroot -p1 \
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
>> dump_p2_07.01.2013.sql

echo -ne "end";