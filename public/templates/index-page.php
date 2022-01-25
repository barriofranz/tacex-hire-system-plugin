<?php get_header(); ?>


<div class="md-container">
    <div class="md-row">

        <div class="md-col-xs-12">
            <div class="md-tacex-page-title">
                JOBSITE LOCATION
            </div>
        </div>
        <div class="md-col-xs-12">
            <input type="text" class="md-postcode-input" placeholder="Enter Post Code">
        </div>

        <div class="md-col-xs-12">
            <div class="md-img-container">
                <img src="<?php  echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/img/img1.png' ?>">
            </div>
        </div>

        <div class="md-col-xs-12">
            <div class="radio-wrapper">
                <input type="radio" class="order-type-radio" name="orderType"> Pick-up
                <p>Pick-up address Hollards</p>
            </div>

            <div class="radio-wrapper">
                <input type="radio" class="order-type-radio" name="orderType"> Delivery
                <p>Delivery Adelaide and Adelaide Hills</p>
            </div>

        </div>

    </div>

    <div class="md-row md-row-section">

        <div class="md-col-xs-12">
            <div class="md-tacex-page-title2">
                What to Hire
            </div>
        </div>


        <div class="md-col-xs-12">
            <select class="md-item-select">
                <option>CAT 301.8C Mini Excavator</option>
                <option>CAT 401.8A Mini Excavator</option>
            </select>



        </div>

    </div>
</div>

<?php get_footer(); ?>
