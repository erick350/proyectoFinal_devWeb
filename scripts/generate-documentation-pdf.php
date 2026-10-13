<?php

use Dompdf\Dompdf;
use Dompdf\Options;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

require dirname(__DIR__).'/vendor/autoload.php';

$root = dirname(__DIR__);
$manuals = ['MANUAL_USUARIO.md', 'MANUAL_TECNICO_LARAVEL.md'];
$environment = new Environment;
$environment->addExtension(new CommonMarkCoreExtension);
$environment->addExtension(new TableExtension);
$converter = new MarkdownConverter($environment);
$html = <<<'HTML'
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 1.8cm 1.6cm; }
        body { color: #17211c; font: 10pt/1.45 "DejaVu Sans", sans-serif; }
        h1 { color: #105d39; font-size: 23pt; }
        h2 { color: #16794a; font-size: 16pt; margin-top: 22px; }
        h3 { font-size: 12pt; margin-top: 17px; }
        p, li { orphans: 3; widows: 3; }
        table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        thead { display: table-header-group; }
        th, td { border: 1px solid #cbd7ce; padding: 6px; vertical-align: top; }
        th { background: #e7f4ec; }
        pre { background: #f2f5f2; border: 1px solid #d8e0da; padding: 8px; white-space: pre-wrap; word-wrap: break-word; }
        code { font-family: "DejaVu Sans Mono", monospace; font-size: 9pt; }
        blockquote { border-left: 3px solid #16794a; color: #526158; margin-left: 0; padding-left: 12px; }
        a { color: #105d39; text-decoration: none; }
        .manual + .manual { page-break-before: always; }
    </style>
</head>
<body>
HTML;

foreach ($manuals as $manual) {
    $path = $root.DIRECTORY_SEPARATOR.$manual;
    $markdown = file_get_contents($path);
    if ($markdown === false) {
        throw new RuntimeException("No se pudo leer el manual: {$path}");
    }

    $html .= '<section class="manual">'.$converter->convert($markdown)->getContent().'</section>';
}

$options = new Options;
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isRemoteEnabled', false);
$options->setChroot($root);

$pdf = new Dompdf($options);
$pdf->loadHtml($html, 'UTF-8');
$pdf->setPaper('A4');
$pdf->render();

$outputPath = $root.DIRECTORY_SEPARATOR.'DOCUMENTACION_PROYECTO.pdf';
if (file_put_contents($outputPath, $pdf->output()) === false) {
    throw new RuntimeException("No se pudo escribir el documento PDF: {$outputPath}");
}

fwrite(STDOUT, "Documento generado: {$outputPath}".PHP_EOL);
