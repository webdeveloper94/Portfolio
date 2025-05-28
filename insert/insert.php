<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfolio";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Ulanish amalga oshmadi: " . mysqli_connect_error());
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $title_ru = mysqli_real_escape_string($conn, $_POST['title_ru']);
    $title_en = mysqli_real_escape_string($conn, $_POST['title_en']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $description_ru = mysqli_real_escape_string($conn, $_POST['description_ru']);
    $description_en = mysqli_real_escape_string($conn, $_POST['description_en']);
    $link = mysqli_real_escape_string($conn, $_POST['link']);
    $technologies = mysqli_real_escape_string($conn, $_POST['technologies']);

    $upload_dir = "../photo/";
    $db_dir = "photo/";
    $file_name = basename($_FILES["image"]["name"]);
    $target_file = $upload_dir . $file_name;
    $db_file = $db_dir . $file_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        $message = "Kechirasiz, faqat JPG, JPEG, PNG & GIF fayllar ruxsat etilgan.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = mysqli_real_escape_string($conn, $db_file);
            
            $sql = "INSERT INTO projects (title, title_ru, title_en, description, description_ru, description_en, image, link, technologies) 
                    VALUES ('$title', '$title_ru', '$title_en', '$description', '$description_ru', '$description_en', '$image', '$link', '$technologies')";

            if (mysqli_query($conn, $sql)) {
                $message = "Yangi loyiha muvaffaqiyatli qo'shildi!";
            } else {
                $message = "Xatolik: " . mysqli_error($conn);
            }
        } else {
            $message = "Kechirasiz, faylni yuklashda xatolik yuz berdi.";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yangi Loyiha Qo'shish</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Yangi Loyiha Qo'shish</h2>
        
        <?php if($message): ?>
            <div class="alert alert-info" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Sarlavha (O'zbek tilida)</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="title_ru" class="form-label">Sarlavha (Rus tilida)</label>
                <input type="text" class="form-control" id="title_ru" name="title_ru" required>
            </div>
            <div class="mb-3">
                <label for="title_en" class="form-label">Sarlavha (Ingliz tilida)</label>
                <input type="text" class="form-control" id="title_en" name="title_en" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Tavsif (O'zbek tilida)</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="description_ru" class="form-label">Tavsif (Rus tilida)</label>
                <textarea class="form-control" id="description_ru" name="description_ru" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="description_en" class="form-label">Tavsif (Ingliz tilida)</label>
                <textarea class="form-control" id="description_en" name="description_en" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Rasm</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <div class="mb-3">
                <label for="link" class="form-label">Loyiha Havolasi</label>
                <input type="url" class="form-control" id="link" name="link" required>
            </div>
            <div class="mb-3">
                <label for="technologies" class="form-label">Texnologiyalar (vergul bilan ajrating)</label>
                <input type="text" class="form-control" id="technologies" name="technologies" required>
            </div>
            <button type="submit" class="btn btn-primary">Loyihani Qo'shish</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>