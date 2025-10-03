<?php
include 'connect.php';

// ambil soal acak 10
$questions = [];
$sql = "SELECT * FROM quiz ORDER BY RAND() LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $soalParts = explode("|||", $row['soal']);
        $questionText = $soalParts[0];
        $media = count($soalParts) > 1 ? $soalParts[1] : null;

        $optionsRaw = explode("|||", $row['pilihan']);
        $options = [];
        foreach ($optionsRaw as $opt) {
            if (strpos($opt, '.webp') !== false) {
                $options[] = ['type' => 'image', 'src' => $opt];
            } else if (strpos($opt, '.mp3') !== false) {
                $options[] = ['type' => 'audio', 'src' => $opt];
            } else {
                $options[] = ['type' => 'text', 'label' => $opt];
            }
        }

        $questions[] = [
            'question' => $questionText,
            'media' => $media,
            'options' => $options,
            'answer' => $row['jawaban'],
            'nilai' => $row['nilai']
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Histonesia - Quiz</title>
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
      min-height: 100vh;
      display: flex;
      flex-direction: column;
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
      color: var(--gold);
    }

    h2 {
      font-size: 1.5rem;
      font-weight: bold;
      color: var(--accent);
    }

    .quiz-container {
      max-width: 650px;
      margin: 30px auto;
      background: var(--paper-bg);
      padding: 20px;
      border-radius: 14px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.25);
      border: 2px solid var(--accent);
      text-align: center;
    }

    .question {
      font-size: 18px;
      margin-bottom: 12px;
      font-weight: bold;
      color: var(--accent);
    }

    .options {
      display: grid;
      gap: 10px;
      margin-top: 12px;
    }

    .option-btn {
      padding: 10px;
      border-radius: 8px;
      border: 2px solid var(--accent);
      background: #fff;
      cursor: pointer;
      transition: 0.3s;
    }
    .option-btn:hover {
      background: var(--accent);
      color: var(--gold);
    }

    .next-btn {
      margin-top: 15px;
      background: var(--accent);
      color: var(--gold);
      border: none;
      padding: 10px 16px;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }
    .next-btn:disabled {
      background: #aaa;
      cursor: not-allowed;
    }
  </style>
</head>
<body>

<!-- Header -->
<header class="shadow-md">
  <div class="max-w-6xl mx-auto flex items-center justify-between p-4">
    <h1 class="text-xl font-bold text-[#FFD700]">Histonesia</h1>
    <nav class="space-x-4">
       <a href="index.html" class="text-[#FFD700] hover:text-white">Beranda</a>
        <a href="tokoh.html" class="text-[#FFD700] hover:text-white">Tokoh</a>
        <a href="timeline.html" class="text-[#FFD700] hover:text-white">Timeline</a>
        <a href="about.html" class="text-[#FFD700] hover:text-white">About</a>
    </nav>
  </div>
</header>

<main class="flex-1">
  <h2 class="mt-6" style="font-size: 2rem;">Quiz Histonesia</h2>
  <div class="quiz-container">
    <div id="quiz">
      <h2 id="question" class="question"></h2>
      <div id="media"></div>
      <div id="options" class="options"></div>
      <button id="next" class="next-btn" disabled>Soal Selanjutnya</button>
    </div>
    <div id="result" style="display:none;"></div>
  </div>
</main>

<!-- Footer -->
<footer class="mt-auto shadow-inner">
  <div class="max-w-6xl mx-auto text-center p-4 text-[#FFD700]">
    © 2025 Histonesia. Semua hak cipta dilindungi.
  </div>
</footer>

<script>
  const questions = <?php echo json_encode($questions); ?>;
let current = 0;
let score = 0;

const qEl = document.getElementById("question");
const mEl = document.getElementById("media");
const optEl = document.getElementById("options");
const nextBtn = document.getElementById("next");
const resultEl = document.getElementById("result");

function loadQuestion() {
  nextBtn.disabled = true;
  const q = questions[current];
  qEl.innerText = q.question;

  // media
  mEl.innerHTML = "";
  if (q.media) {
    if (q.media.includes(".webp")) {
      mEl.innerHTML = `<img src="../assets/img/${q.media}" class="mx-auto my-2" style="max-height:150px;">`;
    } else if (q.media.includes(".mp3")) {
      mEl.innerHTML = `<audio controls class="mx-auto my-2"><source src="../assets/audio/${q.media}" type="audio/mpeg"></audio>`;
    }
  }

  // options
  optEl.innerHTML = "";
  q.options.forEach(opt => {
    let btn;
    if (typeof opt === "string") {
      btn = `<button class="option-btn" data-value="${opt}">${opt}</button>`;
    } else if (opt.type === "image") {
      btn = `<button class="option-btn" data-value="${opt.src}"><img src="../assets/img/${opt.src}" style="max-height:80px;"></button>`;
    } else if (opt.type === "audio") {
      btn = `<button class="option-btn" data-value="${opt.src}"><audio controls><source src="../assets/audio/${opt.src}" type="audio/mpeg"></audio></button>`;
    } else {
      btn = `<button class="option-btn" data-value="${opt.label}">${opt.label}</button>`;
    }
    optEl.innerHTML += btn;
  });

  // event handler pilihan
  document.querySelectorAll(".option-btn").forEach(btn => {
    btn.addEventListener("click", () => {
      document.querySelectorAll(".option-btn").forEach(b => {
        b.style.background = "#fff";
        b.style.color = "#000";
        b.dataset.selected = "false";
      });
      btn.style.background = "var(--accent)";
      btn.style.color = "var(--gold)";
      nextBtn.disabled = false;
      btn.dataset.selected = "true";
    });
  });
}

nextBtn.addEventListener("click", () => {
  const selected = document.querySelector(".option-btn[data-selected='true']");
  if (selected) {
    const answer = selected.dataset.value;
    const q = questions[current];

    // ✅ kalau benar → tambah nilai, kalau salah → skip
    if (answer === q.answer) {
      score += parseInt(q.nilai); 
    }
  }

  current++;
  if (current < questions.length) {
    loadQuestion();
  } else {
    // quiz selesai
    document.getElementById("quiz").style.display = "none";
    resultEl.style.display = "block";
    resultEl.innerHTML = `
      <h2>Quiz Selesai!</h2>
      <p>Nilai Anda: ${score}</p>
      <div style="margin-top:20px;">
        <button onclick="ulangQuiz()" 
          style="padding:10px 16px; margin-right:10px; border:none; border-radius:8px; background:var(--accent); color:var(--gold); cursor:pointer;">
          🔄 Ulang Quiz
        </button>
        <a href="timeline.html" 
          style="padding:13px 16px; margin-right:10px; border:none; border-radius:8px; background:var(--accent); color:var(--gold); cursor:pointer;">
          ⬅️ Kembali
        </a>
      </div>
    `;
  }
});


  function ulangQuiz() {
  current = 0;
  score = 0;
  document.getElementById("quiz").style.display = "block";
  resultEl.style.display = "none";
  loadQuestion();
}


  loadQuestion();
</script>

</body>
</html>
