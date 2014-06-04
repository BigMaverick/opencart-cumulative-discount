<?php

/**
 * OpenCart Ukrainian Community
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License, Version 3
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/copyleft/gpl.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email

 *
 * @category   OpenCart
 * @package    OCU Cumulative Discount
 * @copyright  Copyright (c) 2011 Eugene Lifescale (a.k.a. Shaman) by OpenCart Ukrainian Community (http://opencart-ukraine.tumblr.com)
 * @license    http://www.gnu.org/copyleft/gpl.html     GNU General Public License, Version 3
 * @version    $Id: catalog/model/shipping/ocu_ukrposhta.php 1.2 2011-12-11 22:34:40
 */



/**
 * @category   OpenCart
 * @package    OCU Cumulative Discount
 * @copyright  Copyright (c) 2011 Eugene Lifescale (a.k.a. Shaman) by OpenCart Ukrainian Community (http://opencart-ukraine.tumblr.com)
 * @license    http://www.gnu.org/copyleft/gpl.html     GNU General Public License, Version 3
 */

class ModelTotalOCUCumulativeDiscount extends Model {

    public function getTotal(&$total_data, &$total, &$taxes) {

        // Check dependencies
        if ($this->customer->isLogged() && $this->config->get('ocu_cumulative_discount_status') && count($this->config->get('ocu_cumulative_discount'))) {

            // Load relations
            $this->language->load('total/ocu_cumulative_discount');
            $this->load->model('account/order');
            $this->load->model('catalog/product');

            // Set variables
            $discount_percent = array();
            $order_total      = 0;
            $last_total       = 0;
            $first_total      = $total;


            // Get last customer orders
            $customer_orders = $this->model_account_order->getOrders();

            foreach ($customer_orders as $customer_order) {
                $order_info = $this->model_account_order->getOrder($customer_order['order_id']);
                if ($order_info) {
                    $order_total = $order_total + $order_info['total'];
                }
            }

            // Get cumulative discounts table
            foreach ($this->config->get('ocu_cumulative_discount') as $ocu_cumulative_discount) {

                // Module active
                if ($ocu_cumulative_discount['status'] == 1) {

                    // Last order total >= discount setting
                    if ($order_total >= $ocu_cumulative_discount['total']) {

                        // Set discount
                        $discount_percent[] = $ocu_cumulative_discount['percent'];
                    }
                }
            }

            if ($discount_percent) {

                // Get RAW prices & recalculate total
                foreach ($this->cart->getProducts() as $product) {

                    $price = 0;

                    // Product price
                    $product_info = $this->db->query("SELECT price FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product['product_id'] . "' LIMIT 1");

                    if ($product_info->num_rows) {
                        $price = $product_info->row['price'] - (($product_info->row['price']/100)*max($discount_percent));
                    }

                    // Product discounts
                    $discount_quantity = 0;

                    foreach ($this->session->data['cart'] as $key_2 => $quantity_2) {
                        $product_2 = explode(':', $key_2);

                        if ($product_2[0] == $product['product_id']) {
                            $discount_quantity += $quantity_2;
                        }
                    }

                    $product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product['product_id'] . "' AND customer_group_id = '" . (int)$this->customer->getCustomerGroupId() . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

                    if ($product_discount_query->num_rows) {

                        // Set discount if option active
                        if ($this->config->get('ocu_cumulative_discount_discount')) {
                            $price = $product_discount_query->row['price'] - (($product_discount_query->row['price']/100)*max($discount_percent));
                        } else {
                            $price = $product_discount_query->row['price'];
                        }
                    }


                    // Product specials
                    $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product['product_id'] . "' AND customer_group_id = '" . (int)$this->customer->getCustomerGroupId() . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

                    if ($product_special_query->num_rows) {

                        // Set discount if option active
                        if ($this->config->get('ocu_cumulative_discount_special')) {
                            $price = $product_special_query->row['price'] - (($product_special_query->row['price']/100)*max($discount_percent));
                        } else {
                            $price = $product_special_query->row['price'];
                        }
                    }

                    // Set total value
                    $last_total = $last_total + ($price * $product['quantity']);
                }


                if ($first_total != $last_total) {
                    // Return total data
                    $total_data[] = array(
                        'code'       => 'ocu_cumulative_discount',
                        'title'      => sprintf($this->language->get('text_ocu_cumulative_discount'), max($discount_percent) . '%'),
                        'text'       => $this->currency->format(max(0, $last_total)),
                        'value'      => max(0, $last_total),
                        'sort_order' => $this->config->get('ocu_cumulative_discount_sort_order')
                    );

                    $total = $last_total;
                } else {
                    $total = $first_total;
                }
            }
        }
    }
}
