<?php

class Product {
	
	public function getProducts($id = null, $supplier_id = null, $order = null, $term = null, $id_bu, $active = null) {

		$CI =& get_instance();
		$sqladd = '';

		$CI->db->select('p.id, s.id as supplier_id, p.supplier_reference, pc.name AS category_name, pc.color AS category_color, p.name AS name, s.name AS supplier_name, p.price, p.active, p.id_category, puprc.name AS unit_name, p.id_unit AS id_unit, p.packaging AS packaging, p.freq_inventory, p.comment, ps.mini AS stock_mini, ps.max AS stock_max, ps.qtty AS stock_qtty, ps.warning AS stock_warning, ps.last_update_user AS last_update_user, ps.last_update_pos AS last_update_pos, u.username AS last_update_user_name, p.manage_stock')->from('products AS p')->join('suppliers AS s', 'p.id_supplier = s.id')->join('products_unit AS puprc', 'p.id_unit = puprc.id')->join('products_category AS pc','p.id_category = pc.id','left')->join('products_stock AS ps','p.id = ps.id_product','right')->join('users AS u','ps.last_update_id_user = u.id','left');
		if($id) $sqladd = $CI->db->where('p.deleted', 0)->where('p.id', $id);
		if($active) $CI->db->where('p.active', true);
		if($supplier_id != null && $supplier_id != '%') $CI->db->where('p.deleted', 0)->where('s.id', $supplier_id);
		if($supplier_id != null && $supplier_id == '%') $CI->db->where('p.deleted', 0)->where('p.active', true);
		if($term != null){
			$array = array('p.name' => $term);
			$CI->db->where('p.deleted', 0)->like($array);
		}
		$CI->db->where('s.id_bu', $id_bu);
		$CI->db->where('s.deleted', 0);
		$CI->db->where('s.active', 1);
		$ordersql = "p.`active` DESC, pc. `name` DESC"; 
		if($order) $ordersql = $order; 


		$CI->db->order_by("$ordersql, p.id_supplier ASC")->limit(10000);
		$req = $CI->db->get() or die($this->mysqli->error);

		$ret = array();
		foreach ($req->result_array() as $key) {
			$ret[$key['id']] = $key;
		}
		return $ret;
	}
	
	public function isManaged($product_id) {
		
		$CI = & get_instance();
		
		$CI->db->select('manage_stock');
		$CI->db->from('products');
		$CI->db->where('id', $product_id);
		$row = $CI->db->get()->row_array();
		if ($row['manage_stock']) {
			return (true);
		}
		else {
			return (false);
		}
	}
	
	public function getProductHistory($pdt_id) 
	{
		$CI =& get_instance();
		$CI->db->select('sh.*, u.username, p.name');
		$CI->db->from('stock_history AS sh');
		$CI->db->join('users AS u', 'u.id = sh.id_user');
		$CI->db->join('products AS p', 'p.id = sh.id_product');
		$CI->db->where('sh.id_product', $pdt_id);
		$CI->db->where('p.manage_stock', '1');
		$CI->db->where('sh.deleted', '0');
		$CI->db->order_by('date_inv', 'DESC');
		$CI->db->order_by('id', 'DESC');
		$ret = $CI->db->get()->result_array();
		return ($ret);
	}
	
	public function countProductHistory($pdt_id) 
	{
		$hist = $this->getProductHistory($pdt_id);
		return (count($hist));
	}
	
