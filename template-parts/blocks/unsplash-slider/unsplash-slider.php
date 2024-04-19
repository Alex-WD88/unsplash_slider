<?php

/**
 * Unsplash Slider Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Добавление скриптов и стилей для Swiper
function enqueue_swiper_assets() {
	// Подключение стилей Swiper
	wp_enqueue_style('swiper-style', 'https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css');

	// Подключение скриптов Swiper
	wp_enqueue_script('swiper-script', 'https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js', array(), false, true);
}

add_action('wp_enqueue_scripts', 'enqueue_swiper_assets');


$text = get_field('block_title') ?: 'Your Title here...';
$description = get_field('block_description') ?: 'Description';
$search_keyword = get_field('search_keyword') ?: 'forest';
$image_count = get_field('image_count') ?: 5;
$orientation = get_field('orientation') ?: 'landscape';

$scopes = ['public', 'write_user'];
$search = $search_keyword;
$page = $image_count;
$per_page = 15;
$orientation = $orientation;

// Выполнение поиска фотографий по ключевому слову
// Проверяем, существует ли транзиент с результатами поиска
if ( false === ( $cached_photos = get_transient( 'unsplash_photos' ) ) ) {
	// Выполнение поиска фотографий по ключевому слову и сохранение результатов в транзиент
	$photos_search_result = Unsplash\Search::photos($search, $page, $per_page, $orientation);
	set_transient( 'unsplash_photos', $photos_search_result, 12 * HOUR_IN_SECONDS );
} else {
	// Использование кэшированных результатов
	$photos_search_result = $cached_photos;
}

// Получаем результаты поиска фотографий и преобразуем их в массив
//$photos_search_result = Unsplash\Search::photos($search, $page, $per_page, $orientation);
$photos_array = $photos_search_result->getResults();

// Создаем массив данных для Twig шаблона
$context = array(
	'photos' => $photos_array,
	'text' => $text,
	'description' => $description,
	);

// Рендеринг шаблона с использованием Twig
Timber::render('views/blocks/unsplash-slider.twig', $context);

?>

<div>
	<span class="testimonial-text"><?php echo $text; ?></span>
	<span class="testimonial-description"><?php echo $description; ?></span>
	<span class="testimonial-search_keyword"><?php echo $search_keyword; ?></span>
	<div class="testimonial-image_count"><?php echo $image_count; ?></div>
	<div class="testimonial-orientation"><?php echo $orientation; ?></div>
</div>
