<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 04 May 2014 12:41:32 GMT
 */

if( !defined( 'NV_MAINFILE' ) )	die( 'Stop!!!' );

// Categories
$sql = 'SELECT catid, parentid, lev, ' . NV_LANG_DATA . '_title AS title, ' . NV_LANG_DATA . '_alias AS alias, viewcat, numsubcat, subcatid, newday, form, numlinks, ' . NV_LANG_DATA . '_description AS description, inhome, ' . NV_LANG_DATA . '_keywords AS keywords, groups_view, cat_allow_point, cat_number_point, cat_number_product, image FROM ' . $db_config['prefix'] . '_' . $module_data . '_catalogs ORDER BY sort ASC';
$global_array_cat = nv_db_cache( $sql, 'catid', $module_name );

// Groups
$sql = 'SELECT groupid, parentid, cateid, lev, ' . NV_LANG_DATA . '_title AS title, ' . NV_LANG_DATA . '_alias AS alias, viewgroup, numsubgroup, subgroupid, ' . NV_LANG_DATA . '_description AS description, inhome, indetail, in_order, ' . NV_LANG_DATA . '_keywords AS keywords, numpro, image FROM ' . $db_config['prefix'] . '_' . $module_data . '_group ORDER BY sort ASC';
$global_array_group = nv_db_cache( $sql, 'groupid', $module_name );

// Lay ty gia ngoai te
$sql = 'SELECT code, currency, exchange, round FROM ' . $db_config['prefix'] . '_' . $module_data . '_money_' . NV_LANG_DATA;

$cache_file = NV_LANG_DATA . '_' . md5( $sql ) . '_' . NV_CACHE_PREFIX . '.cache';
if( ($cache = nv_get_cache( $module_name, $cache_file )) != false )
{
	$money_config = unserialize( $cache );
}
else
{
	$money_config = array();
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		$money_config[$row['code']] = array(
			'code' => $row['code'],
			'currency' => $row['currency'],
			'exchange' => $row['exchange'],
			'round' => $row['round'],
			'decimals' => $row['round'] > 1 ? $row['round'] : strlen( $row['round'] ) - 2,
			'is_config' => ($row['code'] == $pro_config['money_unit']) ? 1 : 0
		);
	}
	$result->closeCursor();

	$cache = serialize( $money_config );
	nv_set_cache( $module_name, $cache_file, $cache );
}

// Lay don vi khoi luong
$sql = 'SELECT code, title, exchange, round FROM ' . $db_config['prefix'] . '_' . $module_data . '_weight_' . NV_LANG_DATA;

$cache_file = NV_LANG_DATA . '_' . md5( $sql ) . '_' . NV_CACHE_PREFIX . '.cache';
if( ($cache = nv_get_cache( $module_name, $cache_file )) != false )
{
	$weight_config = unserialize( $cache );
}
else
{
	$weight_config = array();
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		$weight_config[$row['code']] = array(
			'code' => $row['code'],
			'title' => $row['title'],
			'exchange' => $row['exchange'],
			'round' => $row['round'],
			'decimals' => $row['round'] > 1 ? $row['round'] : strlen( $row['round'] ) - 2,
			'is_config' => ($row['code'] == $pro_config['weight_unit']) ? 1 : 0
		);
	}
	$result->closeCursor();

	$cache = serialize( $weight_config );
	nv_set_cache( $module_name, $cache_file, $cache );
}

// Lay dia diem
$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_location ORDER BY sort ASC';
$array_location = nv_db_cache( $sql, 'id', $module_name );

// Lay nha van chuyen
$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier WHERE status = 1 ORDER BY weight ASC';
$array_carrier = nv_db_cache( $sql, 'id', $module_name );

// Lay cua hang
$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_shops WHERE status = 1 ORDER BY weight ASC';
$array_shops = nv_db_cache( $sql, 'id', $module_name );

// Lay Giam Gia
$sql = 'SELECT did, title, begin_time, end_time, config FROM ' . $db_config['prefix'] . '_' . $module_data . '_discounts';
$cache_file = NV_LANG_DATA . '_' . md5( $sql ) . '_' . NV_CACHE_PREFIX . '.cache';
if( ($cache = nv_get_cache( $module_name, $cache_file )) != false )
{
	$discounts_config = unserialize( $cache );
}
else
{
	$discounts_config = array();
	$result = $db->query( $sql );
	while( $row = $result->fetch() )
	{
		$discounts_config[$row['did']] = array(
			'title' => $row['title'],
			'begin_time' => $row['begin_time'],
			'end_time' => $row['end_time'],
			'config' => unserialize( $row['config'] )
		);
	}
	$result->closeCursor();

	$cache = serialize( $discounts_config );
	nv_set_cache( $module_name, $cache_file, $cache );
}

/**
 * nv_currency_conversion()
 *
 * @param mixed $price
 * @param mixed $currency_curent
 * @param mixed $currency_convert
 * @param mixed $discount_id
 * @param mixed $number
 * @return
 */
