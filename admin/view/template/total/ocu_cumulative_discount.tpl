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

 ?>

<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

        <table class="form">
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="ocu_cumulative_discount_status">
                <?php if ($ocu_cumulative_discount_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="ocu_cumulative_discount_sort_order" value="<?php echo $ocu_cumulative_discount_sort_order; ?>" size="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_special; ?></td>
            <td>
              <?php if (isset($ocu_cumulative_discount_special) && $ocu_cumulative_discount_special) { ?>
                <input type="checkbox" name="ocu_cumulative_discount_special" value="1" checked="checked" />
              <?php } else { ?>
                <input type="checkbox" name="ocu_cumulative_discount_special" value="1" />
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_discount; ?></td>
            <td>
              <?php if (isset($ocu_cumulative_discount_discount) && $ocu_cumulative_discount_discount) { ?>
                <input type="checkbox" name="ocu_cumulative_discount_discount" value="1" checked="checked" />
              <?php } else { ?>
                <input type="checkbox" name="ocu_cumulative_discount_discount" value="1" />
              <?php } ?>
            </td>
          </tr>
        </table>

        <table id="ocu_cumulative_discount" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_total; ?></td>
              <td class="left"><?php echo $entry_percent; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $ocu_cumulative_discount_row = 0; ?>
          <?php foreach ($ocu_cumulative_discounts as $ocu_cumulative_discount) { ?>
          <tbody id="ocu_cumulative_discount-row<?php echo $ocu_cumulative_discount_row; ?>">
            <tr>
              <td class="left"><input type="text" name="ocu_cumulative_discount[<?php echo $ocu_cumulative_discount_row; ?>][total]" value="<?php echo $ocu_cumulative_discount['total']; ?>" size="3" /></td>
              <td class="left"><input type="text" name="ocu_cumulative_discount[<?php echo $ocu_cumulative_discount_row; ?>][percent]" value="<?php echo $ocu_cumulative_discount['percent']; ?>" size="3" /></td>
              <td class="left">
                <select name="ocu_cumulative_discount[<?php echo $ocu_cumulative_discount_row; ?>][status]">
                  <?php if ($ocu_cumulative_discount['status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </td>
              <td class="left"><a onclick="$('#ocu_cumulative_discount-row<?php echo $ocu_cumulative_discount_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $ocu_cumulative_discount_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="3"></td>
              <td class="left"><a onclick="add_ocu_cumulative_discount();" class="button"><?php echo $button_add_discount; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var ocu_cumulative_discount_row = <?php echo $ocu_cumulative_discount_row; ?>;

function add_ocu_cumulative_discount() {
  html  = '<tbody id="ocu_cumulative_discount-row' + ocu_cumulative_discount_row + '">';
  html += '<tr>';
  html += '  <td class="left"><input type="text" name="ocu_cumulative_discount[<?php echo $ocu_cumulative_discount_row; ?>][total]" value="" size="3" /></td>';
  html += '  <td class="left"><input type="text" name="ocu_cumulative_discount[<?php echo $ocu_cumulative_discount_row; ?>][percent]" value="" size="3" /></td>';
  html += '  <td class="left">';
  html += '    <select name="ocu_cumulative_discount[<?php echo $ocu_cumulative_discount_row; ?>][status]">';
  html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
  html += '      <option value="0"><?php echo $text_disabled; ?></option>';
  html += '    </select>';
  html += '  </td>';
  html += '  <td class="left"><a onclick="$(\'#ocu_cumulative_discount-row' + ocu_cumulative_discount_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
  html += '</tr>';
  html += '</tbody>';

	$('#ocu_cumulative_discount tfoot').before(html);

	ocu_cumulative_discount_row++;
}
//--></script>
<?php echo $footer; ?>
