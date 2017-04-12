<?php
/*
Plugin Name: Remove trash from head
Plugin URI:  https://github.com/ivannikitin-com/head-clean
Description: Функция удаляет всякую хрень из служебной области <head></head>
Version:     20170412
Author:      Evgeny
Author URI:  https://profiles.wordpress.org/karsky
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: 
Domain Path: /languages
*/

function krs_cleanup () {
	/*
	Display the link to the Windows Live Writer manifest file.
	see https://developer.wordpress.org/reference/functions/wlwmanifest_link/
	Удаляем ссылку на файл в ядре wlwmanifest.xml. Не будет взаимодействия с Windows Live Writer
	*/
    remove_action( 'wp_head', 'wlwmanifest_link' );
	/*
	Displays the XHTML generator that is generated on the wp_head hook.
	see https://developer.wordpress.org/reference/functions/wp_generator/
	Удаляем версию ВордПресс из области HEAD
	*/    
    remove_action( 'wp_head', 'wp_generator' );
	/*
	Display the link to the Really Simple Discovery service endpoint.
	see https://developer.wordpress.org/reference/functions/rsd_link/
	Удаляем ссылку на службу Really Simple Discovery
	*/  
    remove_action( 'wp_head', 'rsd_link' );
	/*
	Injects rel=shortlink into the head if a shortlink is defined for the current page.
	see https://developer.wordpress.org/reference/functions/wp_shortlink_wp_head/
	Удаляем поддержку коротких ссылок, их вывод
	*/ 
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
	/*
	Print the inline Emoji detection script if it is not already printed.
	see https://developer.wordpress.org/reference/functions/print_emoji_detection_script/
	see https://developer.wordpress.org/reference/functions/print_emoji_styles/
	Отключаем вывод стилей и скриптов Эмодзи
	*/ 
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
	/*
	Displays relational links for the posts adjacent to the current post for single post pages.
	see https://developer.wordpress.org/reference/functions/adjacent_posts_rel_link_wp_head/
	Отключаем ссылки для отдельных страниц записи на предыдущую и следующую запись
	*/ 
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
	/*
	Display the links to the general feeds.
	Display the links to the extra feeds such as category feeds.
	see https://developer.wordpress.org/reference/functions/feed_links/
	Отключаем ссылки фидов
	*/ 
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );

	/*
	Отключаем через фильтры собственно сам WP REST API
	*/ 
 	// Filters for WP-API version 1.x
    add_filter( 'json_enabled', '__return_false' );
    add_filter( 'json_jsonp_enabled', '__return_false' );

    // Filters for WP-API version 2.x
    add_filter( 'rest_enabled', '__return_false' );
    add_filter( 'rest_jsonp_enabled', '__return_false' );

	/*
	Adds the REST API URL to the WP RSD endpoint.
	see https://developer.wordpress.org/reference/functions/rest_output_rsd/
	Отключаем ссылки фидов
	*/ 
    remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );

	/*
	Outputs the REST API link tag into page header.
	see https://developer.wordpress.org/reference/functions/rest_output_link_wp_head/
	Удаляем ссылку на WP REST API
	*/
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );

  	/*
	Sends a Link header for the REST API.
	see https://developer.wordpress.org/reference/functions/rest_output_link_header/
	*/     
    remove_action( 'template_redirect', 'rest_output_link_header', 11 );

 	/*
	Отключаем фильтры куки связанные с REST API
	*/
	remove_action( 'auth_cookie_malformed', 'rest_cookie_collect_status' );
	remove_action( 'auth_cookie_expired', 'rest_cookie_collect_status' );
	remove_action( 'auth_cookie_bad_username', 'rest_cookie_collect_status' );
	remove_action( 'auth_cookie_bad_hash', 'rest_cookie_collect_status' );
	remove_action( 'auth_cookie_valid', 'rest_cookie_collect_status' );
	remove_filter( 'rest_authentication_errors', 'rest_cookie_check_errors', 100 );

 	/*
	Отключаем события связанные с REST API
	*/
	remove_action( 'init', 'rest_api_init' );
	remove_action( 'rest_api_init', 'rest_api_default_filters', 10, 1 );
	remove_action( 'parse_request', 'rest_api_loaded' );

  	/*
	Adds oEmbed discovery links in the website .
	see https://developer.wordpress.org/reference/functions/wp_oembed_add_discovery_links/
	Удаляем ссылки формата oEmbed
	*/   
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );   

  	/*
	Adds the necessary JavaScript to communicate with the embedded iframes.
	see https://developer.wordpress.org/reference/functions/wp_oembed_add_host_js/
	Удаляем специальный джаваскрипт oEmbed для работы с фрэймами (front-end / back-end)
	*/ 
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );

  	/*
	Registers the oEmbed REST API route.
	see https://developer.wordpress.org/reference/functions/wp_oembed_register_route/
	Удаляем регистрацию роутинга REST API
	*/
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );

  	/*
	Filters the given oEmbed HTML.
	see https://developer.wordpress.org/reference/functions/wp_oembed_register_route/
	Удаляем фльтр валидации oEmbed HTML
	*/
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

  	/* !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	Filters whether XML-RPC methods requiring authentication are enabled.
	see https://developer.wordpress.org/reference/hooks/xmlrpc_enabled/
	Отключаем возможность XML-RPC ( WordPress API ) удаленного управления ВП, например, с помощью других приложений на iPhone, iPad и устройств на Android.
	!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	*/
	add_filter( 'xmlrpc_enabled', '__return_false' );   
   
}
add_action( 'after_setup_theme', 'krs_cleanup', 999 );
