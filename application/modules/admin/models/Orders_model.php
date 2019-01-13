<?php

class Orders_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function ordersCount($onlyNew = false)
    {
        if ($onlyNew == true) {
            $this->db->where('viewed', 0);
        }
        return $this->db->count_all_results('orders');
    }

    public function orders($limit, $page, $order_by)
    {
        if ($order_by != null) {
            $this->db->order_by($order_by, 'DESC');
        } else {
            $this->db->order_by('id', 'DESC');
        }
        $this->db->select('orders.*, orders_clients.first_name,'
                . ' orders_summary.with_delivery as withDelivery, orders_summary.total_cost as totalCost, orders_summary.delivery_cost as deliveryCost, '
                . ' orders_clients.last_name, orders_clients.email, orders_clients.phone, '
                . 'orders_clients.address, orders_clients.city, orders_clients.post_code,'
                . ' orders_clients.notes, discount_codes.type as discount_type, discount_codes.amount as discount_amount');
	    $this->db->join('orders_clients', 'orders_clients.for_id = orders.id', 'inner');
	    $this->db->join('orders_summary', 'orders_summary.order_id = orders.order_id', 'inner');
        $this->db->join('discount_codes', 'discount_codes.code = orders.discount_code', 'left');
        $result = $this->db->get('orders', $limit, $page);
        return $result->result_array();
    }

    public function changeOrderStatus($id, $to_status)
    {
        $this->db->where('id', $id);
        $this->db->select('processed');
        $result1 = $this->db->get('orders');
        $res = $result1->row_array();

        if ($res['processed'] != $to_status) {
            $this->db->where('id', $id);
            $result = $this->db->update('orders', array('processed' => $to_status, 'viewed' => '1'));
            if ($result == true) {
                $this->manageQuantitiesAndProcurement($id, $to_status, $res['processed']);
            }
        } else {
            $result = false;
        }
        return $result;
    }

	private function manageQuantitiesAndProcurement($id, $to_status, $current) {
		if ( ($to_status == 0 || $to_status == 2)) {
			$operator = '+';
			$operator_pro = '-';
		}
		if ( $to_status == 1 AND $current == 2) {
			$operator = '-';
			$operator_pro = '+';
		}
		$this->db->select( 'products' );
		$this->db->where( 'id', $id );
		$result = $this->db->get( 'orders' );
		$arr = $result->row_array();
		$products = unserialize( $arr['products'] );
		foreach ( $products as $product_id => $quantity ) {
			if ( isset( $operator ) ) {
				if ( !$this->db->query( 'UPDATE products SET quantity=quantity' . $operator . $quantity . ' WHERE id = ' . $product_id ) ) {
					log_message( 'error', print_r( $this->db->error(), TRUE ) );
					show_error( lang( 'database_error' ) );
				}
			}
//			if ( isset( $operator_pro ) ) {
//				if ( !$this->db->query( 'UPDATE products SET procurement=procurement' . $operator_pro . $quantity . ' WHERE id = ' . $product_id ) ) {
//					log_message( 'error', print_r( $this->db->error(), TRUE ) );
//					show_error( lang( 'database_error' ) );
//				}
//			}
		}


	}

	public function checkoutProducts($products){

		foreach ($products['array'] as $product) {
			if (!$this->db->query('UPDATE products SET quantity=quantity' . '-' . $product['num_added'] . ' WHERE id = ' . $product['id'])) {
				log_message('error', print_r($this->db->error(), true));
				show_error(lang('database_error'));
			}

//			if (!$this->db->query('UPDATE products SET procurement=procurement' . '+' . $product['num_added'] . ' WHERE id = ' . $product['id'])) {
//				log_message('error', print_r($this->db->error(), true));
//				show_error(lang('database_error'));
//			}
		}
	}

    public function setBankAccountSettings($post)
    {
        $query = $this->db->query('SELECT id FROM bank_accounts');
        if ($query->num_rows() == 0) {
            $id = 1;
        } else {
            $result = $query->row_array();
            $id = $result['id'];
        }
        $post['id'] = $id;
        if (!$this->db->replace('bank_accounts', $post)) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
    }

    public function getBankAccountSettings()
    {
        $result = $this->db->query("SELECT * FROM bank_accounts LIMIT 1");
        return $result->row_array();
    }

}
