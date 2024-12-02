<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Daftar Buku</h2>
        
        <!-- Form Pencarian dan Tombol Tambah -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form action="" method="GET" class="d-flex gap-2">
                    <input type="number" name="search_id" class="form-control" 
                           placeholder="Cari berdasarkan ID">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBookModal">
                    Tambah Buku
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tahun</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        // Set URL API
                        $api_url = 'http://localhost/rest_buku/book_api.php';
                        
                        // Cek jika ada pencarian
                        if(isset($_GET['search_id']) && !empty($_GET['search_id'])) {
                            $api_url .= '/' . $_GET['search_id'];
                        }
                        
                        // Konfigurasi timeout
                        $ctx = stream_context_create([
                            'http' => ['timeout' => 5]
                        ]);
                        
                        // Ambil data dari API
                        $response = file_get_contents($api_url, false, $ctx);
                        
                        // Validasi response
                        if ($response === false) {
                            throw new Exception('Gagal mengambil data dari API');
                        }
                        
                        // Validasi JSON
                        $books = json_decode($response, true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            throw new Exception('Invalid JSON response');
                        }

                        // Tampilkan data berdasarkan jenis request
                        if(isset($_GET['search_id'])) {
                            if($books && !isset($books['message'])) {
                                // Tampilkan satu buku
                                echo "<tr>";
                                echo "<td>{$books['id']}</td>";
                                echo "<td>{$books['title']}</td>";
                                echo "<td>{$books['author']}</td>";
                                echo "<td>{$books['year']}</td>";
                                echo "</tr>";
                            } else {
                                // Tampilkan pesan tidak ditemukan
                                echo "<tr><td colspan='4' class='text-center'>";
                                echo "Buku dengan ID tersebut tidak ditemukan";
                                echo "</td></tr>";
                            }
                        } else {
                            // Cek apakah ada data
                            if(!empty($books)) {
                                // Loop untuk setiap buku
                                foreach($books as $book) {
                                    echo "<tr>";
                                    echo "<td>{$book['id']}</td>";
                                    echo "<td>{$book['title']}</td>";
                                    echo "<td>{$book['author']}</td>";
                                    echo "<td>{$book['year']}</td>";
                                    echo "</tr>";
                                }
                            } else {
                                // Tampilkan pesan jika tidak ada data
                                echo "<tr><td colspan='4' class='text-center'>";
                                echo "Tidak ada data buku";
                                echo "</td></tr>";
                            }
                        }
                        
                    } catch (Exception $e) {
                        echo "<tr><td colspan='4' class='text-center text-danger'>";
                        echo "Error: " . $e->getMessage();
                        echo "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Buku -->
    <div class="modal fade" id="addBookModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Buku Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addBookForm">
                        <div class="mb-3">
                            <label class="form-label">Judul</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Penulis</label>
                            <input type="text" name="author" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun Terbit</label>
                            <input type="number" name="year" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="addBook()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function addBook() {
        const form = document.getElementById('addBookForm');
        const formData = new FormData(form);
        const data = {
            title: formData.get('title'),
            author: formData.get('author'),
            year: parseInt(formData.get('year'))
        };

        fetch('http://localhost/rest_buku/book_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if(data.message === "Book created") {
                alert('Buku berhasil ditambahkan!');
                window.location.reload();
            } else {
                alert('Gagal menambahkan buku: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
    }
    </script>
</body>
</html>
