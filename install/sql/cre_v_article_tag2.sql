create or replace view %DB_PREFIX%v_article_tag2 as
select a.article_id
	  ,group_concat(b.node_id order by b.disp_seq asc separator ',') tag_id
	  ,group_concat(b.node_name order by b.disp_seq asc separator ',') tags
	  ,group_concat(b.slug order by b.disp_seq asc separator ',') slug
from %DB_PREFIX%article_tag2 a
	,%DB_PREFIX%v_tag2 b
where a.tag_id = b.node_id
group by article_id
