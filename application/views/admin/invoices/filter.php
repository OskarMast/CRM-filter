<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$report_heading = '';
$report_heading_valid = '';
?>
<div class="panel_s">
    <div class="panel-body">
        <?php echo form_open($this->uri->uri_string() . ($this->input->get('filter_id') ? '?filter_id=' . $this->input->get('filter_id') : ''), "id=invoice_form_filter"); ?>
        <div class="_filters _hidden_inputs">
            <div class="row">
                <!-- start date time div -->
                <div class="col-md-2 form-group border-right" id="report-time">
                    <label for="months-report"><?php echo _l('invoice_data_date'); ?></label><br />
                    <select class="selectpicker" name="report_months" id="report_months" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                        <option value=""><?php echo _l('report_sales_months_all_time'); ?></option>
                        <option value="today"><?php echo _l('today'); ?></option>
                        <option value="this_week"><?php echo _l('this_week'); ?></option>
                        <option value="last_week"><?php echo _l('last_week'); ?></option>
                        <!-- <option value="this_month"><?php echo _l('this_month'); ?></option> -->
                        <option value="1"><?php echo _l('last_month'); ?></option>
                        <option value="this_year"><?php echo _l('this_year'); ?></option>
                        <option value="last_year"><?php echo _l('last_year'); ?></option>
                        <option value="3" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-2 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_three_months'); ?></option>
                        <option value="6" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-5 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_six_months'); ?></option>
                        <option value="12" data-subtext="<?php echo _d(date('Y-m-01', strtotime("-11 MONTH"))); ?> - <?php echo _d(date('Y-m-t')); ?>"><?php echo _l('report_sales_months_twelve_months'); ?></option>
                        <option value="custom"><?php echo _l('period_datepicker'); ?></option>
                    </select>
                    <?php
                    if ($report_months !== '') {
                        $report_heading .= ' for ' . _l('period_datepicker') . " ";
                        switch ($report_months) {
                            case 'today':
                                $report_heading .= _d(date('d-m-Y')) . " To " . _d(date('d-m-Y'));
                                break;
                            case 'this_week':
                                $report_heading .= _d(date('d-m-Y', strtotime('monday this week'))) . " To " . _d(date('d-m-Y', strtotime('sunday this week')));
                                break;
                            case 'last_week':
                                $report_heading .= _d(date('d-m-Y', strtotime('monday last week'))) . " To " . _d(date('d-m-Y', strtotime('sunday last week')));
                                break;
                                // case 'this_month':$report_heading.=_d(date('01-m-Y'))." To "._d(date('t-m-Y'));break;
                            case '1':
                                $report_heading .= _d(date('01-m-Y', strtotime('-1 month'))) . " To " . _d(date('t-m-Y', strtotime('-1 month')));
                                break;
                            case 'this_year':
                                $report_heading .= _d(date('01-01-Y')) . " To " . _d(date('31-12-Y'));
                                break;
                            case 'last_year':
                                $report_heading .= _d(date('01-01-Y', strtotime('-1 year'))) . " To " . _d(date('31-12-Y', strtotime('-1 year')));
                                break;
                            case '3':
                                $report_heading .= _d(date('01-m-Y', strtotime('-2 month'))) . " To " . _d(date('t-m-Y'));
                                break;
                            case '6':
                                $report_heading .= _d(date('01-m-Y', strtotime('-5 month'))) . " To " . _d(date('t-m-Y'));
                                break;
                            case '12':
                                $report_heading .= _d(date('01-m-Y', strtotime('-11 month'))) . " To " . _d(date('t-m-Y'));
                                break;
                            case 'custom':
                                $report_heading .= $report_from . " To " . $report_to;
                                break;
                            default:
                                $report_heading .= 'All Time';
                        }
                    }
                    ?>
                </div>
                <div id="date-range" class="col-md-4 <?php echo ($report_months != 'custom' ? 'hide' : '') ?> mbot15" id="date_by_wrapper">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="report_from" class="control-label"><?php echo _l('report_sales_from_date'); ?></label>
                            <div class="input-group date">
                                <input type="text" class="form-control datepicker" id="report_from" name="report_from" value="<?php echo htmlspecialchars($report_from); ?>" autocomplete="off">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar calendar-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 border-right">
                            <label for="report_to" class="control-label"><?php echo _l('report_sales_to_date'); ?></label>
                            <div class="input-group date">
                                <input type="text" class="form-control datepicker" id="report_to" name="report_to" autocomplete="off" onchange="dt_custom_view(this.value,'.table-invoices','report_to'); return false;">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar calendar-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end date time div -->
                <!--added for custom field-->
                <?php if (!empty($list_custom_field)) {
                    $cfs = get_custom_fields('invoice', "id in (" . implode(',', $list_custom_field) . ")");
                    foreach ($cfs as $cf) { ?>
                        <div class="col-md-2 border-right mbot15">
                            <label class="control-label"><?php echo $cf['name']; ?></label>
                            <?php
                            echo render_select(
                                "cf[$cf[id]][]",
                                si_lf_get_custom_field_values($cf['id']),
                                array('value', 'value'),
                                '',
                                (isset($selected_custom_fields[$cf['id']])
                                    ? $selected_custom_fields[$cf['id']]
                                    : ''
                                ),
                                array(
                                    'data-width' => '100%',
                                    'data-none-selected-text' => _l('leads_all'),
                                    'multiple' => true,
                                    'data-actions-box' => true,
                                    'onchange' => "dt_custom_view(this.value,'.table-invoices','cf');return false;"
                                ),
                                array(),
                                'no-mbot',
                                '',
                                false
                            ); ?>
                        </div>
                <?php }
                }
                ?>
                <!--ended for custom field-->
                <!-- start quatity total -->
                <div class="col-md-2 text-center1 border-right mbot15">
                    <label for="total-min" class="control-label"><?php echo _l('total_car_quantity_from'); ?></label>
                    <div class="input-group">
                        <input type="number" value="<?php isset($total_min) && !empty($total_min) ? $total_min : ''; ?>" min="0" class="form-control" id="total_min" name="total_min" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2 text-center1 border-right mbot15">
                    <label for="total-max" class="control-label"><?php echo _l('total_car_quantity_to'); ?></label>
                    <div class="input-group">
                        <input type="number" value="<?php isset($total_max) && !empty($total_max) ? $total_max : ''; ?>" min="0" class="form-control" id="total_max" name="total_max" autocomplete="off">
                    </div>
                </div>
                <!-- end quatity total -->
                <!--start status -->
                <div class="col-md-2 text-center1 border-right">
                    <label for="status" class="control-label"><?php echo _l('shipping_state'); ?></label>
                    <?php
                    echo render_select(
                        'statuses_[]',
                        $statuses,
                        array('id', 'name'),
                        '',
                        $selected_statuses,
                        array(
                            'data-width' => '100%',
                            'data-none-selected-text' => _l('leads_all'),
                            'multiple' => true,
                            'data-actions-box' => true,
                            'onchange' => "dt_custom_view(this.value,'.table-invoices','status'); return false;"
                        ),
                        array(),
                        'no-mbot',
                        'status',
                        false
                    );
                    ?>
                </div>
                <!--end status-->
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
