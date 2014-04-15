create or replace view  %DB_PREFIX%v_article2 as
select 	  a.*
		, concat(a.article_date_u, a.article_id) article_date
		, b.node_name category
from %DB_PREFIX%article2 a
left join %DB_PREFIX%v_category2 b
on a.category_id = b.node_id
where a.del_flag='0'
