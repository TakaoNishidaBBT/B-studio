create or replace view %DB_PREFIX%v_article_tag as
select a.article_id
	  ,group_concat(b.node_id order by b.disp_seq asc separator ',') tag_id
	  ,group_concat(b.node_name order by b.disp_seq asc separator ',') tags
from %DB_PREFIX%article_tag a
	,%DB_PREFIX%v_tag b
where a.tag_id = b.node_id
group by article_id