	public function getProductsWithFilters($id= null, $order = null, $id_bu, $filters = null, $page = 1) 
	{
		$CI =& get_instance();
		$sqladd = '';
		
		$CI->db->select('p.id, s.id as supplier_id, p.supplier_reference, pc.name AS category_name, p.name AS name, s.name AS supplier_name, p.price, p.active, p.id_category, puprc.name AS unit_name, p.id_unit AS id_unit, p.packaging AS packaging, p.freq_inventory, p.comment, ps.mini AS stock_mini, ps.max AS stock_max, ps.qtty AS stock_qtty, ps.warning AS stock_warning, ps.last_update_user AS last_update_user, ps.last_update_pos AS last_update_pos, u.username AS last_update_user_name, p.manage_stock')->from('products AS p')->join('suppliers AS s', 'p.id_supplier = s.id')->join('products_unit AS puprc', 'p.id_unit = puprc.id')->join('products_category AS pc','p.id_category = pc.id','left')->join('products_stock AS ps','p.id = ps.id_product','right')->join('users AS u','ps.last_update_id_user = u.id','left');
		if (isset($filters) && !empty($filters)) {
			foreach ($filters as $key => $value) {
				if ($value != "" && $key != 'p.name' && $key != 'p.supplier_reference') {
					$CI->db->where($key, $value);
				}
			}
			if ($filters['p.name'] != "" ) {
				$CI->db->like('p.name', $filters['p.name']);
			}
			if ($filters['p.supplier_reference'] != "") {
				$CI->db->like('p.supplier_reference', $filters['p.supplier_reference']);
			}
		}
		if($id) $sqladd = $CI->db->where('p.deleted', 0)->where('p.id', $id);
		$CI->db->where('s.id_bu', $id_bu);
		$CI->db->where('s.deleted', 0);
		$CI->db->where('s.active', 1);
			
		$ordersql = "p.`active` DESC";
		if($order) $ordersql = $order; 


		$CI->db->order_by("$ordersql, p.id_supplier ASC");
		if ($page == 1) {
			$CI->db->limit(80);
		} else {
			$offset = 80 * ($page - 1);
			$CI->db->limit(80, $offset);
		}
		$req = $CI->db->get() or die($this->mysqli->error);
		$ret = array();
		foreach ($req->result_array() as $key) {
			$ret[$key['id']] = $key;
		}
		return $ret;
	}
	
	public function countProductsWithFilters($id= null, $order = null, $id_bu, $filters = null) {
		$CI =& get_instance();
		$sqladd = '';
		
		$CI->db->select('p.id, s.id as supplier_id, p.supplier_reference, pc.name AS category_name, p.name AS name, s.name AS supplier_name, p.price, p.active, p.id_category, puprc.name AS unit_name, p.id_unit AS id_unit, p.packaging AS packaging, p.freq_inventory, p.comment, ps.mini AS stock_mini, ps.max AS stock_max, ps.qtty AS stock_qtty, ps.warning AS stock_warning, ps.last_update_user AS last_update_user, ps.last_update_pos AS last_update_pos, u.username AS last_update_user_name, p.manage_stock')->from('products AS p')->join('suppliers AS s', 'p.id_supplier = s.id')->join('products_unit AS puprc', 'p.id_unit = puprc.id')->join('products_category AS pc','p.id_category = pc.id','left')->join('products_stock AS ps','p.id = ps.id_product','right')->join('users AS u','ps.last_update_id_user = u.id','left');
		if (isset($filters) && !empty($filters)) {
			foreach ($filters as $key => $value) {
				if ($value != "" && $key != 'p.name' && $key != 'p.supplier_reference') {
					$CI->db->where($key, $value);
				}
			}
			if ($filters['p.name'] != "" ) {
				$CI->db->like('p.name', $filters['p.name']);
			}
			if ($filters['p.supplier_reference'] != "") {
				$CI->db->like('p.supplier_reference', $filters['p.supplier_reference']);
			}
		}
		if($id) $sqladd = $CI->db->where('p.deleted', 0)->where('p.id', $id);
		$CI->db->where('s.id_bu', $id_bu);
		$ordersql = "p.`active` DESC";
		if($order) $ordersql = $order; 


		$CI->db->order_by("$ordersql, p.id_supplier ASC")->limit(10000);
		$count = $CI->db->count_all_results();
		return $count;
	}
	
