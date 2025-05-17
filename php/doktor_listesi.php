<?php
require_once "baglanti.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department = htmlspecialchars($_POST["department"]);

    $sql = "SELECT id, isim_soyisim AS isim FROM doktorlar WHERE bolum_id = (
                SELECT id FROM poliklinikler WHERE ad = ?)";
    $stmt = $baglanti->prepare($sql);
    $stmt->bind_param("s", $department);
    $stmt->execute();
    $result = $stmt->get_result();

    $doctors = array();
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }

    echo json_encode($doctors);
}
?>