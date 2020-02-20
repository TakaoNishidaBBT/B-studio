create or replace view %DB_PREFIX%v_tag2 as
select *
from %DB_PREFIX%tag2
where del_flag='0'