	public function getManagedProducts($id = null, $supplier_id = null, $order = null, $term = null, $id_bu, $active = null) {

		$CI =& get_instance();
		$sqladd = '';

		$CI->db->select('p.id, s.id as supplier_id, p.supplier_reference, pc.name AS category_name, p.name AS name, s.name AS supplier_name, p.price, p.active, p.id_category, puprc.name AS unit_name, p.id_unit AS id_unit, p.packaging AS packaging, p.freq_inventory, p.comment, ps.mini AS stock_mini, ps.max AS stock_max, ps.qtty AS stock_qtty, ps.warning AS stock_warning, ps.last_update_user AS last_update_user, ps.last_update_pos AS last_update_pos, u.username AS last_update_user_name, p.manage_stock')->from('products AS p')->join('suppliers AS s', 'p.id_supplier = s.id')->join('products_unit AS puprc', 'p.id_unit = puprc.id')->join('products_category AS pc','p.id_category = pc.id','left')->join('products_stock AS ps','p.id = ps.id_product','right')->join('users AS u','ps.last_update_id_user = u.id','left');
		if($id) $sqladd = $CI->db->where('p.deleted', 0)->where('p.id', $id);
		if($active) $CI->db->where('p.active', true);
		if($supplier_id != null && $supplier_id != '%') $CI->db->where('p.deleted', 0)->where('s.id', $supplier_id);
		if($supplier_id != null && $supplier_id == '%') $CI->db->where('p.deleted', 0)->where('p.active', true);
		if($term != null){
			$array = array('p.name' => $term);
			$CI->db->where('p.deleted', 0)->like($array);
		}
		$CI->db->where('s.id_bu', $id_bu);
		$CI->db->where('s.deleted', 0);
		$CI->db->where('s.active', 1);
		
		$CI->db->where('p.manage_stock', 1);
		$ordersql = "p.`active` DESC"; 
		if($order) $ordersql = $order; 


		$CI->db->order_by("$ordersql, p.id_supplier ASC")->limit(10000);
		$req = $CI->db->get() or die($this->mysqli->error);

		$ret = array();
		foreach ($req->result_array() as $key) {
			$ret[$key['id']] = $key;
		}
		return $ret;
	}

	public function getPosProducts($id_bu, $name_filter=null) {
		$CI =& get_instance();
		$CI->db->select('*')->from('sales_product')->where('deleted', 0)->where('id_bu', $id_bu);
		if($name_filter) $CI->db->like('name', "$name_filter", 'both');
		$req = $CI->db->get() or die($this->mysqli->error);
		$ret = array();
		foreach ($req->result_array() as $key) {
			$ret[$key['id']] = $key;
		}
		return $ret;
	}

	public function getAttributName($id) {
		$CI =& get_instance();
		$CI->db->select('name')->from('products_attribut')->where('id', $id)->limit(1);
		$req = $CI->db->get() or die($this->mysqli->error);
		$ret = $req->result_array();
		return $ret[0]['name'];
	}

	public function getAttributs() {
		$CI =& get_instance();
		$CI->db->from('products_attribut');
		$req = $CI->db->get() or die($this->mysqli->error);
		$ret = array();
		foreach ($req->result_array() as $key) {
			$ret[$key['id']] = $key;
		}
		return $ret;
	}

	public function getMapping($id_bu) {
		$CI =& get_instance();
		$CI->db->select('*')->from('products_mapping')->join('products', 'products.id = products_mapping.id_product')->where('id_bu', $id_bu)->where('products.active', 1);
		$req = $CI->db->get() or die($this->mysqli->error);
		$ret = $req->result_array();
		return $ret;
	}

	public function getStock() {
		$CI =& get_instance();
		$CI->db->select('*')->from('products_stock');
		$req = $CI->db->get() or die($this->mysqli->error);
		$ret = array();
		foreach ($req->result_array() as $key) {
			$ret[$key['id_product']] = $key;
		}
		return $ret;
	}

