<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <header style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Kelola Menu</h1>
        <div>
            <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-primary">Dashboard</a>
            <a href="<?= base_url('admin/logout') ?>" class="btn btn-danger">Logout</a>
        </div>
    </header>

    <div class="container">
        <h2>Daftar Menu</h2>
        <div class="orders-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menus as $index => $menu): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($menu['nama_menu']) ?></td>
                        <td><?= ucfirst($menu['kategori']) ?></td>
                        <td>Rp <?= number_format($menu['harga'], 0, ',', '.') ?></td>
                        <td>
                            <input type="number" 
                                   id="stok-<?= $menu['id'] ?>" 
                                   value="<?= $menu['stok'] ?>" 
                                   min="0" 
                                   style="width: 80px; padding: 0.5rem;">
                        </td>
                        <td>
                            <span class="status-badge <?= $menu['status'] == 'tersedia' ? 'status-siap' : 'status-menunggu' ?>">
                                <?= ucfirst($menu['status']) ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-success" onclick="updateStock(<?= $menu['id'] ?>)">
                                Update Stok
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function updateStock(menuId) {
            const stok = document.getElementById('stok-' + menuId).value;

            if (!confirm('Update stok menu ini?')) {
                return;
            }

            fetch('<?= base_url('admin/update-stock') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + menuId + '&stok=' + stok
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Stok berhasil diperbarui');
                    location.reload();
                } else {
                    alert('Gagal memperbarui stok');
                }
            });
        }
    </script>
</body>
</html>