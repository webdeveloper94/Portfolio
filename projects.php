<?php
// Ma'lumotlar bazasi bilan oddiy ulanish
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfolio";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Ulanishni tekshirish
if (!$conn) {
    die("Ulanish amalga oshmadi: " . mysqli_connect_error());
}

// Loyihalarni olish
function getProjects() {
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM projects ORDER BY id DESC");
    $projects = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $projects[] = $row;
        }
        mysqli_free_result($result);
    }
    
    return $projects;
}

$projects = getProjects();

// Skript tugagandan so'ng ulanishni yopish
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mening Loyihalarim</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/index.css">
    <link rel="stylesheet" href="assets/projects.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }
        .modal img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
        }
        .close-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 30px;
            color: white;
            cursor: pointer;
        }
        .project-card {
            position: relative;
        }
        .project-card a.project-link {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .project-image {
            position: relative;
            z-index: 2;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <canvas id="particles" class="particles"></canvas>
    
    <div class="container projects-container">
        <a href="index.html" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Bosh Sahifaga Qaytish
        </a>
        
        <div class="projects-grid">
            <?php foreach($projects as $project): ?>
                <div class="project-card">
                    <a href="<?php echo htmlspecialchars($project['link']); ?>" class="project-link" target="_blank"></a>
                    <img src="<?php echo htmlspecialchars($project['image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" class="project-image" onclick="event.stopPropagation(); openModal('<?php echo htmlspecialchars($project['image']); ?>')">
                    <div class="project-content">
                        <h3 class="project-title"><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p class="project-description"><?php echo htmlspecialchars($project['description']); ?></p>
                        <div class="project-tech">
                            <?php 
                            $technologies = explode(',', $project['technologies']);
                            foreach($technologies as $tech): ?>
                                <span class="tech-tag"><?php echo htmlspecialchars(trim($tech)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="imageModal" class="modal">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <img id="modalImg" src="">
    </div>
    
    <script>
        function openModal(imageSrc) {
            document.getElementById("modalImg").src = imageSrc;
            document.getElementById("imageModal").style.display = "flex";
        }
        function closeModal() {
            document.getElementById("imageModal").style.display = "none";
        }
    </script>
</body>
</html>