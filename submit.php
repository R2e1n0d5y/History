<?php
include 'connect.php';

// Ambil data form
$username = substr($_POST['username'], 0, 7);
$avatar   = intval($_POST['avatar']);
$jawaban  = $_POST['jawaban'];

// Load peta daun (hitam-putih, 0/255)
$treeMask = imagecreatefromjpeg("bw.jpg");
$width  = imagesx($treeMask);
$height = imagesy($treeMask);

// Ambil semua posisi buah yang sudah ada
$positions = [];
$res = $conn->query("SELECT pos_x, pos_y FROM harapan");
while ($row = $res->fetch_assoc()) {
    $positions[] = [$row['pos_x'], $row['pos_y']];
}

// Fungsi cek tabrakan
function isTooClose($x, $y, $positions, $minDist = 60) {
    foreach ($positions as $pos) {
        $dx = $x - $pos[0];
        $dy = $y - $pos[1];
        if (sqrt($dx*$dx + $dy*$dy) < $minDist) {
            return true;
        }
    }
    return false;
}

// Cari posisi valid
do {
    $x = rand(0, $width-1);
    $y = rand(0, $height-1);

    // Cek piksel
    $rgb = imagecolorat($treeMask, $x, $y);
    $col = imagecolorsforindex($treeMask, $rgb);
    $gray = ($col['red'] + $col['green'] + $col['blue']) / 3;

    $validLeaf = ($gray > 200); // artinya putih/daun
    $validDistance = !isTooClose($x, $y, $positions);

} while (!$validLeaf || !$validDistance);

// Simpan ke DB
$sql = "INSERT INTO harapan (username, avatar, jawaban, pos_x, pos_y)
        VALUES ('$username', '$avatar', '$jawaban', '$x', '$y')";

if ($conn->query($sql) === TRUE) {
    header("Location: pohon_kemerdekaan.php");
    exit;
} else {
    echo "Error: " . $conn->error;
}

$conn->close();

?>
