<?php
include("connect.php");
if (isset($_POST['search'])) {
    $limit = 5;
    $name = $_POST['search'];
    $sql = "SELECT user_fname,user_id FROM user_infor WHERE user_fname LIKE :name LIMIT :limit";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => "%$name%", 'limit' => $limit]);
    $result = $stmt->fetchall();
    echo '<ul id="searchbox">';
    foreach ($result as $row) {; ?>

        <li onclick='fill("<?php echo $row['user_fname']; ?>","<?php echo $row['user_id']; ?>")'>
            <a>
                <?php echo $row['user_fname']; ?>
            </a>
        </li>
<?php
    }
    echo '</ul>';
} ?>