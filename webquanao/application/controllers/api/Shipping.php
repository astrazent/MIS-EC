<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Shipping API Controller
 * 
 * Handles webhook callbacks from shipping provider
 */
class Shipping extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('transaction_model');
        $this->load->model('order_model');
        $this->load->model('product_model');
        $this->load->database();
    }
    
    /**
     * Webhook endpoint for receiving shipping status updates
     */
    public function webhook() {
        // Get raw POST data
        $post_data = file_get_contents('php://input');
        
        // Log the webhook data
        log_message('info', 'Shipping webhook received: ' . $post_data);
        
        // If no data received, check if this is a GET request (for testing)
        if (empty($post_data) && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode([
                'status' => 'success',
                'message' => 'Webhook endpoint is working. Use POST to send data.'
            ]));
            return;
        }
        
        $shipping_data = json_decode($post_data, true);
        
        // Check if JSON was invalid
        if (json_last_error() !== JSON_ERROR_NONE) {
            log_message('error', 'Invalid JSON in webhook data: ' . json_last_error_msg());
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode([
                'status' => 'error', 
                'message' => 'Invalid JSON: ' . json_last_error_msg()
            ]));
            return;
        }
        
        // Validate request
        if (!isset($shipping_data['transaction_id']) || !isset($shipping_data['status'])) {
            log_message('error', 'Invalid shipping webhook data: Missing required fields');
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode([
                'status' => 'error', 
                'message' => 'Invalid data: Missing required fields'
            ]));
            return;
        }
        
        // Extract data
        $transaction_id = $shipping_data['transaction_id'];
        $status = intval($shipping_data['status']); // Ensure it's an integer
        $tracking_id = isset($shipping_data['tracking_id']) ? $shipping_data['tracking_id'] : null;
        
        log_message('info', "Processing shipping update: transaction_id={$transaction_id}, status={$status}, tracking_id={$tracking_id}");
        
        // Start transaction
        $this->db->trans_start();
        
        try {
            // Verify transaction exists
            $transaction = $this->transaction_model->get_info($transaction_id);
            if (!$transaction) {
                throw new Exception('Transaction not found: ' . $transaction_id);
            }
            
            log_message('debug', "Current transaction status: " . $transaction->status);
            
            // Update transaction status
            $data = ['status' => $status];
            if ($tracking_id) {
                $data['shipping_info'] = $tracking_id;
            }
            
            $update_result = $this->transaction_model->update($transaction_id, $data);
            log_message('debug', "Transaction update result: " . ($update_result ? 'success' : 'failed'));
            
            // If status is delivered (3), update product purchase counts
            if ($status == 3) {
                log_message('info', "Processing delivered status for transaction {$transaction_id}");
                $orders = $this->order_model->get_list(['where' => ['transaction_id' => $transaction_id]]);
                
                log_message('debug', "Found " . count($orders) . " orders for transaction {$transaction_id}");
                
                foreach ($orders as $order) {
                    $product = $this->product_model->get_info($order->product_id);
                    if ($product) {
                        log_message('debug', "Updating purchase count for product {$product->id}: {$product->buyed} -> " . ($product->buyed + $order->qty));
                        
                        $this->product_model->update($product->id, [
                            'buyed' => $product->buyed + $order->qty
                        ]);
                    } else {
                        log_message('warning', "Product not found: {$order->product_id}");
                    }
                }
            }
            
            // Complete transaction
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Database transaction failed: ' . $this->db->error()['message']);
            }
            
            // Log success
            log_message('info', 'Transaction #' . $transaction_id . ' status updated to ' . $status);
            
            // Return success response
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode([
                'status' => 'success',
                'message' => 'Transaction status updated successfully',
                'transaction_id' => $transaction_id,
                'new_status' => $status
            ]));
            
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->trans_rollback();
            
            // Log error
            log_message('error', 'Webhook error: ' . $e->getMessage());
            
            // Return error response
            $this->output->set_status_header(500);
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]));
        }
    }
    
    /**
     * Test endpoint to verify webhook functionality
     */
    public function test() {
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode([
            'status' => 'success',
            'message' => 'Shipping API is working correctly'
        ]));
    }
} 