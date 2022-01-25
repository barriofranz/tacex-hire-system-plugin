<?php get_header() ?>

  <div class="thsp thsp_container"><!--  content wrapper -->

  <?php 
    $equipment = 'equipment';
    $args = array(
      'post_type' => 'product',
      'tax_query' => array(
        array(
          'taxonomy' => 'product_cat',
          'field'    => 'slug',
          'terms'    => $equipment,
        ),
      ),
    );
    $equipment_query = new WP_Query($args); 

    $excavator = 'excavator';
    $args = array(
      'post_type' => 'product',
      'tax_query' => array(
        array(
          'taxonomy' => 'product_cat',
          'field'    => 'slug',
          'terms'    => $excavator,
        ),
      ),
    );
    $excavator_query = new WP_Query($args);


    $product_terms = get_terms('product_cat');
  ?>
   
    <div class="headline_txt">
      <h1>Get Ready for a better Equipment Hire Experience</h1>
    </div>

    <div class="thsp_content">

      <div class="r1">
        <div class="subheadline_txt">
          <h2>jobsite location</h2>
        </div>
        <div>
          <input class="post_code" id="post_code" type="text" value placeholder="Enter Post Code" />
        </div>
      </div>

      <div class="r2">
        <div class="equipment_img">
          <img src="http://localhost/md/tacexhire.app.dev/wp-content/uploads/2021/08/backhaw.jpg" alt="excavator">
        </div>
      </div>

      <div class="r3">
        <div class="radio_items">
          <input type="radio" name="order_type" id="order_type_pickup">
          <label class="label" for="order_type_pickup">Pick-up <span>Pick-up address Hollards</span></label>
        </div>
        <div class="radio_items">
          <input type="radio" name="order_type" id="order_type_delivery">
          <label class="label" for="order_type_delivery">Delivery <span>Delivery Adelaide and Adelaide Hills</span></label>
        </div>
      </div>

      <div class="r4">
        <div class="select_title_txt">
          <h1>What to Hire</h1>
        </div>

        <div>
          <div id="multiselect" class="multiselect">
            <div id="select_box" class="select_box">
              <span>Select an option</span>
            </div>
            <div id="checkbox_group" style="display:none;">

              <?php 
                foreach ($product_terms as $product_term) {
                  wp_reset_query();
                  $args = array(
                    'post_type' => 'product',
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'tax_query' => array(
                      array(
                        'taxonomy' => 'product_cat',
                        'field' => 'slug',
                        'terms' => $product_term->slug,
                      ),
                    ),
                  );

                  $query = new WP_Query($args);
                  if ($query->have_posts()) { ?>
                    <div class="checkbox_list">
                      <h4 class="group_heading"><?php echo $product_term->name ?></h2>
                      <?php
                      while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="checkbox_items">
                          <label class="item_label" for="item-<?php echo $post->post_name ?>">
                            <input type="checkbox" id="item-<?php echo $post->post_name ?>" name="item-<?php echo $post->post_name ?>" />
                            <span><?php echo get_the_title() ?></span>
                            <span><?php echo get_post_meta($post->ID, '_custom_product_text_field', true); ?></span>
                            <span><?php echo get_post_meta($post->ID, '_custom_product_number_field', true); ?></span>
                            <span><?php echo get_post_meta($post->ID, '_custom_product_select_field', true); ?></span>
                            <span><?php echo get_post_meta($post->ID, '_custom_product_textarea', true); ?></span>
                          </label>
                        </div>
                      <?php 
                      endwhile;
                  } else { ?>
                    <div class="checkbox_list">
                      <h2 class="group_heading"><?php echo $product_term->name ?></h2>
                      <div class="checkbox_items">
                        <label>No product found.</label>
                      </div>
                  <?php } ?>
                  </div>
                <?php } 
              ?>

            </div>
          </div>
        </div>

        <div class="next_btn btn">
          <button id="proceed_btn" disabled="disabled">proceed</a>
        </div>
      </div>

    </div>

  </div><!--  end content wrapper -->

<?php get_footer() ?>