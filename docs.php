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
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Documentación de la API REST PHP." />
  <title>Documentación · API REST PHP</title>
  <link rel="stylesheet" href="assets/css/app.css" />
  <link rel="stylesheet" href="assets/css/docs.css" />
</head>
<body>
  <div class="shell docs-shell">
    <header class="topbar">
      <a class="brand" href="./"><span class="brand-mark" aria-hidden="true">A</span><span>api<span class="brand-muted">.rest</span></span></a>
      <a class="back-link" href="./">← Volver a la consola</a>
    </header>
    <main class="docs-main">
      <aside class="docs-sidebar">
        <span class="eyebrow">DOCUMENTACIÓN</span>
        <strong>API REST PHP</strong>
        <p>Referencia del proyecto, configuración y ejemplos de uso.</p>
        <a href="README.md" download>Descargar README.md <span aria-hidden="true">↓</span></a>
      </aside>
      <article class="markdown-content">
        <?= $renderedMarkdown ?>
      </article>
    </main>
    <footer><span>API REST PHP</span><span>Documentación renderizada desde README.md</span></footer>
  </div>
</body>
</html>
