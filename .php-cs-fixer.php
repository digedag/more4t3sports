<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('Resources')
    ->exclude('Documentation')
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
return $config->setFinder($finder)
    ->setRules([
        '@Symfony' => true,
        'phpdoc_align' => false,
        'no_superfluous_phpdoc_tags' => false,
        'single_line_comment_spacing' => false,
        'global_namespace_import' => [
            'import_classes' => true, 'import_constants' => false, 'import_functions' => false
        ],
        'phpdoc_separation' => [
            'skip_unlisted_annotations' => true,
        ],
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays'],
        ]
    ])
    ->setLineEnding("\n")
;
