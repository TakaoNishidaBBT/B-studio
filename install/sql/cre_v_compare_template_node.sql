create or replace view %DB_PREFIX%v_compare_template_node as
select *
from %DB_PREFIX%template_node a
where concat(a.version_id, a.revision_id) = (
	select max(concat(b.version_id, b.revision_id))
	from %DB_PREFIX%template_node b
		,%DB_PREFIX%compare_version c
		,%DB_PREFIX%version d
	where a.node_id=b.node_id
	and b.version_id = d.version_id
	and ((b.version_id < c.compare_version_id and b.revision_id < d.private_revision_id)
	or (b.version_id = c.compare_version_id and b.revision_id <= d.private_revision_id))
	group by node_id
)
