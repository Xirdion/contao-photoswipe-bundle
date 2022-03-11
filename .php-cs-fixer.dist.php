<?php

$header = <<<'EOF'
This file is part of SowieSo contao-photoswipe-bundle

@copyright  Copyright (c) 2022, Ideenwerkstatt Sowieso GmbH & Co. KG
@author     Sowieso GmbH & Co. KG <https://sowieso.team>
@link       https://github.com/sowieso-web/contao-photoswipe-bundle
EOF;
$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
;

$rules = [
    '@Symfony' => true,
    '@Symfony:risky' => true,
    '@PHP80Migration' => true,
    '@PHP80Migration:risky' => true,
    '@PHP81Migration' => true,
    '@PHPUnit84Migration:risky' => true,
    'align_multiline_comment' => true,
    'array_indentation' => true,
    'array_syntax' => ['syntax' => 'short'],
    'combine_consecutive_issets' => true,
    'combine_consecutive_unsets' => true,
    'comment_to_phpdoc' => true,
    'compact_nullable_typehint' => true,
    'concat_space' => [
        'spacing' => 'one'
    ],
    'escape_implicit_backslashes' => true,
    'fopen_flags' => false,
    'fully_qualified_strict_types' => true,
    'general_phpdoc_annotation_remove' => [
        'annotations' => [
            'author',
            'expectedException',
            'expectedExceptionMessage',
        ],
    ],
    'header_comment' => ['header' => $header],
    'heredoc_to_nowdoc' => true,
    'linebreak_after_opening_tag' => true,
    'list_syntax' => ['syntax' => 'short'],
    'multiline_comment_opening_closing' => true,
    'multiline_whitespace_before_semicolons' => [
        'strategy' => 'new_line_for_chained_calls',
    ],
    'native_function_invocation' => [
        'include' => ['@compiler_optimized'],
    ],
    'no_alternative_syntax' => true,
    'no_binary_string' => true,
    'no_null_property_initialization' => true,
    'no_superfluous_elseif' => true,
    'no_superfluous_phpdoc_tags' => false,
    'no_unreachable_default_argument_value' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'ordered_class_elements' => true,
    'ordered_imports' => true,
    'php_unit_strict' => true,
    'phpdoc_add_missing_param_annotation' => true,
    'phpdoc_order' => true,
    'phpdoc_trim_consecutive_blank_line_separation' => true,
    'phpdoc_types_order' => [
        'null_adjustment' => 'always_last',
        'sort_algorithm' => 'none',
    ],
    'return_assignment' => true,
    'strict_comparison' => true,
    'strict_param' => true,
    'string_line_ending' => true,
    'void_return' => true,
    'yoda_style' => true,
];

$config = new \PhpCsFixer\Config();
$config
    ->setRules($rules)
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
;

return $config;
