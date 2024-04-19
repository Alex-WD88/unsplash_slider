<?php

// Подключаем библиотеку Composer и инициализируем Timber
require_once get_template_directory() . '/vendor/autoload.php';
$timber = new \Timber\Timber();

// Инициализация клиента Unsplash
Unsplash\HttpClient::init([
    'applicationId' => 'r5GI0JxXfw1rcjXaxluFYMB04ldXHalr1PowDaiz-rM',
    'secret'        => 'Zc_f72Wt2cYiOz35xjYFyGifYXhH99rT03qcs9_-SdQ',
    'callbackUrl'   => 'https://unsplash.com/oauth/applications/590599/callback',
    'utmSource'     => 'image carousel'
]);

// Регистрация ACF блока для Gutenberg
function register_acf_block_types() {

    // Проверяем, что функция доступна.
    if (function_exists('acf_register_block_type')) {

        // Регистрируем блок рекомендаций.
        acf_register_block_type(array(
            'name'              => 'unsplash_slider',
            'title'             => __('Unsplash Slider'),
            'description'       => __('A custom slider block for Unsplash images.'),
            'render_template'   => 'template-parts/blocks/unsplash-slider/unsplash-slider.php',
            'category'          => 'formatting',
            'icon'              => 'images-alt2',
            'keywords'          => array('slider', 'unsplash'),
        ));
    }
}

add_action('acf/init', 'register_acf_block_types');