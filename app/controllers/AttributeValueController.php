<?php
class AttributeValueController extends Controller
{
    public function storeAjax()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);

        $attributeId = $input['attribute_id'] ?? '';
        $value = trim($input['value'] ?? '');

        if (empty($attributeId) || empty($value)) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $attrValueModel = $this->model('AttributeValueModel');

        $existing = $this->checkValueExists($attributeId, $value);

        if ($existing) {
            echo json_encode(['success' => false, 'message' => 'Giá trị này đã tồn tại', 'id' => $existing['id']]);
            exit;
        }

        $data = [
            'attribute_id' => $attributeId,
            'value' => $value
        ];

        if ($attrValueModel->create($data)) {
            $newValue = $this->checkValueExists($attributeId, $value);
            echo json_encode(['success' => true, 'data' => $newValue]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Lỗi khi lưu DB']);
        }
        exit;
    }

    private function checkValueExists($attributeId, $value)
    {
        $attrValueModel = $this->model('AttributeValueModel');
        $all = $attrValueModel->getByAttributeId($attributeId);
        foreach ($all as $item) {
            if (mb_strtolower($item['value']) == mb_strtolower($value)) {
                return $item;
            }
        }
        return false;
    }
}
