create or replace view  %DB_PREFIX%v_category2 as
select *
from %DB_PREFIX%category2
where del_flag='0'
