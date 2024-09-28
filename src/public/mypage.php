<?php
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=tq_filter; charset=utf8',
    $dbUserName,
    $dbPassword
);

$search = isset($_GET['search']) ? $_GET['search'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'desc';
$specific_date = isset($_GET['specific_date']) ? $_GET['specific_date'] : '';

$sql = 'SELECT * FROM pages WHERE 1=1';

if ($search) {
    $sql .= ' AND (name LIKE :search OR contents LIKE :search)';
}

if ($specific_date) {
    $sql .= ' AND DATE(created_at) = :specific_date';
}

$sql .= ' ORDER BY created_at ' . ($order === 'asc' ? 'ASC' : 'DESC');

$statement = $pdo->prepare($sql);

if ($search) {
    $statement->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}

if ($specific_date) {
    $statement->bindValue(':specific_date', $specific_date, PDO::PARAM_STR);
}

$statement->execute();
$pages = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>マイページ</title>
</head>

<body>
  <div>
    <div>
      <form action="mypage.php" method="get">
        <div>
          <input type="text" name="search" value="<?php echo htmlspecialchars(
              $search,
              ENT_QUOTES,
              'UTF-8'
          ); ?>" placeholder="検索ワード">
        </div>
        <div>
          <label>
            <input type="radio" name="order" value="desc" <?php echo $order ===
            'desc'
                ? 'checked'
                : ''; ?>>
            <span>新着順</span>
          </label>
          <label>
            <input type="radio" name="order" value="asc" <?php echo $order ===
            'asc'
                ? 'checked'
                : ''; ?>>
            <span>古い順</span>
          </label>
        </div>
        <div>
          <label>作成日：<input type="date" name="specific_date" value="<?php echo htmlspecialchars(
              $specific_date,
              ENT_QUOTES,
              'UTF-8'
          ); ?>"></label>
        </div>
        <button type="submit">検索・並び替え</button>
      </form>
    </div>
    
    <div>
      <table border="1">
        <tr>
          <th>タイトル</th>
          <th>内容</th>
          <th>作成日時</th>
        </tr>
        <?php foreach ($pages as $page): ?>
          <tr>
            <td><?php echo htmlspecialchars(
                $page['name'],
                ENT_QUOTES,
                'UTF-8'
            ); ?></td>
            <td><?php echo htmlspecialchars(
                $page['contents'],
                ENT_QUOTES,
                'UTF-8'
            ); ?></td>
            <td><?php echo htmlspecialchars(
                $page['created_at'],
                ENT_QUOTES,
                'UTF-8'
            ); ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</body>

</html>