	public function getSuppliers($order = null, $idsup = null, $id_bu) {
		$CI =& get_instance();
		$CI->load->library('hmw');

		if($order) {
			$CI->db->select('s.id as id, s.name, s.location, s.carriage_paid, s.payment_type, s.payment_delay, s.contact_order_name, s.contact_order_tel, s.contact_order_email, s.contact_sale_name, s.contact_sale_tel, s.contact_sale_email, s.delivery_days, s.order_method, s.comment_internal, s.comment_order, s.comment_delivery, s.comment_delivery_info, s.simple_order_form')
				->from('suppliers as s')
				->join('(SELECT date, user, status, supplier_id FROM orders WHERE (status = "sent" OR status = "received") AND date IS NOT null) as o','o.supplier_id = s.id','left')
				->where('s.active', 1)
				->where('s.deleted', 0)
				->where('s.id_bu', $id_bu)
				->order_by('o.date desc');
		} else {
			$CI->db->select('s.id as id, s.name, s.location, s.carriage_paid, s.payment_type, s.payment_delay, s.contact_order_name, s.contact_order_tel, s.contact_order_email, s.contact_sale_name, s.contact_sale_tel, s.contact_sale_email, s.delivery_days, s.order_method, s.comment_internal, s.comment_order, s.comment_delivery, s.comment_delivery_info, s.simple_order_form')
				->from('suppliers as s')
				->where('active', 1)
				->where('deleted', 0)
				->where('s.id_bu', $id_bu);

			if(!empty($idsup)) $CI->db->where('s.id', $idsup);
			$CI->db->order_by('s.name asc');
		}
		$req = $CI->db->get() or die($this->mysqli->error);

		$ret = array();
		$orders_status = array('sent', 'received');
		
		foreach ($req->result_array() as $key) {
			$CI->db->select('date, user')->from('orders')->where('supplier_id', $key["id"])->where_in('status', $orders_status)->where('id_bu', $id_bu)->order_by('date desc')->limit(1);
			$reql = $CI->db->get() or die($this->mysqli->error);
			$rowl = $reql->result_array();
			$ret[$key['id']] = $key;
			if(isset($rowl[0]['date'])) {
				$now = new DateTime();
				$dateBdd = new DateTime($rowl[0]['date']);
				$interval = $dateBdd->diff($now);

				$ret[$key['id']]['last_order'] = $interval->format('%h hour(s) and %i minute(s) ago');
				if($dateBdd->diff($now)->days > 0) $ret[$key['id']]['last_order'] = $interval->format('%d day(s) ago');
				if($dateBdd->diff($now)->m > 0) $ret[$key['id']]['last_order'] = $interval->format('%m month and %d day(s) ago');
				
				$ret[$key['id']]['last_order_user'] = $CI->hmw->getUser($rowl[0]['user']);
			}
		}
		return $ret;
	}

	public function getProductUnit() {
		$CI =& get_instance();
		$CI->db->select('*')->from('products_unit');
		$CI->db->order_by("name", "asc");
		$req = $CI->db->get() or die($this->mysqli->error);
		$ret = array();
		foreach ($req->result_array() as $key) {
			$ret[$key['id']] = $key;
		}
		return $ret;
	}

	public function getProductCategory() {
		$CI =& get_instance();
		$CI->db->select('*')->from('products_category')->where('active', 1)->where('deleted', 0)->order_by('id asc');
		$req = $CI->db->get() or die($this->mysqli->error);
		$ret = array();
		foreach ($req->result_array() as $key) {
			$ret[$key['id']] = $key;
		}
		return $ret;
	}

	public function getSupplierCategory() {
		$CI =& get_instance();
		$CI->db->select('*')->from('suppliers_category')->where('active', 1)->where('deleted', 0)->order_by('id asc');
		$req = $CI->db->get() or die($this->mysqli->error);
		$ret = array();
		foreach ($req->result_array() as $key) {
			$ret[$key['id']] = $key;
		}
		return $ret;
	}
}
?>
