<?php include 'connect.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pohon Merdeka - Histonesia</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root{
      --accent: #5B0000;
      --gold: #FFD700;
      --paper-bg: #f8f5ee;
    }

    body {
      font-family: Georgia, "Times New Roman", serif;
      background: linear-gradient(180deg,#e9e7e2 0%, #efeae0 100%);
      margin: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      color: #222;
      text-align: center;
    }

    header{
      background: var(--accent);
      color: var(--gold);
      z-index: 50;
    }

    footer {
      background: var(--accent);
    }

    h2 {
      margin-top: 20px;
      font-size: 1.5rem;
      font-weight: bold;
      color: var(--accent);
    }

    p.subtitle {
      color: #444;
        font-size: 1.1rem;
      margin-bottom: 10px;
    }

    /* Tree */
    .tree {
      position: relative;
      width: 1400px;
      height: 1050px;
      margin: 0px auto;
      background: url('bwn.png') no-repeat center;
      background-size: contain;
    }
    .fruit {
      position: absolute;
      text-align: center;
      cursor: pointer;
    }
    .fruit img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
    }
    .username {
      font-size: 12px;
      margin-top: 2px;
    background-color: var(--accent);
    color : var(--gold);
    border-radius: 6px;
    }

    /* Form kotak */
    .form-box {
      position: fixed;
      top: 50%;
      left: 20px;
      transform: translateY(-50%);
      width: 260px;
      background: var(--paper-bg);
      border-radius: 14px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.25);
      padding: 16px;
      text-align: left;
      z-index: 9999;
      border: 2px solid var(--accent);
    }
    .form-box h3 {
      margin-top: 0;
      font-size: 16px;
      font-weight: bold;
      color: var(--accent);
      margin-bottom: 8px;
    }
    .form-box input,
    .form-box select,
    .form-box textarea {
      width: 100%;
      margin: 6px 0;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 13px;
      background: #fff;
    }
    .form-box button {
      background: var(--accent);
      border: none;
      padding: 8px 12px;
      border-radius: 8px;
      color: var(--gold);
      cursor: pointer;
      font-size: 14px;
      font-weight: bold;
      width: 100%;
    }
    .form-box button:hover {
      background: #7a1111;
      color: #fff;
    }

    .avatar-list {
  display: grid;
  grid-template-columns: repeat(3, 1fr); /* 5 kolom */
  gap: 8px;
  margin: 8px 0;
}

