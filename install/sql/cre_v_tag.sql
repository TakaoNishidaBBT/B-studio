create or replace view %DB_PREFIX%v_tag as
select *
from %DB_PREFIX%tag
where del_flag='0'
