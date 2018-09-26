create or replace view %DB_PREFIX%v_c_widget as
select *
from %DB_PREFIX%widget a
where concat(a.version_id, a.revision_id) = (
	select max(concat(b.version_id, b.revision_id))
	from %DB_PREFIX%widget b
        ,%DB_PREFIX%v_current_version c
		,%DB_PREFIX%version d
	where a.contents_id=b.contents_id
	and b.version_id = d.version_id
	and ((b.version_id < c.current_version_id and b.revision_id < d.private_revision_id)
	or (b.version_id = c.current_version_id and b.revision_id <= d.private_revision_id))
	group by b.contents_id
)
