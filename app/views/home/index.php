<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách chi nhánh</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Danh sách chi nhánh</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Tên chi nhánh</th>
            <th>Địa chỉ</th>
            <th>Số điện thoại</th>
            <th>Trạng thái</th>
        </tr>
        <?php foreach ($branches as $b): ?>
        <tr>
            <td><?= $b['ID'] ?></td>
            <td><?= $b['Name'] ?></td>
            <td><?= $b['Address'] ?></td>
            <td><?= $b['Phone'] ?></td>
            <td><?= $b['Status'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
