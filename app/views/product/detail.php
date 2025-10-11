<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sản phẩm</title>
</head>
<body>
    <?php if ($data['product']): ?>
        <h1><?= htmlspecialchars($data['product']['name']) ?></h1>
        <p>Giá: <?= number_format($data['product']['price']) ?> VNĐ</p>
        <p><?= htmlspecialchars($data['product']['description']) ?></p>
    <?php else: ?>
        <p>Không tìm thấy sản phẩm.</p>
    <?php endif; ?>
</body>
</html>
