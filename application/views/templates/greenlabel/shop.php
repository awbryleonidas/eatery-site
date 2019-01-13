<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$pagename = array();
?>
<link rel="stylesheet" href="<?= base_url('assets/bootstrap-select-1.12.1/bootstrap-select.min.css') ?>">
<div class="inner-nav">
    <div class="container">
        <?= lang('home') ?> <span class="active"> > <?= lang('shop') ?></span>
    </div>
</div>
<div class="container" id="shop-page">
    <div class="row">
        <div class="col-md-3">
            <div class="categories">
                <div class="title">
                    <span><?= lang('categories') ?></span>
                    <?php if (isset($_GET['category']) && $_GET['category'] != '') { ?>
                        <a href="javascript:void(0);" class="clear-filter" data-type-clear="category" data-toggle="tooltip" data-placement="right" title="<?= lang('clear_the_filter') ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                    <?php } ?>
                </div>
                <div class="body">
                    <a href="javascript:void(0)" id="show-xs-nav" class="visible-xs visible-sm">
                        <span class="show-sp"><?= lang('showXsNav') ?><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i></span>
                        <span class="hidde-sp"><?= lang('hideXsNav') ?><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i></span>
                    </a>
                    <div id="nav-categories">

                        <?php

	                        foreach ($home_categories as $page) {
	                        $pagename[$page['id']] = $page['name'];
	                        }

                        function loop_tree($pages, $is_recursion = false)
                        {
                            ?>
                            <ul class="<?= $is_recursion === true ? 'children' : 'parent' ?>">
                                <?php
                                foreach ($pages as $page) {
                                    $pagename[$page['id']] = $page['name'];
                                    $children = false;
                                    if (isset($page['children']) && !empty($page['children'])) {
                                        $children = true;
                                    }
                                    ?>
                                    <li>
                                        <?php if ($children === true) {
                                            ?>
<!--                                            <i class="fa fa-chevron-right" aria-hidden="true"></i>-->
                                        <?php } else { ?>
<!--                                            <i class="fa fa-circle-o" aria-hidden="true"></i>-->
                                        <?php } ?>
                                        <a href="javascript:void(0);" data-category-id="<?= $page['id'] ?>" class="go-category left-side <?= isset($_GET['category']) && $_GET['category'] == $page['id'] ? 'selected' : '' ?>">
                                            <?= $page['name'] ?>
                                        </a>
                                        <?php
                                        if ($children === true) {
                                            loop_tree($page['children'], true);
                                        } else {
                                            ?>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                            <?php
                            if ($is_recursion === true) {
                                ?>
                                </li>
                                <?php
                            }
                        }

                        loop_tree($home_categories);
                        ?>
                    </div>
                </div>
            </div>
            <?php if ($showBrands == 1) { ?>
                <div class="filter-sidebar">
                    <div class="title">
                        <span><?= (isset($_GET['category']))? $_GET['category'] : 'hjhuihgu' ?></span>
                        <?php if (isset($_GET['brand_id']) && $_GET['brand_id'] != '') { ?>
                            <a href="javascript:void(0);" class="clear-filter" data-type-clear="brand_id" data-toggle="tooltip" data-placement="right" title="<?= lang('clear_the_filter') ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                        <?php } ?>
                    </div>
                    <ul>
                        <?php foreach ($brands as $brand) { ?>
                            <li>
                                <i class="fa fa-chevron-right" aria-hidden="true"></i> <a href="javascript:void(0);" data-brand-id="<?= $brand['id'] ?>" class="brand <?= isset($_GET['brand_id']) && $_GET['brand_id'] == $brand['id'] ? 'selected' : '' ?>"><?= $brand['name'] ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } if ($showOutOfStock == 1) { ?>
                <div class="filter-sidebar">
                    <div class="title">
                        <span><?= lang('store') ?></span>
                        <?php if (isset($_GET['in_stock']) && $_GET['in_stock'] != '') { ?>
                            <a href="javascript:void(0);" class="clear-filter" data-type-clear="in_stock" data-toggle="tooltip" data-placement="right" title="<?= lang('clear_the_filter') ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                        <?php } ?>
                    </div>
                    <ul>
                        <li>
                            <a href="javascript:void(0);" data-in-stock="1" class="in-stock <?= isset($_GET['in_stock']) && $_GET['in_stock'] == '1' ? 'selected' : '' ?>"><?= lang('in_stock') ?> (<?= $countQuantities['in_stock'] ?>)</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-in-stock="0" class="in-stock <?= isset($_GET['in_stock']) && $_GET['in_stock'] == '0' ? 'selected' : '' ?>"><?= lang('out_of_stock') ?> (<?= $countQuantities['out_of_stock'] ?>)</a>
                        </li>
                    </ul>
                </div>
            <?php } if ($shippingOrder != 0 && $shippingOrder != null) { ?>
                <div class="filter-sidebar">
                    <div class="title">
                        <span><?= lang('freeShippingHeader') ?></span>
                    </div>
                    <div class="error info">
                        <strong><?= lang('promo') ?></strong> - <?= str_replace(array('%price%', '%currency%'), array($shippingOrder, CURRENCY), lang('freeShipping')) ?>!
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-9 eqHeight" id="products-side">

            <h1><?= (isset($_GET['category']))? $pagename[$_GET['category']] : lang('products'); ?></h1>
<!--            <h1>--><?//= lang('products') ?><!--</h1>-->
            <?php
            if (!empty($products)) {
                $ctr = 1;
                foreach ($products as $product) {
                    ?>

                    <?php if ($ctr>3){?><br><?php }$ctr++;?>
                    <div class="col-sm-6 col-md-4 product-inner">
                        <a href="<?= LANG_URL . '/' . $product['url'] ?>">
                            <img src="<?= base_url('attachments/shop_images/' . $product['image']) ?>" alt="" class="img-responsive">
                        </a>
                        <h3><?= $product['title'] ?></h3>
                        <span class="price"><?= $product['price'] . CURRENCY ?></span>
                        <a class="add-to-cart" data-goto="<?= LANG_URL . '/checkout' ?>" href="javascript:void(0);" data-id="<?= $product['id'] ?>">
                            <?= lang('add_to_cart') ?>
                        </a>
                    </div>
                    <?php
                }
            } else {
                ?>
                <script>
                    $(document).ready(function () {
                        ShowNotificator('alert-info', '<?= lang('no_results') ?>');
                    });
                </script>
                <?php
            }
            ?>
        </div>
    </div>
    <?php if ($links_pagination != '') { ?>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <?= $links_pagination ?>
            </div>
        </div>
    <?php } ?>
</div>
<script src="<?= base_url('assets/bootstrap-select-1.12.1/js/bootstrap-select.min.js') ?>"></script>
