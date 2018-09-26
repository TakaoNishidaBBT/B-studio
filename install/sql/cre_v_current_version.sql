create or replace view %DB_PREFIX%v_current_version as
select b.version_id reserved_version_id
    ,b.publication_datetime_u
    ,if(c.version_id, c.version_id, d.version_id) current_version_id
    ,if(c.version_id, c.version, d.version) current_version
	,a.working_version_id
	,e.version working_version
	,e.private_revision_id revision_id
from %DB_PREFIX%current_version a
left join %DB_PREFIX%version b
on a.reserved_version_id = b.version_id
and b.publication_datetime_u > UNIX_TIMESTAMP()
left join %DB_PREFIX%version c
on a.reserved_version_id = c.version_id
and c.publication_datetime_u <= UNIX_TIMESTAMP()
left join %DB_PREFIX%version d
on a.current_version_id = d.version_id
left join %DB_PREFIX%version e
on a.working_version_id = e.version_id
