create or replace view %DB_PREFIX%v_tag3 as
select *
from %DB_PREFIX%tag3
where del_flag='0'
