create or replace view %DB_PREFIX%v_w_form_field as
select *
from %DB_PREFIX%form_field a
where concat(a.version_id, a.revision_id) = (
	select max(concat(b.version_id, b.revision_id))
	from %DB_PREFIX%form b
        ,%DB_PREFIX%v_current_version c
		,%DB_PREFIX%version d
	where a.contents_id=b.contents_id
	and b.version_id = d.version_id
	and ((b.version_id < c.working_version_id and b.revision_id < d.private_revision_id)
	or (b.version_id = c.working_version_id and b.revision_id <= d.private_revision_id))
	group by b.contents_id
)
