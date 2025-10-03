CREATE TABLE harapan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(7) NOT NULL,
    avatar INT NOT NULL, -- simpan angka avatar 1-10
    jawaban TEXT NOT NULL,
    pos_x INT NOT NULL, -- posisi random X
    pos_y INT NOT NULL, -- posisi random Y
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
