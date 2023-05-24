<?php

preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $_GET['url'], $matches);
$video_id = '';
if (isset($matches[0])) {
    $video_id =  $matches[0];
}
$video_link = "https://www.youtube.com/embed/" . $video_id;

$res = array(
    'id' => '',
    'url' => $_GET['url'],
    'type' => 'video',
    'version' => '1.0',
    'title' => $_GET['url'],
    'author' => '',
    'author_url' => '',
    'provider_name' => 'YouTube',
    'thumbnail_url' => $_GET['url'],
    'thumbnail_width' => 1280,
    'thumbnail_height' => 720,
    'html' => '<div><div style="left: 0; width: 100%; height: 0; position: relative; padding-bottom: 56.25%;"><iframe src="' . $video_link . '" style="top: 0; left: 0; width: 100%; height: 100%; position: absolute; border: 0;" allowfullscreen scrolling="no" allow="accelerometer *; clipboard-write *; encrypted-media *; gyroscope *; picture-in-picture *;"></iframe></div></div>',
    'cache_age' => 86400,
    'options' =>
    array(
        '_start' =>
        array(
            'label' => 'Start from',
            'value' => '',
            'placeholder' => 'ex.: 11, 1m10s',
        ),
        '_end' =>
        array(
            'label' => 'End on',
            'value' => '',
            'placeholder' => 'ex.: 11, 1m10s',
        ),
        '_cc_load_policy' =>
        array(
            'label' => 'Closed captions',
            'value' => false,
        ),
        'click_to_play' =>
        array(
            'label' => 'Hold load & play until clicked',
            'value' => false,
        ),
    ),
);

echo json_encode($res);
