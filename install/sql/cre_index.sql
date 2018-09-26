create index idx_node_id on %PREFIX%contents_node (node_id);
create index idx_contents_id on %PREFIX%contents (contents_id);

create index idx_node_id on %PREFIX%template_node (node_id);
create index idx_contents_id on %PREFIX%template (contents_id);

create index idx_node_id on %PREFIX%resource_node (node_id);
create index idx_parent_node on %PREFIX%resource_node (parent_node);

create index idx_node_id on %PREFIX%widget_node (node_id);
create index idx_contents_id on %PREFIX%widget (contents_id);
