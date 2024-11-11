<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Daftar Buku</h2>
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
                        // Ambil data dari API
                        $response = file_get_contents('http://localhost/rest_buku/book_api.php');
                        
                        // Ubah JSON menjadi array PHP
                        $books = json_decode($response, true);

                        // Tampilkan data dalam tabel
                        if(!empty($books)){
                            foreach($books as $book){
                                echo "<tr>";
                                echo "<td>{$book['id']}</td>";
                                echo "<td>{$book['title']}</td>";
                                echo "<td>{$book['author']}</td>";
                                echo "<td>{$book['year']}</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>Tidak ada data buku</td></tr>";
                        }
                    } catch (Exception $e) {
                        echo "<tr><td colspan='4' class='text-center text-danger'>Error: Tidak dapat mengambil data dari API</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
