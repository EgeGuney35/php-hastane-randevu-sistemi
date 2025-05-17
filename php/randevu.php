<?php
require_once "baglanti.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST["name"]) ? htmlspecialchars($_POST["name"]) : "";
    $tc = isset($_POST["tc"]) ? htmlspecialchars($_POST["tc"]) : "";
    $phone = isset($_POST["phone"]) ? preg_replace('/[^0-9]/', '', $_POST["phone"]) : "";
    $department = isset($_POST["department"]) ? htmlspecialchars($_POST["department"]) : "";
    $doctor = isset($_POST["doctor"]) ? intval($_POST["doctor"]) : "";

    $date = isset($_POST["date"]) ? $_POST["date"] : "";
    if (!empty($date)) {
        $dateTime = DateTime::createFromFormat('d-m-Y', $date);
        if ($dateTime !== false) {
            $date = $dateTime->format('Y-m-d');
        } else {
            $error_message = "Geçersiz tarih formatı.";
        }
    } else {
        $date = "";
    }

    $time = isset($_POST["time"]) ? htmlspecialchars($_POST["time"]) : "";
    $message = isset($_POST["message"]) ? htmlspecialchars($_POST["message"]) : "";

    if (empty($name) || empty($phone) || empty($tc) || empty($department) || empty($doctor) || empty($date) || empty($time)) {
        $error_message = "Tüm alanları doldurun.";
    } else {
        if (strlen($tc) != 11 || !ctype_digit($tc)) {
            $error_message = "Geçersiz T.C. kimlik numarası.";
        } else {
            // Randevu kontrolü (aynı bölüm)
            $sql = "SELECT * FROM randevular WHERE polikinlik = ? AND tarih = ? AND tc = ?";
            $stmt = $baglanti->prepare($sql);
            $stmt->bind_param("sss", $department, $date, $tc);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error_message = "Aynı gün içinde bu bölümde başka bir randevu oluşturulamaz.";
            } else {
                $inserted = randevuEkle($baglanti, $name, $tc, $phone, $department, $doctor, $date, $time, $message);
                if ($inserted) {
                    $success_message = "Randevu başarıyla kaydedildi.";
                } else {
                    $error_message = "Randevu kaydedilirken bir hata oluştu.";
                }
            }
        }
    }
}

function randevuEkle($connection, $name, $tc, $phone, $department, $doctor, $date, $time, $message)
{
    $sql = "INSERT INTO randevular (isim_soyisim, tel_no, polikinlik, doktor_id, tc, tarih, saat, sikayet) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sissssss", $name, $phone, $department, $doctor, $tc, $date, $time, $message);
    return $stmt->execute();
}

