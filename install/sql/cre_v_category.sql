create or replace view  %DB_PREFIX%v_category as
select *
from %DB_PREFIX%category
where del_flag='0'