.avatar-item {
  cursor: pointer;
  border: 2px solid transparent;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.avatar-item input {
  display: none;
}

.avatar-item img {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  transition: transform 0.2s, border 0.2s;
}

/* efek hover */
.avatar-item:hover img {
  transform: scale(1.1);
}

/* ketika terpilih */
.avatar-item input:checked + img {
  border: 2px solid var(--accent);
  box-shadow: 0 0 6px rgba(0,0,0,0.3);
}


    /* Modal */
      .popup {
    position: absolute;
    display: none;
    z-index: 9999;
  }
  .popup-content {
    background: var(--paper-bg);
    border: 2px solid var(--accent);
    padding: 10px;
    border-radius: 8px;
    width: 220px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    text-align: left;
  }
  .popup-content h3 {
    margin: 0 0 4px 0;
    font-weight: bold;
    font-size: 14px;
    color: var(--accent);
  }
  .popup-content p {
    font-size: 13px;
    margin: 0;
  }
  .popup-content button {
    margin-top: 6px;
    background: var(--accent);
    color: var(--gold);
    border: none;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
  }
  .popup-content button:hover {
    background: #7a1111;
    color: #fff;
  }
  </style>
</head>
<body>

  <!-- header -->
  <header class="shadow-md">
    <div class="max-w-6xl mx-auto flex items-center justify-between p-4">
      <h1 class="text-xl font-bold text-[#FFD700]">Histonesia</h1>
      <nav class="space-x-4">
            <a href="tokoh.html" class="text-[#FFD700] hover:text-white">Tokoh</a>
            <a href="quiz.php" class="text-[#FFD700] hover:text-white">Quiz</a>
            <a href="about.html" class="text-[#FFD700] hover:text-white">About</a>
         <a href="timeline.html" class="text-[#FFD700] hover:text-white">Timeline</a>
      </nav>
    </div>
  </header>

  <!-- konten -->
  <main class="flex-1">
    <h2 style="font-size: 2rem; font-weight:bolder;  padding-top: 10px;">Pohon Merdeka</h2>
    <p class="subtitle">Apa makna merdeka bagimu?</p>
    <!-- Kotak form fixed -->
    <div class="form-box">
      <h3>Tulis Jawabanmu</h3>
      <form action="submit.php" method="POST">
        <input type="text" name="username" maxlength="7" placeholder="Username (max 7)" required>
        <h3>Pilih Avatar</h3>
        <div class="avatar-list">
        <?php for ($i=1; $i<=9; $i++): ?>
            <label class="avatar-item">
                <input type="radio" name="avatar" value="<?= $i ?>" <?= $i === 1 ? 'checked' : '' ?>>
                <img src="img/avatar<?= $i ?>.png" alt="Avatar <?= $i ?>">
            </label>
            <?php endfor; ?>

        </div>

        <textarea name="jawaban" placeholder="Tulis jawabanmu..." required></textarea>
        <button type="submit">Kirim</button>
      </form>
    </div>

    <!-- Pohon -->
    <div class="tree">
      <?php
      $result = $conn->query("SELECT * FROM harapan");
      while ($row = $result->fetch_assoc()):
      ?>
       <div class="fruit" style="left:<?= $row['pos_x'] ?>px; top:<?= $row['pos_y'] ?>px;" 
            onclick="showModal('<?= htmlspecialchars($row['username']) ?>','<?= htmlspecialchars($row['jawaban']) ?>', this)">
            <img src="img/avatar<?= $row['avatar'] ?>.png" alt="avatar">
            <div class="username"><?= htmlspecialchars($row['username']) ?></div>
        </div>

      <?php endwhile; ?>
    </div>
  </main>

    <!-- Popup dekat buah -->
    <div class="popup" id="popup">
    <div class="popup-content">
        <h3 id="popup-username"></h3>
        <p id="popup-text"></p>
        <button onclick="closePopup()">Tutup</button>
    </div>
    </div>

  <!-- footer -->
  <footer class="mt-auto shadow-inner">
    <div class="max-w-6xl mx-auto text-center p-4 text-[#FFD700]">
      © 2025 Histonesia. Semua hak cipta dilindungi.
    </div>
  </footer>

<script>
function showModal(username, text, el) {
  const popup = document.getElementById('popup');
  const popupUser = document.getElementById('popup-username');
  const popupText = document.getElementById('popup-text');

  popupUser.innerText = username;
  popupText.innerText = text;

  // Posisi buah (avatar)
  const rect = el.getBoundingClientRect();
  const treeRect = document.querySelector('.tree').getBoundingClientRect();

  // Hitung posisi relatif dalam .tree
  const x = rect.left - treeRect.left + rect.width/2;
  const y = rect.top - treeRect.top;

  popup.style.left = (x + 25) + "px"; // -110 supaya popup center di atas buah
  popup.style.top = (y + 25) + "px";   // naik ke atas
  popup.style.display = "block";
}

function closePopup() {
  document.getElementById('popup').style.display = "none";
}

document.querySelector("form").addEventListener("submit", function(e) {
  const username = this.username.value.trim();
  const jawaban = this.jawaban.value.trim();
  const avatar = this.avatar.value; // radio value

  if (!username || !jawaban || !avatar) {
    e.preventDefault(); // stop submit
    alert("Harap isi Username, pilih Avatar, dan masukkan Jawaban terlebih dahulu.");
    return false;
  }
});
</script>

</body>
</html>
