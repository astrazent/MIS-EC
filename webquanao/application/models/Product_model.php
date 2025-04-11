<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends MY_Model {
	var $table = 'product';

	public function get_products_with_discount($input = []) {
        $this->db->select('
            product.*,
            CASE
                WHEN discount.status = 1 
                     AND product.price >= discount.min_price 
                     AND NOW() BETWEEN discount.start_date AND discount.end_date THEN
                    CASE
                        WHEN discount.measure = 0 THEN discount.value
                        WHEN discount.measure = 1 THEN product.price * (discount.value / 100)
                        ELSE 0
                    END
                ELSE 0
            END AS discount
        ');
        $this->db->from('product');
        $this->db->join('discount', 'product.discount_id = discount.id', 'left');

        // Áp dụng các điều kiện từ $input
        if (isset($input['order']) && is_array($input['order'])) {
            $this->db->order_by($input['order'][0], $input['order'][1]);
        }
        if (isset($input['limit']) && is_array($input['limit'])) {
            $this->db->limit($input['limit'][0], $input['limit'][1]);
        }

        return $this->db->get()->result();
    }

	public function get_product_with_discount($id) {
		$this->db->select('
			product.*,
			discount.name AS discount_name,
			discount.measure,
			discount.value AS discount_value,
			discount.min_price,
			discount.start_date,
			discount.end_date,
			discount.status,
			CASE
				WHEN discount.status = 1 
					 AND product.price >= discount.min_price 
					 AND NOW() BETWEEN discount.start_date AND discount.end_date THEN
					CASE
						WHEN discount.measure = 0 THEN discount.value
						WHEN discount.measure = 1 THEN product.price * (discount.value / 100)
						ELSE 0
					END
				ELSE 0
			END AS discount
		');
		$this->db->from('product');
		$this->db->join('discount', 'product.discount_id = discount.id', 'left');
		$this->db->where('product.id', $id);
	
		return $this->db->get()->row(); // Trả về một đối tượng
	}
}