function nv_currency_conversion( $price, $currency_curent, $currency_convert, $discount_id = 0, $number = 1 )
{
	global $pro_config, $money_config, $discounts_config;

	if( $currency_curent == $pro_config['money_unit'] )
	{
		$price = $price / $money_config[$currency_convert]['exchange'];
	}
	elseif( $currency_convert == $pro_config['money_unit'] )
	{
		$price = $price * $money_config[$currency_curent]['exchange'];
	}
	$price = $price * $number;


	$r = $money_config[$currency_convert]['round'];
	$decimals = nv_get_decimals( $currency_convert );

	if( $r > 1 )
	{
		$price = round( $price / $r ) * $r;
	}
	else
	{
		$price = round( $price, $decimals );
	}

	$f = 0;
	$discount_percent = 0;
	$discount_unit = '';
	$discount = 0;

	if( isset( $discounts_config[$discount_id] ) )
	{
		$_config = $discounts_config[$discount_id];
		if( $_config['begin_time'] < NV_CURRENTTIME and ($_config['end_time'] > NV_CURRENTTIME or empty( $_config['end_time'] )) )
		{
			foreach( $_config['config'] as $_d )
			{
				if( $_d['discount_from'] <= $number and $_d['discount_to'] >= $number )
				{
					$discount_percent = $_d['discount_number'];
					if( $_d['discount_unit'] == 'p' )
					{
						$discount_unit = '%';
						$discount = ($price * ($discount_percent / 100));
					}
					else
					{
						$discount_unit = ' ' . $pro_config['money_unit'];
						$discount = $discount_percent * $number;
					}

					if( $r > 1 )
					{
						$discount = round( $discount / $r ) * $r;
					}
					else
					{
						$discount = round( $discount, $decimals );
					}
					break;
				}
			}
		}
	}

	$return = array();
	$return['price'] = $price; // Giá sản phẩm chưa format
	$return['price_format'] = nv_number_format( $price, $decimals ); // Giá sản phẩm đã format

	$return['discount'] = $discount;// Số tiền giảm giá sản phẩm chưa format
	$return['discount_format'] = nv_number_format( $discount, $decimals ); // Số tiền giảm giá sản phẩm đã format
	$return['discount_percent'] = $discount_unit == '%' ? $discount_percent : nv_number_format( $discount_percent, $decimals );// Giảm giá theo phần trăm
	$return['discount_unit'] = $discount_unit;// Đơn vị giảm giá

	$return['sale'] = $price - $discount;// Giá bán thực tế của sản phẩm
	$return['sale_format'] = nv_number_format( $return['sale'], $decimals );// Giá bán thực tế của sản phẩm đã format
	$return['unit'] = $currency_convert;// Đơn vị của tiền tệ

	return $return;
}

/**
 * nv_number_format()
 *
 * @param mixed $number
 * @param integer $decimals
 * @return
 */

function nv_number_format( $number, $decimals = 0 )
{
	$str = number_format( $number, $decimals, ',', '.' );
	return $str;
}

/**
 * nv_get_decimals()
 *
 * @param mixed $currency_convert
 * @return
 */

function nv_get_decimals( $currency_convert )
{
	global $money_config;

	$r = $money_config[$currency_convert]['round'];
	$decimals = 0;

	if( $r <= 1 )
	{
		$decimals = $money_config[$currency_convert]['decimals'];
	}
	return $decimals;
}

/**
 * nv_weight_conversion()
 *
 * @param mixed $weight
 * @param mixed $weight_unit_curent
 * @param mixed $weight_unit_convert
 * @param integer $number
 * @return
 */
function nv_weight_conversion( $weight, $weight_unit_curent, $weight_unit_convert, $number = 1 )
{
	global $pro_config, $weight_config;

	if( $weight > 0 )
	{
		if( $weight_unit_curent == $pro_config['weight_unit'] )
		{
			$weight = $weight / $weight_config[$weight_unit_convert]['exchange'];
		}
		elseif( $weight_unit_convert == $pro_config['weight_unit'] )
		{
			$weight = $weight * $weight_config[$weight_unit_curent]['exchange'];
		}
		$weight = $weight * $number;

		$r = $weight_config[$weight_unit_convert]['round'];
		$decimals = 0;

		if( $r <= 1 )
		{
			$decimals = $weight_config[$weight_unit_convert]['decimals'];
		}

		if( $r > 1 )
		{
			$weight = round( $weight / $r ) * $r;
		}
		else
		{
			$weight = round( $weight, $decimals );
		}
	}

	return $weight;
}

/**
 * nv_shipping_price()
 *
 * @param mixed $weight
 * @param mixed $weight_unit
 * @param mixed $location_id
 * @param mixed $carrier_id
 * @param mixed $config_id
 * @return
 */
function nv_shipping_price( $weight, $weight_unit, $location_id, $shops_id, $carrier_id )
{
	global $db, $db_config, $module_data, $pro_config;

	$array_weight_config = array();
	$array_location_config = array();
	$price = array();

	$sql = 'SELECT config_id FROM ' . $db_config['prefix'] . '_' . $module_data . '_shops_carrier WHERE shops_id = ' . $shops_id . ' AND carrier_id = ' . $carrier_id;
	$result = $db->query( $sql );
	list( $config_id ) = $result->fetch( 3 );

	if( $config_id )
	{
		$sql = 'SELECT iid FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_location WHERE cid = ' . $config_id . ' AND lid = ' . $location_id;
				$result = $db->query( $sql );
				list( $iid ) = $result->fetch( 3 );

		if( $iid )
		{
			// Weight config
			$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_carrier_config_weight WHERE iid = ' . $iid;
			$result = $db->query( $sql );
			while( $array_weight = $result->fetch() )
			{
				$array_weight_config[$array_weight['iid']][] = $array_weight;
			}
		}
	}

	if( !empty( $array_weight_config ) )
	{
		foreach( $array_weight_config as $weight_config )
		{
			foreach( $weight_config as $config )
			{
				$config['weight'] = nv_weight_conversion( $config['weight'], $config['weight_unit'], $weight_unit );
				if( $weight <= $config['weight'] )
				{
					$price = nv_currency_conversion( $config['carrier_price'], $config['carrier_price_unit'], $pro_config['money_unit'] );
					break;
				}
				else
				{
					// Vuot muc gia cau hinh

				}
			}
		}
	}

	return $price;
}