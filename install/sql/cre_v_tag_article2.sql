create or replace view %DB_PREFIX%v_tag_article2 as
select max(tag_id) tag_id, b.node_name tag_name, b.disp_seq
from %DB_PREFIX%article_tag2 a
left join %DB_PREFIX%tag2 b
on a.tag_id = b.node_id
group by tag_id