<!-- BEGIN: main -->
<form action="" method="post">
	<table class="tab1">
		<tbody>
			<tr>
				<td width="40%"><strong>{LANG.setting_home_view}</strong></td>
				<td>
				<select name="home_view">
					<!-- BEGIN: home_view_loop -->
					<option value="{type_view}"{view_selected}>{name_view}</option>
					<!-- END: home_view_loop -->
				</select></td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_homesite}</strong></td>
				<td><input type="text" value="{DATA.homewidth}" style="width: 40px;" name="homewidth" /> x <input type="text" value="{DATA.homeheight}" style="width: 40px;" name="homeheight" /></td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_per_page}</strong></td>
				<td><input type="text" value="{DATA.per_page}" style="width: 40px;" name="per_page" /> {LANG.setting_per_note_home}</td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_per_row}</strong></td>
				<td>
				    <select name="per_row">
				        <!-- BEGIN: per_row -->
				        <option value="{PER_ROW.value}" {PER_ROW.selected}>{PER_ROW.value}</option>
				        <!-- END: per_row -->
				    </select>
				</td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_hometext}</strong></td>
				<td><input type="checkbox" value="1" name="active_showhomtext" {ck_active_showhomtext} /></td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_active_order}</strong></td>
				<td><input type="checkbox" value="1" name="active_order" {ck_active_order} /></td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_active_price}</strong></td>
				<td><input type="checkbox" value="1" name="active_price" {ck_active_price} id="active_price" /></td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_active_order_number}</strong></td>
				<td><input type="checkbox" value="1" name="active_order_number" {ck_active_order_number} id="active_order_number" /> {LANG.setting_active_order_number_note} </td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_active_payment}</strong></td>
				<td><input type="checkbox" value="1" name="active_payment" {ck_active_payment} id="active_payment" /> {LANG.setting_active_payment_note} </td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_compare}</strong></td>
				<td><input type="checkbox" value="1" name="show_compare" {ck_compare} /></td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_displays}</strong></td>
				<td><input type="checkbox" value="1" name="show_displays" {ck_displays}/></td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_money_all}</strong></td>
				<td>
				<select name="money_unit">
					<!-- BEGIN: money_loop -->
					<option value="{DATAMONEY.value}"{DATAMONEY.selected}>{DATAMONEY.title}</option>
					<!-- END: money_loop -->
				</select></td>
			</tr>
			<tr>
				<td><strong>{LANG.format_order_id}</strong></td>
				<td><input type="text" value="{DATA.format_order_id}" style="width: 100px;" name="format_order_id" /> {LANG.format_order_id_note}</td>
			</tr>
			<tr>
				<td><strong>{LANG.format_code_id}</strong></td>
				<td><input type="text" value="{DATA.format_code_id}" style="width: 100px;" name="format_code_id" /> {LANG.format_order_id_note}</td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_active_auto_check_order}</strong></td>
				<td><input type="checkbox" value="1" name="auto_check_order" {ck_auto_check_order} id="auto_check_order" /></td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_active_tooltip}</strong></td>
				<td><input type="checkbox" value="1" name="active_tooltip" {ck_active_tooltip} id="active_tooltip" /></td>
			</tr>
			<tr>
				<td><strong>{LANG.setting_show_product_code}</strong></td>
				<td><input type="checkbox" value="1" name="show_product_code" {ck_show_product_code} id="show_product_code" /></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center; padding:10px"><input type="submit" value="{LANG.save}" name="Submit1" /><input type="hidden" value="1" name="savesetting"></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- BEGIN: payment -->
<script type="text/javascript">
	var url_back = '{url_back}';
	var url_change_weight = '{url_change}';
	var url_active = '{url_active}'; 
</script>
<div style="background:#F0F0F0;padding:10px; font-weight:bold">
	{LANG.paymentcaption}
</div>
<table id="edit" class="tab1">
	<thead>
		<tr>
			<td width="50" align="center"><strong>{LANG.weight}</strong></td>
			<td><strong>{LANG.paymentname}</strong></td>
			<td><strong>{LANG.domain}</strong></td>
			<td align="center"><strong>{LANG.active}</strong></td>
			<td align="center"><strong>{LANG.function}</strong></td>
		</tr>
	</thead>
	<tbody>
		<!-- BEGIN: paymentloop -->
		<tr>
			<td align="center">{DATA_PM.slect_weight}</td>
			<td>{DATA_PM.paymentname}</td>
			<td>{DATA_PM.domain}</td>
			<td align="center"><input type="checkbox" name="{DATA_PM.payment}" id="{DATA_PM.payment}" {DATA_PM.active} onclick="ChangeActive(this,url_active)"/></td>
			<td align="center"><span class="edit_icon"><a href="{DATA_PM.link_edit}#edit">{LANG.edit}</a></span></td>
		</tr>
		<!-- END: paymentloop -->
	</tbody>
</table>
<!-- END: payment -->
<!-- BEGIN: main -->