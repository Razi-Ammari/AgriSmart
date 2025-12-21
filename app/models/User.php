<?php
/**
 * User Model
 * AgriSmart - Agriculture Marketplace
 * 
 * Handles user data operations with PDO
 */

class User {
    
    private $db;
    private $table = 'users';
    
    public $id;
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $phone;
    public $address;
    public $role;
    public $profile_image;
    public $is_active;
    public $email_verified;
    public $created_at;
    public $updated_at;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Create new user
     * 
     * @param array $data
     * @return int|false User ID or false on failure
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} 
                    (username, email, password, full_name, phone, address, role, profile_image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                $data['username'],
                $data['email'],
                Security::hashPassword($data['password']),
                $data['full_name'],
                $data['phone'] ?? null,
                $data['address'] ?? null,
                $data['role'] ?? 'user',
                $data['profile_image'] ?? 'default-avatar.png'
            ]);
            
            if ($success) {
                // Log activity
                $this->logActivity($this->db->lastInsertId(), 'user_registered');
                return $this->db->lastInsertId();
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("User creation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find user by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function findById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("User findById error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find user by email
     * 
     * @param string $email
     * @return array|false
     */
    public function findByEmail($email) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("User findByEmail error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find user by username
     * 
     * @param string $username
     * @return array|false
     */
    public function findByUsername($username) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE username = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$username]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("User findByUsername error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Authenticate user
     * 
     * @param string $login (email or username)
     * @param string $password
     * @return array|false
     */
    public function authenticate($login, $password) {
        try {
            $sql = "SELECT * FROM {$this->table} 
                    WHERE (email = ? OR username = ?) AND is_active = 1 
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$login, $login]);
            $user = $stmt->fetch();
            
            if ($user && Security::verifyPassword($password, $user['password'])) {
                // Log activity
                $this->logActivity($user['id'], 'user_login');
                return $user;
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("User authentication error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update user
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        try {
            $fields = [];
            $values = [];
            
            $allowedFields = ['username', 'email', 'full_name', 'phone', 'address', 'profile_image', 'role', 'is_active'];
            
            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    $fields[] = "{$field} = ?";
                    $values[] = $data[$field];
                }
            }
            
            if (isset($data['password']) && !empty($data['password'])) {
                $fields[] = "password = ?";
                $values[] = Security::hashPassword($data['password']);
            }
            
            if (empty($fields)) {
                return false;
            }
            
            $values[] = $id;
            $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute($values);
            
            if ($success) {
                $this->logActivity($id, 'user_updated');
            }
            
            return $success;
            
        } catch (PDOException $e) {
            error_log("User update error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete user
     * 
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("User delete error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all users with pagination
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
            
            if (isset($filters['role'])) {
                $where[] = "role = ?";
                $params[] = $filters['role'];
            }
            
            if (isset($filters['is_active'])) {
                $where[] = "is_active = ?";
                $params[] = $filters['is_active'];
            }
            
            if (isset($filters['search'])) {
                $where[] = "(username LIKE ? OR email LIKE ? OR full_name LIKE ?)";
                $searchTerm = '%' . $filters['search'] . '%';
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }
            
            $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
            
            // Get total count
            $countSql = "SELECT COUNT(*) FROM {$this->table} {$whereClause}";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetchColumn();
            
            // Get users
            $sql = "SELECT id, username, email, full_name, phone, role, profile_image, is_active, email_verified, created_at 
                    FROM {$this->table} {$whereClause} 
                    ORDER BY created_at DESC 
                    LIMIT ? OFFSET ?";
            
            $params[] = $perPage;
            $params[] = $offset;
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $users = $stmt->fetchAll();
            
            return [
                'data' => $users,
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($total / $perPage)
            ];
            
        } catch (PDOException $e) {
            error_log("User getAll error: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'page' => 1, 'per_page' => $perPage, 'total_pages' => 0];
        }
    }
    
    /**
     * Create password reset token
     * 
     * @param string $email
     * @return string|false Token or false on failure
     */
    public function createPasswordResetToken($email) {
        try {
            $user = $this->findByEmail($email);
            if (!$user) {
                return false;
            }
            
            $token = Security::generateToken(32);
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $sql = "INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user['id'], $token, $expiresAt]);
            
            return $token;
            
        } catch (PDOException $e) {
            error_log("Password reset token error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verify password reset token
     * 
     * @param string $token
     * @return array|false
     */
    public function verifyPasswordResetToken($token) {
        try {
            $sql = "SELECT pr.*, u.email FROM password_resets pr 
                    JOIN users u ON pr.user_id = u.id 
                    WHERE pr.token = ? AND pr.expires_at > NOW() 
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$token]);
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            error_log("Verify password reset token error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Reset password
     * 
     * @param string $token
     * @param string $newPassword
     * @return bool
     */
    public function resetPassword($token, $newPassword) {
        try {
            $resetData = $this->verifyPasswordResetToken($token);
            if (!$resetData) {
                return false;
            }
            
            // Update password
            $sql = "UPDATE {$this->table} SET password = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([Security::hashPassword($newPassword), $resetData['user_id']]);
            
            // Delete used token
            $deleteSql = "DELETE FROM password_resets WHERE token = ?";
            $deleteStmt = $this->db->prepare($deleteSql);
            $deleteStmt->execute([$token]);
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Reset password error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Log user activity
     * 
     * @param int $userId
     * @param string $action
     * @param string $entityType
     * @param int $entityId
     */
    private function logActivity($userId, $action, $entityType = null, $entityId = null) {
        try {
            $sql = "INSERT INTO activity_logs (user_id, action, entity_type, entity_id, ip_address, user_agent) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $userId,
                $action,
                $entityType,
                $entityId,
                Security::getClientIp(),
                Security::getUserAgent()
            ]);
        } catch (PDOException $e) {
            error_log("Activity log error: " . $e->getMessage());
        }
    }
    
    /**
     * Verify user account
     * 
     * @param int $userId
     * @return bool
     */
    public function verifyAccount($userId) {
        try {
            $sql = "UPDATE {$this->table} SET email_verified = 1, is_active = 1 WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("User verifyAccount error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toggle user active status
     * 
     * @param int $userId
     * @param int $status (0 or 1)
     * @return bool
     */
    public function toggleActiveStatus($userId, $status) {
        try {
            $sql = "UPDATE {$this->table} SET is_active = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $userId]);
        } catch (PDOException $e) {
            error_log("User toggleActiveStatus error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get user statistics
     * 
     * @param int $userId
     * @return array
     */
    public function getStatistics($userId) {
        try {
            $stats = [];
            
            // Total orders
            $sql = "SELECT COUNT(*) as total_orders, 
                    SUM(total_amount) as total_spent 
                    FROM orders WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $orderStats = $stmt->fetch();
            $stats['total_orders'] = $orderStats['total_orders'] ?? 0;
            $stats['total_spent'] = $orderStats['total_spent'] ?? 0;
            
            // Products (for buyers)
            $sql = "SELECT COUNT(*) as total_products FROM products WHERE seller_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $stats['total_products'] = $stmt->fetchColumn();
            
            // Reviews
            $sql = "SELECT COUNT(*) as total_reviews FROM reviews WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $stats['total_reviews'] = $stmt->fetchColumn();
            
            return $stats;
            
        } catch (PDOException $e) {
            error_log("User statistics error: " . $e->getMessage());
            return [];
        }
    }
}
