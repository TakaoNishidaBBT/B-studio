<?php
//config
$rss_config = 
array(
	'start_html'	=> '<?xml version="1.0" encoding="UTF-8"?>
						<rdf:RDF
						xmlns="http://purl.org/rss/1.0/"
						xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
						xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
						xmlns:dc="http://purl.org/dc/elements/1.1/"
						xml:lang="ja">',
	'end_html'		=> '</rdf:RDF>',

	'select_sql'	=> "select * from " . BC_DB_PREFIX . "v_article3 where publication = '1' ",

	'header'	=>
	array(
		array(
			'name'			=> 'channel',
			'start_html'	=> '<channel>',
			'end_html'		=> '</channel>',
			array(
				'start_html'	=> '<title>',
				'end_html'		=> '</title>',
				'name'			=> 'site_title',
			),
			array(
				'start_html'	=> '<link>',
				'end_html'		=> '</link>',
				'name'			=> 'url',
			),
			array(
				'start_html'	=> '<description>',
				'end_html'		=> '</description>',
				'name'			=> 'description',
			),
			array(
				'start_html'	=> '<dc:date>',
				'end_html'		=> '</dc:date>',
				'name'			=> 'date',
			),
			array(
				'start_html'	=> '<dc:language>',
				'end_html'		=> '</dc:language>',
				'name'			=> 'language',
			),
			array(
				'start_html'	=> '<items>',
				'end_html'		=> '</items>',
				'class'			=> 'BC_PlaceHolder',
				'name'			=> 'header_item',
			),
		),
	),

	'row'		=>
	array(
		array(
			'name'			=> 'item',
			'start_html'	=> '<item>',
			'end_html'		=> '</item>',
			array(
				'name'			=> 'title',
				'start_html'	=> '<title>',
				'end_html'		=> '</title>',
			),
			array(
				'name'			=> 'article_url',
				'start_html'	=> '<link target="_blank">',
				'end_html'		=> '</link>',
			),
			array(
				'start_html'	=> '<description>',
				'end_html'		=> '</description>',
			),
			array(
				'start_html'	=> '<dc:creator>',
				'end_html'		=> '</dc:creator>',
				'value'			=> 'creator',
			),
			array(
				'start_html'	=> '<dc:date>',
				'end_html'		=> '</dc:date>',
				'name'			=> 'article_date',
			),
			array(
				'class'			=> 'BC_Data',
				'name'			=> 'article_id',
			),
			array(
				'class'			=> 'BC_Data',
				'name'			=> 'update_datetime',
			),
			array(
				'class'			=> 'BC_Data',
				'name'			=> 'article_date_u',
			),
			array(
				'class'			=> 'BC_Data',
				'name'			=> 'description_flag',
			),
			array(
				'class'			=> 'BC_Data',
				'name'			=> 'url',
			),
			array(
				'class'			=> 'BC_Data',
				'name'			=> 'external_link',
			),
		),
	),
);

$item_config = 
array(
	'start_html'	=> '<rdf:Seq>',
	'end_html'		=> '</rdf:Seq>',

	'select_sql'	=> "select * from " . BC_DB_PREFIX . "v_article3 where publication = '1' ",
	'row'		=>
	array(
		array(
			'name'			=> 'item',
		),
		array(
			'class'			=> 'BC_Data',
			'name'			=> 'article_id',
		),
		array(
			'class'			=> 'BC_Data',
			'name'			=> 'description_flag',
		),
		array(
			'class'			=> 'BC_Data',
			'name'			=> 'url',
		),
		array(
			'class'			=> 'BC_Data',
			'name'			=> 'external_link',
		),
	),
);