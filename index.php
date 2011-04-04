<?php
//index.php

require 'Slim/Slim.php';
require 'functions.php';

Slim::init();
Slim::get('/', function () {
    $mc = new Memcache();
    $mc->connect('localhost');
    if (false === ($data = $mc->get('data'))) {
	    $gmc = new GearmanClient;
        $gmc->addServer();
        $jobId = $gmc->doBackground('data', null);
        $url = Slim::urlFor(
            'status',
            array('jobId' => $jobId)
        );
        Slim::redirect($url);
    }
    $content = "<ul>";
    foreach ($data as $url => $title) {
        $content.= sprintf('<li><a href="%s">%s</a></li>',
                           htmlentities($url, ENT_QUOTES, 'utf-8'),
                           htmlentities($title, ENT_QUOTES, 'utf-8'));

    }
    $content.= "</ul>";
    echo layout($content);
})->name('/');

Slim::get('/status/:jobId', function($jobId) {
    $gmc = new GearmanClient;
    $gmc->addServer();
    $stat = $gmc->jobStatus($jobId);
    if (Slim::request()->isAjax()) {
        Slim::response()->header('Content-type', 'text/json');
        echo json_encode($stat);
    } else {
        $root = Slim::urlFor('/');
        $url = Slim::urlFor(
            'status',
            array('jobId' => $jobId, 'format' => 'json')
        );
        $content = <<<EOT
<h4>Please wait</h4>
<div id="progressBar"></div>
Status: <span id="status">Waiting</span>
<script type="text/javascript">
    var globals = {};
    globals.statusUrl = "$url";
    globals.returnUrl = "$root";
</script>
<script type="text/javascript" src="${root}progress.js"></script>
EOT;
        echo layout($content);
    }
})->name('status');

Slim::run();

?>