$sql = "SELECT * FROM poliklinikler";
$result = $baglanti->query($sql);

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Randevu Sistemi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="../css/randevu.css">

    <style>
        #messageBox {
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .message {
            margin: 0;
        }

        .message.success {
            color: #155724;
            background-color: #d4edda;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 0;
        }

        .message.error {
            color: #721c24;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="../img/logo.png" alt="Logo">
    </div>

    <div class="container">
        <h2>Randevu Sistemi</h2>

        <?php if (!empty($success_message) || !empty($error_message)): ?>
            <div id="messageBox">
                <?php if (!empty($success_message)): ?>
                    <p class="message success"><?php echo $success_message; ?></p>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <p class="message error"><?php echo $error_message; ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
            <div class="form-group">
                <input name="name" type="text" placeholder="İsim Soyisim"
                    value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
            </div>
            <div class="form-group">
                <input name="tc" type="text" id="tc" placeholder="T.C. Kimlik Numarası" maxlength="11"
                    value="<?php echo isset($tc) ? htmlspecialchars($tc) : ''; ?>">
            </div>
            <div class="form-group">
                <input name="phone" type="text" id="phone" placeholder="İletişim Numarası"
                    value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
            </div>
            <div class="form-group">
                <select name="department" id="department" class="nice-select">
                    <option value="" selected disabled>Poliklinik Seçiniz</option>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['ad']); ?>"><?php echo htmlspecialchars($row['ad']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <select name="doctor" id="doctor" class="nice-select">
                    <option value="" selected disabled>Lütfen önce poliklinik seçiniz</option>
                </select>
            </div>

            <div class="form-group">
                <input name="date" type="text" placeholder="Tarih" id="datepicker" class="form-control">
            </div>
            <div class="form-group">
                <select name="time" id="time" class="nice-select">
                    <option value="" selected disabled>Lütfen bir saat seçiniz</option>
                </select>
            </div>
            <div class="form-group">
                <textarea name="message" maxlength="300" placeholder="Şikayetiniz"><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Randevu Oluştur</button>
            </div>
        </form>
        <a href="../index.html" class="back-button">Geri Dön</a>
    </div>

    <div class="container">
        <h2>Randevu Sorgula</h2>

        <form class="form" method="post" action="randevu_sorgula.php" onsubmit="return validateSorgulaForm()">
            <div class="form-group">
                <input id="tcc" name="tcc" type="text" placeholder="T.C. Kimlik Numarası" maxlength="11">
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Randevu Sorgulama</button>
            </div>
        </form>
    </div>

    <a href="yetkili_giris.php" class="authority-button">Yetkili Sistemi</a>
    <a href="doktor_giris.php" class="doctor-button">Doktor Sistemi</a>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        function validateForm() {
            var nameInput = document.querySelector('input[name="name"]');
            var phoneInput = document.querySelector('input[name="phone"]');
            var tcInput = document.querySelector('input[name="tc"]');
            var messageInput = document.querySelector('textarea[name="message"]');
            var errorMessage = "";

            if (/[^a-zA-ZğüşıöçĞÜŞİÖÇ\s]/.test(nameInput.value) || !nameInput.value.trim()) {
                errorMessage += "Yanlış veri girişi, lütfen isim soyisim kısmını kontrol edin.<br>";
            }

            if (/[^0-9\s()-]/.test(phoneInput.value)) {
                errorMessage += "Yanlış veri girişi, lütfen iletişim numarasını kontrol edin.<br>";
            }

            var tcValue = tcInput.value.trim();
            if (tcValue.length !== 11 || isNaN(tcValue)) {
                errorMessage += "Lütfen geçerli bir T.C. Kimlik Numarası giriniz (11 haneli).<br>";
            }

            if (messageInput && /[^a-zA-Z0-9ğüşıöçĞÜŞİÖÇ\s,.?!]/.test(messageInput.value)) {
                errorMessage += "Yanlış veri girişi, lütfen şikayet kısmını kontrol edin.<br>";
            }

            if (errorMessage) {
                var messageBox = document.getElementById("messageBox");
                if (!messageBox) {
                    messageBox = document.createElement('div');
                    messageBox.id = 'messageBox';
                    var container = document.querySelector('.container');
                    container.insertAfter(messageBox, container.firstChild.nextSibling);
                }
                messageBox.innerHTML = '<p class="message error">' + errorMessage + '</p>';
                return false;
            }
            return true;
        }

        function validateSorgulaForm() {
            var tcInput = document.getElementById("tcc");
            var tcValue = tcInput.value.trim();

            if (tcValue.length !== 11 || isNaN(tcValue)) {
                var errorMessage = "Lütfen geçerli bir T.C. Kimlik Numarası giriniz (11 haneli).";
                var messageBox = document.getElementById("messageBox");
                if (!messageBox) {
                    messageBox = document.createElement('div');
                    messageBox.id = 'messageBox';
                    var container = document.querySelector('.container');
                    container.insertAfter(messageBox, container.firstChild.nextSibling);
                }
                messageBox.innerHTML = '<p class="message error">' + errorMessage + '</p>';
                return false;
            }

            return true;
        }

        $(document).ready(function () {
            $('#department').on('change', function () {
                var department = $('#department').val();
                if (department) {
                    $.ajax({
                        type: 'POST',
                        url: 'doktor_listesi.php',
                        data: {
                            department: department
                        },
                        success: function (response) {
                            var doctorSelect = $('#doctor');
                            doctorSelect.empty();
                            doctorSelect.append('<option value="" selected disabled>Lütfen bir doktor seçiniz</option>');

                            try {
                                var doctors = JSON.parse(response);

                                if (doctors.length > 0) {
                                    doctors.forEach(function (doctor) {
                                        doctorSelect.append('<option value="' + doctor.id + '">' + doctor.isim + '</option>');
                                    });
                                } else {
                                    doctorSelect.append('<option disabled>Doktor bulunamadı</option>');
                                }
                            } catch (error) {
                                console.error("Yanıtı işlerken hata oluştu: ", error);
                                console.error("Dönen yanıt: ", response);
                            }
                        }
                    });
                }
            });

            $('#tc').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
            });

            $('#phone').mask('0 (000) 000 00 00');

            $('#datepicker').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true,
                autoclose: true
            });

            $('#department, #datepicker, #doctor').on('change', function () {
                var date = $('#datepicker').val();
                var department = $('#department').val();
                var doctor = $('#doctor').val();

                if (date && department && doctor) {
                    $.ajax({
                        type: 'POST',
                        url: 'randevu_saat_kontrol.php',
                        data: {
                            date: date,
                            department: department,
                            doctor: doctor
                        },
                        success: function (response) {
                            var timeSelect = $('#time');
                            timeSelect.empty();
                            timeSelect.append('<option value="" selected disabled>Lütfen bir saat seçiniz</option>');

                            try {
                                var times = JSON.parse(response);

                                if (times.length > 0) {
                                    times.forEach(function (time) {
                                        timeSelect.append('<option value="' + time + '">' + time + '</option>');
                                    });
                                } else {
                                    timeSelect.append('<option disabled>Uygun saat bulunamadı</option>');
                                }
                            } catch (error) {
                                console.error("Yanıtı işlerken hata oluştu: ", error);
                                console.error("Dönen yanıt: ", response);
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
