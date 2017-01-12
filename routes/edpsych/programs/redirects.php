<?php
/**
 * Programs Redirects
 */
$router->respond('GET', '/ASL/faq.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/asl/faq/', 301)->send();
});

$router->respond('GET', '/ASL/ASL-Placement-Assessment.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/asl/placement-assessment/', 301)->send();
});

$router->respond('GET', '/Courses/ASL.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/courses/asl/', 301)->send();
});

$router->respond('GET', '/CSPP/', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/cspp/', 301)->send();
});

$router->respond('GET', '/CSPP/default.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/cspp/', 301)->send();
});

$router->respond('GET', '/Foundations/default.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/foundations/', 301)->send();
});

$router->respond('GET', '/Foundations/', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/foundations/', 301)->send();
});

$router->respond('GET', '/Foundations/funding/default.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/foundations/funding/', 301)->send();
});

$router->respond('GET', '/Foundations/learning.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/foundations/learning/', 301)->send();
});

$router->respond('GET', '/Foundations/prelim.html', function($request, $response, $service, $app) {
    $response->redirectToURI($request->uri());
});

$router->respond('GET', '/Foundations/social-psych.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/foundations/social-psych/', 301)->send();
});

$router->respond('GET', '/Foundations/certificate/Talent.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/foundations/talent/', 301)->send();
});

$router->respond('GET', '/Foundations/certificate/5135.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/foundations/5135/', 301)->send();
});

$router->respond('GET', '/CSPP/handbooks/', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/current/cspp/handbooks/', 301)->send();
});

$router->respond('GET', '/QME/', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/qme/', 301)->send();
});

$router->respond('GET', '/QME/default.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/qme/', 301)->send();
});

$router->respond('GET', '/QME/handbook.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/current/qme/handbook/', 301)->send();
});

$router->respond('GET', '/QME/job-placement.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/qme/job-placement/', 301)->send();
});

$router->respond('GET', '/QME/minor.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/qme/minor/', 301)->send();
});

$router->respond('GET', '/QME/stats-ed-courses.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/qme/stats-ed/courses/', 301)->send();
});

$router->respond('GET', '/QME/stats-intro.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/qme/stats-intro/', 301)->send();
});

$router->respond('GET', '/Graduate-Minor.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/graduate-minor/', 301)->send();
});

$router->respond('GET', '/SchoolPsych/students.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/students/', 301)->send();
});

$router->respond('GET', '/SchoolPsych/focus.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/focus/', 301)->send();
});

$router->respond('GET', '/SchoolPsych/diversity.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/diversity/', 301)->send();
});

$router->respond('GET', '/SchoolPsych/outcomes.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/outcomes/', 301)->send();
});

$router->respond('GET', '/FutureStudents/pridefellow.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/pride-fellow/', 301)->send();
});

$router->respond('GET', '/FutureStudents/master.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/master/', 301)->send();
});

$router->respond('GET', '/FutureStudents/doctor.html', function($request, $response, $service, $app) {
    $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/doctor/', 301)->send();
});

$router->respond('GET', '/minor/curriculum.html', function($request, $response, $service, $app) {
    $response->redirectToURI($request->uri());
});
