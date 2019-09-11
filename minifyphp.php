<?php 

require_once ('library/PhpMinify.php');

$options = array(
    PhpMinify::OPT_INPUT_PATH => dirname(__FILE__) . '/src/Frontend/HomeBundle/Controller',
    PhpMinify::OPT_OUTPUT_PATH => dirname(__FILE__) . '/target/src/Frontend/HomeBundle/Controller',
    PhpMinify::OPT_TRACK_MINIFIED => true,
    PhpMinify::OPT_STRIP_TABULATION => true
);


try {
    $minifier = new PhpMinify($options);
    $minifier->run();
} catch (PhpMinify_Exception $ex) {
    echo 'ERROR: ' . $ex->getMessage();
} 

$options = array(
    PhpMinify::OPT_INPUT_PATH => dirname(__FILE__) . '/src/Backend/AdminBundle/Controller',
    PhpMinify::OPT_OUTPUT_PATH => dirname(__FILE__) . '/target/src/Backend/AdminBundle/Controller',
    PhpMinify::OPT_TRACK_MINIFIED => true,
    PhpMinify::OPT_STRIP_TABULATION => true
);


try {
    $minifier = new PhpMinify($options);
    $minifier->run();
} catch (PhpMinify_Exception $ex) {
    echo 'ERROR: ' . $ex->getMessage();
} 

$options = array(
    PhpMinify::OPT_INPUT_PATH => dirname(__FILE__) . '/src/Backend/CustomerAdminBundle/Controller',
    PhpMinify::OPT_OUTPUT_PATH => dirname(__FILE__) . '/target/src/Backend/CustomerAdminBundle/Controller',
    PhpMinify::OPT_TRACK_MINIFIED => true,
    PhpMinify::OPT_STRIP_TABULATION => true
);


try {
    $minifier = new PhpMinify($options);
    $minifier->run();
} catch (PhpMinify_Exception $ex) {
    echo 'ERROR: ' . $ex->getMessage();
} 

$options = array(
    PhpMinify::OPT_INPUT_PATH => dirname(__FILE__) . '/src/Backend/CustomerBundle/Controller',
    PhpMinify::OPT_OUTPUT_PATH => dirname(__FILE__) . '/target/src/Backend/CustomerBundle/Controller',
    PhpMinify::OPT_TRACK_MINIFIED => true,
    PhpMinify::OPT_STRIP_TABULATION => true
);


try {
    $minifier = new PhpMinify($options);
    $minifier->run();
} catch (PhpMinify_Exception $ex) {
    echo 'ERROR: ' . $ex->getMessage();
} 

$options = array(
    PhpMinify::OPT_INPUT_PATH => dirname(__FILE__) . '/src/Backend/UserBundle/Controller',
    PhpMinify::OPT_OUTPUT_PATH => dirname(__FILE__) . '/target/src/Backend/UserBundle/Controller',
    PhpMinify::OPT_TRACK_MINIFIED => true,
    PhpMinify::OPT_STRIP_TABULATION => true
);


try {
    $minifier = new PhpMinify($options);
    $minifier->run();
} catch (PhpMinify_Exception $ex) {
    echo 'ERROR: ' . $ex->getMessage();
} 

?>