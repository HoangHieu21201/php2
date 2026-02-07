<?php
class VariantAttributeValueModel extends Model
{
    private $table = 'variant_attribute_values';

    public function add($variantId, $attributeValueId)
    {
        $sql = "INSERT INTO $this->table (product_variant_id, attribute_value_id) VALUES (:variant_id, :value_id)";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':variant_id' => $variantId, ':value_id' => $attributeValueId]);
    }

    public function removeByVariantId($variantId)
    {
        $sql = "DELETE FROM $this->table WHERE product_variant_id = :variant_id";
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':variant_id' => $variantId]);
    }

    public function getAttributesByVariantId($variantId)
    {
        $sql = "SELECT vav.*, av.value, av.attribute_id, a.name as attribute_name
                FROM $this->table vav
                JOIN attribute_values av ON vav.attribute_value_id = av.id
                JOIN attributes a ON av.attribute_id = a.id
                WHERE vav.product_variant_id = :variant_id";
        
        $conn = $this->connect($sql);
        $stmt = $conn->prepare($sql);
        $stmt->execute([':variant_id' => $variantId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}