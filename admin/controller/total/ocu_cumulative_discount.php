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

class ControllerTotalOCUCumulativeDiscount extends Controller {

    private $error = array();

    public function index() {
        $this->language->load('total/ocu_cumulative_discount');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('total_ocu_cumulative_discount', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled']  = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');

        $this->data['entry_status']     = $this->language->get('entry_status');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['entry_percent']    = $this->language->get('entry_percent');
        $this->data['entry_total']      = $this->language->get('entry_total');
        $this->data['entry_discount']   = $this->language->get('entry_discount');
        $this->data['entry_special']    = $this->language->get('entry_special');

        $this->data['button_save']         = $this->language->get('button_save');
        $this->data['button_cancel']       = $this->language->get('button_cancel');
        $this->data['button_remove']       = $this->language->get('button_remove');
        $this->data['button_add_discount'] = $this->language->get('button_add_discount');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_total'),
            'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('total/total', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('total/ocu_cumulative_discount', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['discounts'] = array();

        if (isset($this->request->post['ocu_cumulative_discount'])) {
            $this->data['ocu_cumulative_discounts'] = $this->request->post['ocu_cumulative_discount'];
        } elseif ($this->config->get('ocu_cumulative_discount')) {
            $this->data['ocu_cumulative_discounts'] = $this->config->get('ocu_cumulative_discount');
        } else {
            $this->data['ocu_cumulative_discounts'] = array();
        }

        if (isset($this->request->post['ocu_cumulative_discount_status'])) {
            $this->data['ocu_cumulative_discount_status'] = $this->request->post['ocu_cumulative_discount_status'];
        } else {
            $this->data['ocu_cumulative_discount_status'] = $this->config->get('ocu_cumulative_discount_status');
        }

        if (isset($this->request->post['ocu_cumulative_discount_sort_order'])) {
            $this->data['ocu_cumulative_discount_sort_order'] = $this->request->post['ocu_cumulative_discount_sort_order'];
        } else {
            $this->data['ocu_cumulative_discount_sort_order'] = $this->config->get('ocu_cumulative_discount_sort_order');
        }

        if (isset($this->request->post['ocu_cumulative_discount_discount'])) {
            $this->data['ocu_cumulative_discount_discount'] = $this->request->post['ocu_cumulative_discount_discount'];
        } else {
            $this->data['ocu_cumulative_discount_discount'] = $this->config->get('ocu_cumulative_discount_discount');
        }

        if (isset($this->request->post['ocu_cumulative_discount_special'])) {
            $this->data['ocu_cumulative_discount_special'] = $this->request->post['ocu_cumulative_discount_special'];
        } else {
            $this->data['ocu_cumulative_discount_special'] = $this->config->get('ocu_cumulative_discount_special');
        }

        $this->template = 'total/ocu_cumulative_discount.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'total/ocu_cumulative_discount')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
