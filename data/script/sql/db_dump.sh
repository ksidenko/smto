echo -ne "dumping database...";
mysqldump smto_new -h localhost -u sks --password=qazqaz --ignore-table=smto_new.machine_data -r dump_13.10.2011.sql
mysqldump smto_new machine_data -h localhost -u sks --password=qazqaz --no-data >> dump_13.10.2011.sql
echo -ne "end";