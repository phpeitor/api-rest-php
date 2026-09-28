<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

$markdownFile = __DIR__ . '/README.md';
$markdown = is_file($markdownFile) ? file_get_contents($markdownFile) : false;

if ($markdown === false) {
    http_response_code(404);
    $renderedMarkdown = '<p>No se encontró el archivo de documentación.</p>';
} else {
    $environment = new Environment([
        'html_input' => 'strip',
        'allow_unsafe_links' => false,
    ]);
    $environment->addExtension(new CommonMarkCoreExtension());
    $environment->addExtension(new TableExtension());

    $converter = new MarkdownConverter($environment);
    $renderedMarkdown = $converter->convert($markdown)->getContent();
}
require __DIR__ . '/views/docs.php';
