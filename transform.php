<?php

require __DIR__ . '/vendor/autoload.php';
use Facebook\InstantArticles\Elements as Elements;
use Facebook\InstantArticles\Transformer as Transformer;


header("Content-type: application/json");

//Enable debugging:
$log = \Logger::getLogger('facebook-instantarticles-transformer');
$logConfig = array(
    'appenders' => array(
        'default' => array(
            'class' => 'LoggerAppenderEcho',
            'layout' => array(
                'class' => 'LoggerLayoutPattern',
                'params' => array(
                    'conversionPattern' => '%msg%n'
                )
            )
        )
    ),
    'rootLogger' => array(
        'appenders' => array('default')
    ),
);
$log->configure($logConfig);
$log->setLevel(LoggerLevel::getLevelDebug());

// Loads the rules content file
//$rules_file_content = file_get_contents("simple-rules.json", true);
//$wp_default_rules_file_content = file_get_contents("wp-default-rules.json", true);
$inputHtml = $_POST['input-html'];
$inputRules = $_POST['input-rules'];

if( !$inputHtml ){
    http_response_code(400);
    die(json_encode(array('error' => 'input-html was not provided in the POST params.')));
}

if( !$inputRules ){
    http_response_code(400);
    die(json_encode(array('error' => 'input-rules was not provided in the POST params.')));
}

//Validate JSON:
if( !json_decode($inputRules, true) ){
    http_response_code(400);
    die(json_encode(array('error' => 'input-rules was invalid JSON.')));
}

//TODO: Validate HTML?

// Instantiate Instant article
$instant_article = Elements\InstantArticle::create();

// Creates the transformer and loads the rules
$transformer = new Transformer\Transformer();
//$transformer->loadRules( $wp_default_rules_file_content );
$transformer->loadRules($inputRules);


//TODO: Print out HTML Errors
//TODO: Print out all loaded rules.
//TODO: Add checkbox to include default WP-plugin rules.
//TODO: Checkbox to Hide Empty Text Nodes

// Ignores errors on HTML parsing
libxml_use_internal_errors(true);
$document = new \DOMDocument();
$document->loadHTML($inputHtml);
libxml_use_internal_errors(false);

ob_start();
// Invokes transformer
$transformer->transform($instant_article, $document);
$transformerLog = ob_get_clean();

// Get errors from transformer
$warnings = $transformer->getWarnings();
//TODO: Print these somewhere?

//TODO: Print out the context at the end, right before converting it to HTML.

// Renders the InstantArticle markup format
$result = $instant_article->render();
$result = Mihaeu\HtmlFormatter::format($result);

$return = [];
$return['result'] = $result;
$return['log'] = $transformerLog;

echo json_encode($return);
