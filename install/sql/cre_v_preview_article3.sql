create or replace view %DB_PREFIX%v_preview_article3 as
select 	  a.*
		, concat(a.article_date_u, a.article_id) article_date
		, b.path
		, b.node_name category
		, b.disp_seq
		, b.slug category_slug
		, b.color color
		, b.background_color background_color
		, b.icon_file icon_file
		, c.tag_id
		, c.tags
		, c.slug tags_slug
from %DB_PREFIX%article3 a
left join %DB_PREFIX%v_category3 b
on a.category_id = b.node_id
left join %DB_PREFIX%v_article_tag3 c
on a.article_id = c.article_id
where a.del_flag='0' and publication in ('1', '2')
