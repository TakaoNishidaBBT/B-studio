create or replace view %DB_PREFIX%v_category3 as
select *
from %DB_PREFIX%category3
where del_flag='0'
