<?php
/**
 * Order Model
 * AgriSmart - Agriculture Marketplace
 * 
 * Handles order data operations with PDO
 */

class Order {
    
    private $db;
    private $table = 'orders';
    
    public $id;
    public $user_id;
    public $order_number;
    public $total_amount;
    public $status;
    public $payment_method;
    public $payment_status;
    public $shipping_address;
    public $shipping_phone;
    public $notes;
    public $created_at;
    public $updated_at;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Create new order
     * 
     * @param array $orderData
     * @param array $items
     * @return int|false Order ID or false on failure
     */
    public function create($orderData, $items) {
        try {
            $this->db->beginTransaction();
            
            // Generate unique order number
            $orderNumber = $this->generateOrderNumber();
            
            // Insert order
            $sql = "INSERT INTO {$this->table} 
                    (user_id, order_number, total_amount, status, payment_method, payment_status, 
                     shipping_address, shipping_phone, notes) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $orderData['user_id'],
                $orderNumber,
                $orderData['total_amount'],
                $orderData['status'] ?? 'pending',
                $orderData['payment_method'] ?? 'cash',
                $orderData['payment_status'] ?? 'pending',
                $orderData['shipping_address'],
                $orderData['shipping_phone'],
                $orderData['notes'] ?? null
            ]);
            
            $orderId = $this->db->lastInsertId();
            
            // Insert order items
            $itemSql = "INSERT INTO order_items 
                        (order_id, product_id, seller_id, quantity, price, subtotal) 
                        VALUES (?, ?, ?, ?, ?, ?)";
            $itemStmt = $this->db->prepare($itemSql);
            
