<?php

require_once(dirname(__FILE__) . "/../../../wp-load.php");
require plugin_dir_path( __FILE__ ) . 'includes/booqable_lib.php';


require_once( dirname(__FILE__) . '/../../../wp-admin/includes/plugin.php');
require_once( dirname(__FILE__) . '/../../../wp-admin/includes/media.php');
require_once( dirname(__FILE__) . '/../../../wp-admin/includes/file.php');
require_once(dirname(__FILE__) . "/../../../wp-admin/includes/image.php");



$booq = new BooqableProductScript();
$booq->pullProducts();


class BooqableProductScript {

    public $product_categories;


    public function pullProducts()
    {

        $booqObj = new Booqable_lib;
        $booqObj->init();
        // echo "<pre>";print_r($booqObj);echo "</pre>";die();

        $this->initProductCategories();
        $productsOutput = $booqObj->getProducts();

        foreach ($productsOutput['mainArr'] as $key => $prodOut) {
            // if (count($prodOut->tags) > 0) {
            //
            // } else {
            //     continue;
            // }

            $prodData = [
                'author_id' => 3,
                'title' => $prodOut->name,
                'sku' => $prodOut->sku,
                'price' => ((int)$prodOut->flat_fee_price_in_cents / 100),
                'photo_url' => $prodOut->photo_url,
                'tags' => $prodOut->tags,
                'slug' => $prodOut->slug,
                'stock_count' => (int)$prodOut->stock_count,
                'products_count' => $prodOut->products_count,
                'booq_id' => $prodOut->id,
                'extra_information' => $prodOut->extra_information,
                'price_period' => $prodOut->price_period,
                'price_type' => $prodOut->price_type,
                'product_type' => $prodOut->product_type,
            ];

            echo "<pre>";print_r($prodData);echo "</pre>";
            $this->create_woo_product($prodData);

        }

    }



    public function create_woo_product( $data = null )
    {

        $post_title = sanitize_text_field( $data['title'] );
        $post_args = array(
            'post_author' => intval( $data['author_id'] ), // The user's ID
            'post_title' => $post_title, // The product's Title
            'post_type' => 'product',
            'post_status' => 'publish' // This could also be $data['status'];
        );
        $post_id = post_exists( $post_title, '', '', '', 'publish' );

        // If the post was created okay, let's try update the WooCommerce values.
        if ( ! empty( $post_id ) && function_exists( 'wc_get_product' ) ) {

        } else {
            $post_id = wp_insert_post( $post_args );
        }


        //
        $product = wc_get_product( $post_id );
        $product->set_sku( $data['sku'] ); // Generate a SKU with a prefix. (i.e. 'pre-123')
        $product->set_regular_price( $data['price'] ); // Be sure to use the correct decimal price.


        // // Set multiple category ID's.
        if (count($data['tags']) >0) {
            $cat_ids = [];
            foreach ($data['tags'] as $cat_slug) {
                // $this->product_categories
                $cat_ids[] = $this->product_categories[$cat_slug]->term_id;

            }
            // echo "<pre>";print_r($aaaa);echo "</pre>";die();
            $product->set_category_ids( $cat_ids );
        }

        $product->set_manage_stock(true);
        if( $data['stock_count'] == 0) {
            $product->set_stock_status('outofstock');
        }
        $product->set_stock_quantity($data['stock_count']);



        $product->save(); // Save/update the WooCommerce order object.

        update_post_meta($post_id, '_booq_products_count', $data['products_count']);
        update_post_meta($post_id, '_booq_id', $data['booq_id']);
        update_post_meta($post_id, '_booq_extra_information', $data['extra_information']);
        update_post_meta($post_id, '_booq_price_period', $data['price_period']);
        update_post_meta($post_id, '_booq_price_type', $data['price_type']);
        update_post_meta($post_id, '_booq_product_type', $data['product_type']);

		$url = $data['photo_url'];
		$desc = $data['slug'] . ' image from booqable';
		$image = media_sideload_image( $url, $post_id, $desc, 'id' );
		set_post_thumbnail( $post_id, $image );

    }

    public function uploadMedia($image_url){
    	$media = media_sideload_image($image_url,0);
    	$attachments = get_posts(array(
    	'post_type' => 'attachment',
    	'post_status' => null,
    	'post_parent' => 0,
    	'orderby' => 'post_date',
    	'order' => 'DESC'
    	));
    	return $attachments[0]->ID;
    }


    public function initProductCategories()
    {
        // since wordpress 4.5.0
        $args = array(
            'taxonomy'   => "product_cat",
            'number'     => $number,
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
            'include'    => $ids
        );
        $product_categories = get_terms($args);
        $prod_cat = [];
        foreach ( $product_categories as $pc) {
            $prod_cat[$pc->slug] = $pc;
        }

        $this->product_categories = $prod_cat;
    }
}

// $test = $booqObj->getCustomers();

// $testParam = [
//     'customer_id' => 'fe4ac8cb-0a01-4de4-a5ef-3b5eb0bbed1c',
//     "starts_at" => "2021-12-13 9:00",
//     "stops_at" => "2021-12-14 9:00",
//     "status" => "concept",
// ];
// $test = $booqObj->submitOrder($testParam);


// $test = $booqObj->getOrders();


// $orderId = '5a9b1431-ecfa-44e9-b913-36959fa1219f';
// $test = $booqObj->updateOrderToConcept($orderId);
// echo '<pre>';print_r( $test );echo '</pre>';die();
