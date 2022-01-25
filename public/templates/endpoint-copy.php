<?php get_header() ?>

  <div class="mdp_container"><!--  content wrapper -->

    <div class="flex justify-center bg-yellow-400 p-4"><!-- main heading -->
      <h1 class="m-0 p-0"><span>Get Ready for a better Equipment Hire Experience</span></h1>
    </div><!-- end main heading -->

    <div class="mx-auto max-w-screen-md pt-4 pb-16 text-gray-700">

      <div>
        <div class="bg-black mb-2 p-4">
          <h1 class="uppercase text-center text-white m-0 p-0">
            <span>jobsite location</span>
          </h1>
        </div>
        <div>
          <input class="w-full post_code" type="text" value placeholder="Enter Post Code" />
        </div>
      </div>

      <div>
        <div class="flex justify-center py-10">
          <img src="http://localhost/md/tacexhire.app.dev/wp-content/uploads/2021/08/backhaw.jpg" alt="excavator">
        </div>
      </div>

      <div class="pb-8 border-b-4 border-solid border-yellow-400">
        <div class="bg-yellow-50 border-2 border-gray-600 border-solid py-4 px-8 mb-2">
          <input class="cursor-pointer w-5 h-5 mr-2" type="radio" name="order_type" id="order_type_pickup">
          <label class="cursor-pointer text-3xl" for="order_type_pickup">Pick-up <span class="block italic text-sm text-gray-400 ml-7">Pick-up address Hollards</span></label>
        </div>
        <div class="bg-yellow-50 border-2 border-gray-600 border-solid py-4 px-8">
          <input class="cursor-pointer w-5 h-5 mr-2" type="radio" name="order_type" id="order_type_delivery">
          <label class="cursor-pointer text-3xl" for="order_type_delivery">Delivery <span class="block italic text-sm text-gray-400 ml-7">Delivery Adelaide and Adelaide Hills</span></label>
        </div>
      </div>

      <div>
        <div class="mt-10 mb-6">
          <h1 class="text-center m-0 p-0">
            <span>What to Hire</span>
          </h1>
        </div>

        <div class="flex justify-center">
          <select class="select_item w-full p-4 rounded cursor-pointer appearance-none" name="order_item" id="order_item">
            <option value="0">Select Item</option>
            <option value="1">CAT 301.8C Mini Excavator</option>
            <option value="2">CAT 401.8A Mini Excavator</option>
          </select>
        </div>
      </div>

    </div>

  </div><!--  end content wrapper -->

<?php get_footer() ?>