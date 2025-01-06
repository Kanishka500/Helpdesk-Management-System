<table class="tableview1">
    <thead>
        <tr>
            <th>Action Date</th>
            <th>Poisition Ky</th>
            <th>Poisition</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("../includes/connect.php");
    $setid = $_POST['setid'];
    $sql = "SELECT * FROM job_position WHERE complain_id=:setid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['setid' => $setid]);
    $result = $stmt->fetchall();
    foreach ($result as $row) {
?>
        <tr>
            <td><?php echo $row['jobre_date']; ?></td>
            <td><?php echo $row['jobre_key']; ?></td>
            <td><?php echo $row['jobre_title']; ?></td>
            <td><?php echo $row['jobre_status']; ?></td>
        </tr>
<?php
}
$pdo=null;
}
?>
</tbody>
</table>