            foreach ($items as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['seller_id'],
                    $item['quantity'],
                    $item['price'],
                    $item['subtotal']
                ]);
                
                // Update product stock
                $stockSql = "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?";
                $stockStmt = $this->db->prepare($stockSql);
                $stockStmt->execute([$item['quantity'], $item['product_id']]);
            }
            
            $this->db->commit();
            return $orderId;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Order creation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find order by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function findById($id) {
        try {
            $sql = "SELECT o.*, u.full_name as customer_name, u.email as customer_email, u.phone as customer_phone
                    FROM {$this->table} o
                    LEFT JOIN users u ON o.user_id = u.id
                    WHERE o.id = ? LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $order = $stmt->fetch();
            
            if ($order) {
                // Get order items
                $order['items'] = $this->getOrderItems($id);
            }
            
            return $order;
        } catch (PDOException $e) {
            error_log("Order findById error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find order by order number
     * 
     * @param string $orderNumber
     * @return array|false
     */
    public function findByOrderNumber($orderNumber) {
        try {
            $sql = "SELECT o.*, u.full_name as customer_name, u.email as customer_email
                    FROM {$this->table} o
                    LEFT JOIN users u ON o.user_id = u.id
                    WHERE o.order_number = ? LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderNumber]);
            $order = $stmt->fetch();
            
            if ($order) {
                $order['items'] = $this->getOrderItems($order['id']);
            }
            
            return $order;
        } catch (PDOException $e) {
            error_log("Order findByOrderNumber error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get order items
     * 
     * @param int $orderId
     * @return array
     */
    public function getOrderItems($orderId) {
        try {
            $sql = "SELECT oi.*, p.name as product_name, p.image, p.unit,
                    u.full_name as seller_name, u.phone as seller_phone
                    FROM order_items oi
                    LEFT JOIN products p ON oi.product_id = p.id
                    LEFT JOIN users u ON oi.seller_id = u.id
                    WHERE oi.order_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$orderId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Order getOrderItems error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Update order
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        try {
            $fields = [];
            $values = [];
            
            $allowedFields = ['status', 'payment_method', 'payment_status', 'shipping_address', 
                             'shipping_phone', 'notes'];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "{$field} = ?";
                    $values[] = $data[$field];
                }
            }
            
            if (empty($fields)) {
                return false;
            }
            
            $values[] = $id;
            $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($values);
            
        } catch (PDOException $e) {
            error_log("Order update error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all orders with pagination
     * 
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return array
     */
    public function getAll($page = 1, $perPage = 20, $filters = []) {
        try {
            $offset = ($page - 1) * $perPage;
            $where = [];
            $params = [];
            
            if (isset($filters['user_id'])) {
                $where[] = "o.user_id = ?";
                $params[] = $filters['user_id'];
            }
            
            if (isset($filters['seller_id'])) {
                $where[] = "oi.seller_id = ?";
                $params[] = $filters['seller_id'];
            }
            
            if (isset($filters['status'])) {
                $where[] = "o.status = ?";
                $params[] = $filters['status'];
            }
            
            if (isset($filters['payment_status'])) {
                $where[] = "o.payment_status = ?";
                $params[] = $filters['payment_status'];
            }
            
            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
            
            // Join with order_items for seller filter
            $joinClause = '';
            $groupBy = '';
            if (isset($filters['seller_id'])) {
                $joinClause = 'LEFT JOIN order_items oi ON o.id = oi.order_id';
                $groupBy = 'GROUP BY o.id';
            }
            
            // Get total count
            $countSql = "SELECT COUNT(DISTINCT o.id) FROM {$this->table} o {$joinClause} {$whereClause}";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetchColumn();
            
            // Get orders
            $sql = "SELECT DISTINCT o.*, u.full_name as customer_name, u.email as customer_email
                    FROM {$this->table} o
                    LEFT JOIN users u ON o.user_id = u.id
                    {$joinClause}
                    {$whereClause}
                    {$groupBy}
                    ORDER BY o.created_at DESC
                    LIMIT ? OFFSET ?";
            
            $params[] = $perPage;
            $params[] = $offset;
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $orders = $stmt->fetchAll();
            
            // Get items for each order
            foreach ($orders as &$order) {
                $order['items'] = $this->getOrderItems($order['id']);
            }
            
            return [
                'data' => $orders,
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($total / $perPage)
            ];
            
        } catch (PDOException $e) {
            error_log("Order getAll error: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'page' => 1, 'per_page' => $perPage, 'total_pages' => 0];
        }
    }
    
    /**
     * Get user orders
     * 
     * @param int $userId
     * @param int $limit
     * @return array
     */
    public function getUserOrders($userId, $limit = 10) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE user_id = ? 
                    ORDER BY created_at DESC 
                    LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $limit]);
            $orders = $stmt->fetchAll();
            
            foreach ($orders as &$order) {
                $order['items'] = $this->getOrderItems($order['id']);
            }
            
            return $orders;
        } catch (PDOException $e) {
            error_log("Order getUserOrders error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get seller orders
     * 
     * @param int $sellerId
     * @param int $limit
     * @return array
     */
    public function getSellerOrders($sellerId, $limit = 10) {
        try {
            $sql = "SELECT DISTINCT o.*, u.full_name as customer_name, u.phone as customer_phone
                    FROM {$this->table} o
                    JOIN order_items oi ON o.id = oi.order_id
                    JOIN users u ON o.user_id = u.id
                    WHERE oi.seller_id = ?
                    ORDER BY o.created_at DESC
                    LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$sellerId, $limit]);
            $orders = $stmt->fetchAll();
            
            foreach ($orders as &$order) {
                // Get only items for this seller
                $itemSql = "SELECT oi.*, p.name as product_name, p.image as product_image, p.unit
                            FROM order_items oi
                            LEFT JOIN products p ON oi.product_id = p.id
                            WHERE oi.order_id = ? AND oi.seller_id = ?";
                $itemStmt = $this->db->prepare($itemSql);
                $itemStmt->execute([$order['id'], $sellerId]);
                $order['items'] = $itemStmt->fetchAll();
            }
            
            return $orders;
        } catch (PDOException $e) {
            error_log("Order getSellerOrders error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Generate unique order number
     * 
     * @return string
     */
    private function generateOrderNumber() {
        return 'ORD-' . date('Y') . '-' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }
    
    /**
     * Get order statistics
     * 
     * @param array $filters
     * @return array
     */
    public function getStatistics($filters = []) {
        try {
            $where = [];
            $params = [];
            
            if (isset($filters['user_id'])) {
                $where[] = "user_id = ?";
                $params[] = $filters['user_id'];
            }
            
            if (isset($filters['seller_id'])) {
                // Need to join with order_items
                $sql = "SELECT 
                        COUNT(DISTINCT o.id) as total_orders,
                        SUM(oi.subtotal) as total_revenue,
                        COUNT(CASE WHEN o.status = 'pending' THEN 1 END) as pending_orders,
                        COUNT(CASE WHEN o.status = 'delivered' THEN 1 END) as delivered_orders
                        FROM {$this->table} o
                        JOIN order_items oi ON o.id = oi.order_id
                        WHERE oi.seller_id = ?";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$filters['seller_id']]);
            } else {
                $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
                
                $sql = "SELECT 
                        COUNT(*) as total_orders,
                        SUM(total_amount) as total_revenue,
                        COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_orders,
                        COUNT(CASE WHEN status = 'delivered' THEN 1 END) as delivered_orders,
                        COUNT(CASE WHEN payment_status = 'paid' THEN 1 END) as paid_orders
                        FROM {$this->table} {$whereClause}";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute($params);
            }
            
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            error_log("Order statistics error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Cancel order
     * 
     * @param int $id
     * @return bool
     */
    public function cancel($id) {
        try {
            $this->db->beginTransaction();
            
            // Get order items to restore stock
            $items = $this->getOrderItems($id);
            
            // Restore stock for each item
            $stockSql = "UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?";
            $stockStmt = $this->db->prepare($stockSql);
            
            foreach ($items as $item) {
                $stockStmt->execute([$item['quantity'], $item['product_id']]);
            }
            
            // Update order status
            $sql = "UPDATE {$this->table} SET status = 'cancelled' WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            $this->db->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("Order cancel error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get order by ID
     * 
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        try {
            $sql = "SELECT o.*, u.full_name as customer_name, u.email as customer_email 
                    FROM {$this->table} o
                    LEFT JOIN users u ON o.user_id = u.id
                    WHERE o.id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
            
        } catch (PDOException $e) {
            error_log("Order getById error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update order status
     * 
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus($id, $status) {
        try {
            $sql = "UPDATE {$this->table} SET status = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $id]);
            
        } catch (PDOException $e) {
            error_log("Order updateStatus error: " . $e->getMessage());
            return false;
        }
    }
}