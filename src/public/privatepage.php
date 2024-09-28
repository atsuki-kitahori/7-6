<?php
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=tq_filter; charset=utf8',
    $dbUserName,
    $dbPassword
);

$order = isset($_GET['order']) ? $_GET['order'] : 'desc';

$sql =
    'SELECT * FROM pages ORDER BY created_at ' .
    ($order === 'asc' ? 'ASC' : 'DESC');
$statement = $pdo->prepare($sql);
$statement->execute();
$pages = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>top画面</title>
</head>

<body>
  <div>
    <div>
      <form action="privatepage.php" method="get">
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
        <button type="submit">並び替え</button>
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