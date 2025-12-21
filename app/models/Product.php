<?php
/**
 * Product Model
 * AgriSmart - Agriculture Marketplace
 * 
 * Handles product data operations with PDO
 */

class Product {
    
    private $db;
    private $table = 'products';
    
    public $id;
    public $seller_id;
    public $category_id;
    public $name;
    public $slug;
    public $description;
    public $price;
    public $stock_quantity;
    public $unit;
    public $image;
    public $images;
    public $is_featured;
    public $is_active;
    public $views_count;
    public $created_at;
    public $updated_at;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Create new product
     * 
     * @param array $data
     * @return int|false Product ID or false on failure
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO {$this->table} 
                    (seller_id, category_id, name, slug, description, price, stock_quantity, unit, image, images, is_featured, is_active,
                     min_temperature, max_temperature, min_humidity, max_humidity, origin, sunlight_requirement, 
                     watering_frequency, soil_type, growing_season, harvest_time) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            // Generate unique slug
            $baseSlug = $data['slug'] ?? Security::generateSlug($data['name']);
            $slug = $this->generateUniqueSlug($baseSlug);
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                $data['seller_id'],
                $data['category_id'],
                $data['name'],
                $slug,
                $data['description'] ?? null,
                $data['price'],
                $data['stock_quantity'] ?? 0,
                $data['unit'] ?? 'piece',
                $data['image'] ?? null,
                isset($data['images']) ? json_encode($data['images']) : null,
                $data['is_featured'] ?? 0,
                $data['is_active'] ?? 1,
                $data['min_temperature'] ?? null,
                $data['max_temperature'] ?? null,
                $data['min_humidity'] ?? null,
                $data['max_humidity'] ?? null,
                $data['origin'] ?? null,
                $data['sunlight_requirement'] ?? null,
                $data['watering_frequency'] ?? null,
                $data['soil_type'] ?? null,
                $data['growing_season'] ?? null,
                $data['harvest_time'] ?? null
            ]);
            
            return $success ? $this->db->lastInsertId() : false;
            
        } catch (PDOException $e) {
            error_log("Product creation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find product by ID
     * 
     * @param int $id
     * @return array|false
     */
    public function findById($id) {
        try {
            $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug,
                    u.username as seller_username, u.full_name as seller_name, u.phone as seller_phone
                    FROM {$this->table} p
                    LEFT JOIN categories c ON p.category_id = c.id
                    LEFT JOIN users u ON p.seller_id = u.id
                    WHERE p.id = ? LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Product findById error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find product by slug
     * 
     * @param string $slug
     * @return array|false
     */
    public function findBySlug($slug) {
        try {
            $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug,
                    u.username as seller_username, u.full_name as seller_name, u.phone as seller_phone
                    FROM {$this->table} p
                    LEFT JOIN categories c ON p.category_id = c.id
                    LEFT JOIN users u ON p.seller_id = u.id
                    WHERE p.slug = ? LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$slug]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Product findBySlug error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update product
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        try {
            $fields = [];
            $values = [];
            
            $allowedFields = ['category_id', 'name', 'slug', 'description', 'price', 'stock_quantity', 
                             'unit', 'image', 'images', 'is_featured', 'is_active',
                             'min_temperature', 'max_temperature', 'min_humidity', 'max_humidity',
                             'origin', 'sunlight_requirement', 'watering_frequency', 'soil_type',
                             'growing_season', 'harvest_time'];
            
            // Generate unique slug if name or slug is being updated
            if (isset($data['slug']) || isset($data['name'])) {
                $baseSlug = $data['slug'] ?? (isset($data['name']) ? Security::generateSlug($data['name']) : null);
                if ($baseSlug) {
                    $data['slug'] = $this->generateUniqueSlug($baseSlug, $id);
                }
            }
            
            foreach ($allowedFields as $field) {
                if (array_key_exists($field, $data)) {
                    $fields[] = "{$field} = ?";
                    $values[] = ($field === 'images' && is_array($data[$field])) 
                                ? json_encode($data[$field]) 
                                : $data[$field];
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
            error_log("Product update error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete product
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
            error_log("Product delete error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all products with pagination and filters
     * 
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return array
     */
    public function getAll($page = 1, $perPage = 12, $filters = []) {
        try {
            $offset = ($page - 1) * $perPage;
            $where = ['p.is_active = 1'];
            $params = [];
            
            if (!empty($filters['category_id'])) {
                $where[] = "p.category_id = ?";
                $params[] = $filters['category_id'];
            }
            
            if (!empty($filters['seller_id'])) {
                $where[] = "p.seller_id = ?";
                $params[] = $filters['seller_id'];
            }
            
            if (isset($filters['is_featured'])) {
                $where[] = "p.is_featured = ?";
                $params[] = $filters['is_featured'];
            }
            
            if (!empty($filters['search'])) {
                $where[] = "MATCH(p.name, p.description) AGAINST(? IN NATURAL LANGUAGE MODE)";
                $params[] = $filters['search'];
            }
            
            if (!empty($filters['min_price'])) {
                $where[] = "p.price >= ?";
                $params[] = $filters['min_price'];
            }
            
            if (!empty($filters['max_price'])) {
                $where[] = "p.price <= ?";
                $params[] = $filters['max_price'];
            }
            
            $whereClause = 'WHERE ' . implode(' AND ', $where);
            
            // Get total count
            $countSql = "SELECT COUNT(*) FROM {$this->table} p {$whereClause}";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetchColumn();
            
            // Determine sort order
            $orderBy = "p.created_at DESC";
            if (isset($filters['sort'])) {
                switch ($filters['sort']) {
                    case 'price_asc':
                        $orderBy = "p.price ASC";
                        break;
                    case 'price_desc':
                        $orderBy = "p.price DESC";
                        break;
                    case 'popular':
                        $orderBy = "p.views_count DESC";
                        break;
                    case 'name':
                        $orderBy = "p.name ASC";
                        break;
                }
            }
            
            // Get products
            $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug,
                    u.username as seller_username, u.full_name as seller_name
                    FROM {$this->table} p
                    LEFT JOIN categories c ON p.category_id = c.id
                    LEFT JOIN users u ON p.seller_id = u.id
                    {$whereClause}
                    ORDER BY {$orderBy}
                    LIMIT ? OFFSET ?";
            
            $params[] = $perPage;
            $params[] = $offset;
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $products = $stmt->fetchAll();
            
            return [
                'data' => $products,
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($total / $perPage)
            ];
            
        } catch (PDOException $e) {
            error_log("Product getAll error: " . $e->getMessage());
            return ['data' => [], 'total' => 0, 'page' => 1, 'per_page' => $perPage, 'total_pages' => 0];
        }
    }
    
    /**
     * Get featured products
     * 
     * @param int $limit
     * @return array
     */
    public function getFeatured($limit = 8) {
        try {
            $sql = "SELECT p.*, c.name as category_name, u.full_name as seller_name
                    FROM {$this->table} p
                    LEFT JOIN categories c ON p.category_id = c.id
                    LEFT JOIN users u ON p.seller_id = u.id
                    WHERE p.is_featured = 1 AND p.is_active = 1
                    ORDER BY p.created_at DESC
                    LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Product getFeatured error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get latest products
     * 
     * @param int $limit
     * @return array
     */
    public function getLatest($limit = 8) {
        try {
            $sql = "SELECT p.*, c.name as category_name, u.full_name as seller_name
                    FROM {$this->table} p
                    LEFT JOIN categories c ON p.category_id = c.id
                    LEFT JOIN users u ON p.seller_id = u.id
                    WHERE p.is_active = 1
                    ORDER BY p.created_at DESC
                    LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Product getLatest error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get recommended products
     * 
     * @param int $productId Current product ID
     * @param int $limit
     * @return array
     */
    public function getRecommended($productId, $limit = 4) {
        try {
            // Get current product category
            $product = $this->findById($productId);
            if (!$product) {
                return $this->getLatest($limit);
            }
            
            // Get products from same category, excluding current product
            $sql = "SELECT p.*, c.name as category_name, u.full_name as seller_name
                    FROM {$this->table} p
                    LEFT JOIN categories c ON p.category_id = c.id
                    LEFT JOIN users u ON p.seller_id = u.id
                    WHERE p.category_id = ? AND p.id != ? AND p.is_active = 1
                    ORDER BY p.views_count DESC, p.created_at DESC
                    LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$product['category_id'], $productId, $limit]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Product getRecommended error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Increment product views
     * 
     * @param int $id
     * @return bool
     */
    public function incrementViews($id) {
        try {
            $sql = "UPDATE {$this->table} SET views_count = views_count + 1 WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Product incrementViews error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update product stock
     * 
     * @param int $id
     * @param int $quantity
     * @return bool
     */
    public function updateStock($id, $quantity) {
        try {
            $sql = "UPDATE {$this->table} SET stock_quantity = stock_quantity - ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$quantity, $id]);
        } catch (PDOException $e) {
            error_log("Product updateStock error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get product reviews
     * 
     * @param int $productId
     * @return array
     */
    public function getReviews($productId) {
        try {
            $sql = "SELECT r.*, u.full_name as user_name, u.profile_image
                    FROM reviews r
                    JOIN users u ON r.user_id = u.id
                    WHERE r.product_id = ? AND r.is_approved = 1
                    ORDER BY r.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$productId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Product getReviews error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get average rating
     * 
     * @param int $productId
     * @return float
     */
    public function getAverageRating($productId) {
        try {
            $sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews
                    FROM reviews
                    WHERE product_id = ? AND is_approved = 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$productId]);
            $result = $stmt->fetch();
            
            return [
                'average' => round($result['avg_rating'] ?? 0, 1),
                'total' => $result['total_reviews'] ?? 0
            ];
        } catch (PDOException $e) {
            error_log("Product getAverageRating error: " . $e->getMessage());
            return ['average' => 0, 'total' => 0];
        }
    }
    
    /**
     * Get all categories
     * 
     * @return array
     */
    public function getCategories() {
        try {
            $sql = "SELECT c.*, COUNT(p.id) as products_count
                    FROM categories c
                    LEFT JOIN products p ON c.id = p.category_id AND p.is_active = 1
                    WHERE c.is_active = 1
                    GROUP BY c.id
                    ORDER BY c.name ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Product getCategories error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Generate a unique slug
     * 
     * @param string $baseSlug
     * @param int|null $id Current product ID for updates
     * @return string
     */
    private function generateUniqueSlug($baseSlug, $id = null) {
        $slug = $baseSlug;
        $counter = 1;
        
        while ($this->slugExists($slug, $id)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Check if slug exists
     * 
     * @param string $slug
     * @param int|null $id Current product ID to exclude
     * @return bool
     */
    private function slugExists($slug, $id = null) {
        try {
            $sql = "SELECT id FROM {$this->table} WHERE slug = ?";
            $params = [$slug];
            
            if ($id) {
                $sql .= " AND id != ?";
                $params[] = $id;
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log("Product slugExists error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get AI-style smart recommendations based on growing conditions
     * Uses weighted scoring algorithm for fuzzy matching
     * 
     * @param array $conditions User's growing conditions
     * @return array Recommended products sorted by match score
     */
    public function getRecommendations($conditions) {
        try {
            // Get all active products with plant data
            $sql = "SELECT p.*, c.name as category_name, u.full_name as seller_name
                    FROM {$this->table} p
                    LEFT JOIN categories c ON p.category_id = c.id
                    LEFT JOIN users u ON p.seller_id = u.id
                    WHERE p.is_active = 1
                    AND p.min_temperature IS NOT NULL
                    ORDER BY p.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $products = $stmt->fetchAll();
            
            $recommendations = [];
            
            foreach ($products as $product) {
                $matchScore = $this->calculateMatchScore($product, $conditions);
                
                // Only include products with 30% or higher match
                if ($matchScore['total'] >= 30) {
                    $product['match_score'] = $matchScore['total'];
                    $product['scores'] = $matchScore['breakdown'];
                    $recommendations[] = $product;
                }
            }
            
            // Sort by match score (highest first)
            usort($recommendations, function($a, $b) {
                return $b['match_score'] <=> $a['match_score'];
            });
            
            return $recommendations;
            
        } catch (PDOException $e) {
            error_log("Product getRecommendations error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Calculate weighted match score for a product
     * 
     * Scoring weights:
     * - Temperature: 35%
     * - Humidity: 30%
     * - Season: 35%
     * 
     * @param array $product Product data
     * @param array $conditions User conditions
     * @return array Total score and breakdown
     */
    private function calculateMatchScore($product, $conditions) {
        $scores = [
            'temperature' => 0,
            'humidity' => 0,
            'season' => 0
        ];
        
        // Temperature matching (35% weight)
        if (!empty($conditions['min_temp']) && !empty($conditions['max_temp'])) {
            $scores['temperature'] = $this->calculateRangeMatch(
                $conditions['min_temp'],
                $conditions['max_temp'],
                $product['min_temperature'],
                $product['max_temperature']
            );
        } else {
            $scores['temperature'] = 100; // No preference = perfect match
        }
        
        // Humidity matching (30% weight)
        if (!empty($conditions['min_humidity']) && !empty($conditions['max_humidity'])) {
            $scores['humidity'] = $this->calculateRangeMatch(
                $conditions['min_humidity'],
                $conditions['max_humidity'],
                $product['min_humidity'],
                $product['max_humidity']
            );
        } else {
            $scores['humidity'] = 100; // No preference = perfect match
        }
        
        // Season matching (35% weight)
        if (!empty($conditions['season'])) {
            $scores['season'] = $this->calculateSeasonMatch(
                $conditions['season'],
                $product['growing_season']
            );
        } else {
            $scores['season'] = 100; // No preference = perfect match
        }
        
        // Calculate weighted total
        $total = ($scores['temperature'] * 0.35) +
                 ($scores['humidity'] * 0.30) +
                 ($scores['season'] * 0.35);
        
        return [
            'total' => $total,
            'breakdown' => $scores
        ];
    }
    
    /**
     * Calculate range overlap percentage (fuzzy matching)
     * 
     * @param float $userMin User's minimum value
     * @param float $userMax User's maximum value
     * @param float $productMin Product's minimum value
     * @param float $productMax Product's maximum value
     * @return float Match score (0-100)
     */
    private function calculateRangeMatch($userMin, $userMax, $productMin, $productMax) {
        // If no product data, return neutral score
        if ($productMin === null || $productMax === null) {
            return 50;
        }
        
        // Calculate overlap
        $overlapMin = max($userMin, $productMin);
        $overlapMax = min($userMax, $productMax);
        
        // If ranges overlap
        if ($overlapMin <= $overlapMax) {
            $overlapRange = $overlapMax - $overlapMin;
            $userRange = $userMax - $userMin;
            $productRange = $productMax - $productMin;
            
            // Perfect match if user range is within product range
            if ($userMin >= $productMin && $userMax <= $productMax) {
                return 100;
            }
            
            // Calculate overlap percentage relative to both ranges
            $overlapScore = ($overlapRange / max($userRange, $productRange)) * 100;
            
            // Bonus for center alignment
            $userCenter = ($userMin + $userMax) / 2;
            $productCenter = ($productMin + $productMax) / 2;
            $centerDistance = abs($userCenter - $productCenter);
            $maxDistance = max($userRange, $productRange);
            $centerBonus = (1 - ($centerDistance / $maxDistance)) * 20;
            
            return min(100, $overlapScore + $centerBonus);
        }
        
        // Ranges don't overlap - calculate proximity
        if ($userMax < $productMin) {
            $gap = $productMin - $userMax;
        } else {
            $gap = $userMin - $productMax;
        }
        
        // Penalize based on gap size (max gap tolerance: 20 units)
        $gapPenalty = min(100, ($gap / 20) * 100);
        return max(0, 100 - $gapPenalty);
    }
    
    /**
     * Calculate season compatibility
     * 
     * @param string $userSeason User's preferred season
     * @param string $productSeason Product's growing season
     * @return float Match score (0-100)
     */
    private function calculateSeasonMatch($userSeason, $productSeason) {
        if (empty($productSeason)) {
            return 50; // Neutral if no data
        }
        
        // Normalize seasons
        $userSeason = strtolower(trim($userSeason));
        $productSeason = strtolower(trim($productSeason));
        
        // Year-round products match everything perfectly
        if ($productSeason === 'year-round' || $productSeason === 'all') {
            return 100;
        }
        
        // Exact match
        if ($userSeason === $productSeason) {
            return 100;
        }
        
        // Partial matches (adjacent seasons get bonus)
        $seasonAdjacency = [
            'spring' => ['summer' => 60, 'fall' => 30, 'winter' => 40],
            'summer' => ['spring' => 60, 'fall' => 60, 'winter' => 30],
            'fall' => ['summer' => 60, 'winter' => 60, 'spring' => 30],
            'winter' => ['fall' => 60, 'spring' => 60, 'summer' => 30]
        ];
        
        if (isset($seasonAdjacency[$userSeason][$productSeason])) {
            return $seasonAdjacency[$userSeason][$productSeason];
        }
        
        return 0; // No match
    }

}
