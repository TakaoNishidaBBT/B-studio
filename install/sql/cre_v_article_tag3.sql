create or replace view %DB_PREFIX%v_article_tag3 as
select a.article_id
	  ,group_concat(b.node_id order by b.disp_seq asc separator ',') tag_id
	  ,group_concat(b.node_name order by b.disp_seq asc separator ',') tags
from %DB_PREFIX%article_tag3 a
	,%DB_PREFIX%v_tag3 b
where a.tag_id = b.node_id
group by article_id
