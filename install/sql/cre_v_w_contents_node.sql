create or replace view %DB_PREFIX%v_w_contents_node as
select *
from %DB_PREFIX%contents_node a
where concat(a.version_id, a.revision_id) = (
	select max(concat(b.version_id, b.revision_id))
	from %DB_PREFIX%contents_node b
		,%DB_PREFIX%v_current_version c
		,%DB_PREFIX%version d
	where a.node_id=b.node_id
	and b.version_id = d.version_id
	and ((b.version_id < c.working_version_id and b.revision_id < d.private_revision_id)
	or (b.version_id = c.working_version_id and b.revision_id <= d.private_revision_id))
	group by node_id
)
