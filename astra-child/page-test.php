<?php 
get_header(); ?>
<style>
.test-product .row {
    width: 100%;
    display: flex;
}
.test-product .col-md-3 {
    width: 20% !important;
}
.test-product .col-md-9 {
    width: 80%;
}

.products-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.product {
    width: 23%;
    border: 1px solid #ddd;
    padding: 15px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.product-image {
    max-width: 100%;
    height: auto;
    margin-bottom: 10px;
}

.product-title {
    font-size: 18px;
    margin-bottom: 10px;
}

.product-price {
    font-size: 16px;
    margin-bottom: 15px;
    color: #28a745;
}

.add-to-cart {
    display: inline-block;
    padding: 8px 15px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
}

.add-to-cart:hover {
    background-color: #0056b3;
}

.custom-pagination ul {
    display: flex;
    justify-content: center;
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.custom-pagination ul li {
    margin: 0 5px;
}

.custom-pagination ul li a,
.custom-pagination ul li span {
    display: inline-block;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-decoration: none;
    color: #007bff;
    transition: all 0.3s ease;
}

.custom-pagination ul li a:hover,
.custom-pagination ul li span.current {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
}
ul.categories-list {
    margin: 4px 2px;
}
.products-title {
    width: 100%;
}


</style>

<style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }
    .tabs {
      display: flex;
      border-bottom: 2px solid #ccc;
    }
    .tab {
      padding: 10px 20px;
      cursor: pointer;
      border: 1px solid #ccc;
      border-bottom: none;
      background-color: #f9f9f9;
    }
    .tab.active {
      background-color: #fff;
      border-top: 2px solid #007BFF;
      color: #007BFF;
    }
    .tab-content {
      border: 1px solid #ccc;
      padding: 20px;
      display: none;
    }
    .tab-content.active {
      display: block;
    }
  </style>

<div class="container test-product">
    <div class="row">
        <!-- Left Side: Product Categories -->
        <div class="col-md-3">
            <?php get_sidebar(); ?>
        </div>
        <!-- Right Side: Products --> 
        <div class="col-md-9">      
            <div class="row">  
                <div class="right-product-price">                        
                    <select id="price-filter-dropdown" class="price-filter-dropdown">
                        <option value="">Filter by Price</option>
                        <option value="low_to_high">Low to High</option>
                        <option value="high_to_low">High to Low</option>
                    </select>
                </div>
            </div>  
            <div class="row">
                <div id="product-list" class="products-wrapper">
                    <?php echo do_shortcode('[simple_product_display]'); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <h1>Tabbed Interface</h1>
        <div class="tabs">
            <div class="tab active" data-tab="1"><a href="#tab1">Tab 1</a></div>
            <div class="tab" data-tab="2"><a href="#tab2">Tab 2</a></div>
            <div class="tab" data-tab="3"><a href="#tab3">Tab 3</a></div>
        </div>
        <div class="tab-contents">
            <div class="tab-content active" id="tab-1">Content for Tab 1</div>
            <div class="tab-content" id="tab-2">Content for Tab 2</div>
            <div class="tab-content" id="tab-3">Content for Tab 3</div>
        </div>
    </div>

</div>

<script>
    // JavaScript for Tab Functionality
    const tabs = document.querySelectorAll('.tab');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        // Remove active class from all tabs and contents
        tabs.forEach(t => t.classList.remove('active'));
        contents.forEach(c => c.classList.remove('active'));

        // Add active class to the clicked tab and its content
        tab.classList.add('active');
        document.getElementById(`tab-${tab.dataset.tab}`).classList.add('active');
      });
    });
  </script>
<?php get_footer(); 

?>