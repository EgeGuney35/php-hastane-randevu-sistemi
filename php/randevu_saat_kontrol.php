<?php
require_once "baglanti.php";

date_default_timezone_set('Europe/Istanbul');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST["date"];
    $department = isset($_POST["department"]) ? htmlspecialchars($_POST["department"]) : "";
    $doctor = isset($_POST["doctor"]) ? htmlspecialchars($_POST["doctor"]) : "";

    if (empty($doctor)) {
        echo json_encode(array("error" => "Doktor seçilmedi"));
        exit;
    }

    $dateTime = DateTime::createFromFormat('d-m-Y', $date);
    $today = new DateTime();

    if ($dateTime !== false) {
        $formattedDate = $dateTime->format('Y-m-d');

        if ($dateTime->format('Y-m-d') < $today->format('Y-m-d')) {
            echo json_encode(array("error" => "Geçmiş tarihler için randevu sorgulanamaz"));
            exit;
        } else {
            $check_sql = "SELECT COUNT(*) AS count FROM doktorlar WHERE id = ?";
            $check_stmt = $baglanti->prepare($check_sql);
            $check_stmt->bind_param("s", $doctor);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            $check_row = $check_result->fetch_assoc();

            if ($check_row['count'] == 0) {
                echo json_encode(array("error" => "Belirtilen doktor bulunamadı"));
                exit;
            } else {
                $sql = "SELECT saat FROM randevu_saatleri WHERE saat NOT IN (
                            SELECT saat FROM randevular 
                            WHERE tarih = ? AND doktor_id = ?)";
                $stmt = $baglanti->prepare($sql);
                $stmt->bind_param("ss", $formattedDate, $doctor);
                $stmt->execute();
                $result = $stmt->get_result();
                $available_times = array();
                $current_time = new DateTime();

                while ($row = $result->fetch_assoc()) {
                    $saat_str = $row['saat'];
                    $saat = DateTime::createFromFormat('H:i', $saat_str);

                    if ($dateTime->format('Y-m-d') == $today->format('Y-m-d') && $saat <= $current_time) {
                        continue;
                    }

                    $available_times[] = $saat_str;
                }

                echo json_encode($available_times);
                exit;
            }
        }
    } else {
        echo json_encode(array("error" => "Geçersiz tarih formatı"));
    }
} else {
    echo json_encode(array("error" => "Geçersiz istek"));
}
