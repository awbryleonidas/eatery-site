<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container">
    <?= purchase_steps(1, 2, 3) ?><br>
    <div class="alert alert-success"><?= lang('c_o_d_order_completed') ?></div>
    <h3>Order Summary</h3>
    <div class="container" id="shopping-cart">
		<?php
			if ($cartItems['array'] == null) {
				?>
                <div class="alert alert-info"><?= lang('no_products_in_cart') ?></div>
				<?php
			} else {
				?>
                <div class="table-responsive">
                    <table class="table table-bordered table-products">
                        <thead>
                        <tr>
                            <th><?= lang('product') ?></th>
                            <th><?= lang('title') ?></th>
                            <th><?= lang('quantity') ?></th>
                            <th><?= lang('price') ?></th>
                            <th><?= lang('total') ?></th>
                        </tr>
                        </thead>
                        <tbody>
						<?php foreach ($cartItems['array'] as $item) { ?>
                            <tr>
                                <td class="relative">
                                    <input type="hidden" name="id[]" value="<?= $item['id'] ?>">
                                    <input type="hidden" name="quantity[]" value="<?= $item['num_added'] ?>">
                                    <img class="product-image" src="<?= base_url('/attachments/shop_images/' . $item['image']) ?>" alt="">
                                </td>
                                <td><a href="<?= LANG_URL . '/' . $item['url'] ?>"><?= $item['title'] ?></a></td>
                                <td>
                                    <span class="quantity-num">
                                    <?= $item['num_added'] ?>
                                </span>
                                </td>
                                <td><?= $item['price'] . CURRENCY ?></td>
                                <td><?= $item['sum_price'] . CURRENCY ?></td>
                            </tr>
						<?php } ?>
                        <tr>
                            <td colspan="4" class="text-right"><?= lang('total') ?></td>
                            <td><?= $cartItems['finalSum'] . CURRENCY ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <a href="<?= LANG_URL ?>" class="btn btn-black pull-left go-shop">
					<?= lang('back_to_shop') ?>
                </a>
                <div class="clearfix"></div>
			<?php } ?>
    </div>
</div>