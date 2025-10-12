<?php
class Home extends Model {
    public function get_Best_Selling_Products() {
        $query = "SELECT 
                        p.*,
                        SUM(od.Quantity) AS Total_Sold
                    FROM 
                        Order_detail od
                    JOIN 
                        Product p ON od.ID_product = p.ID
                    WHERE 
                        p.Status = 'active'
                    GROUP BY 
                        p.ID, p.Name
                    ORDER BY 
                        Total_Sold DESC
                    LIMIT 3;
                    ";
        $result = $this->db->query($query);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
