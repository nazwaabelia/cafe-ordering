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
        <h1>Dashboard Admin</h1>
        <div>
            <a href="<?= base_url('admin/manage-menu') ?>" class="btn btn-primary">Kelola Menu</a>
            <a href="<?= base_url('admin/logout') ?>" class="btn btn-danger">Logout</a>
        </div>
    </header>

    <div class="container">
        <!-- Stats Cards -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3><?= $totalPesanan ?></h3>
                <p>Total Pesanan</p>
            </div>
            <div class="stat-card">
                <h3><?= $pesananMenunggu ?></h3>
                <p>Pesanan Menunggu</p>
            </div>
            <div class="stat-card">
                <h3><?= $pesananHariIni ?></h3>
                <p>Pesanan Hari Ini</p>
            </div>
        </div>

        <!-- Orders Table -->
        <h2>Daftar Pesanan</h2>
        <div class="orders-table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>Nama Pelanggan</th>
                        <th>Meja/Area</th>
                        <th>Tipe</th>
                        <th>Detail Pesanan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 2rem;">
                                Belum ada pesanan
                            </td>
                        </tr>
    
                    <?php else: ?>
                        <?php foreach ($orders as $index => $order): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($order['waktu_pesan'])) ?></td>
                            <td><?= esc($order['nama_pelanggan']) ?></td>
                            <td><?= esc($order['nomor_meja']) ?></td>
                            <td><?= $order['tipe_pesanan'] == 'dine-in' ? 'Makan di Tempat' : 'Bawa Pulang' ?></td>
                            <td>
                                <?php foreach ($order['items'] as $item): ?>
                                    <div><?= $item['name'] ?> x<?= $item['qty'] ?></div>
                                <?php endforeach; ?>
                            </td>
                            <td>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                            <td>
                                <span class="status-badge status-<?= $order['status'] ?>">
                                    <?= ucfirst($order['status']) ?>
                                </span>
                            </td>
                            <td>
                                <select onchange="updateOrderStatus(<?= $order['id'] ?>, this.value)" 
                                        <?= $order['status'] == 'selesai' ? 'disabled' : '' ?>>
                                    <option value="menunggu" <?= $order['status'] == 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                    <option value="diterima" <?= $order['status'] == 'diterima' ? 'selected' : '' ?>>Diterima</option>
                                    <option value="disiapkan" <?= $order['status'] == 'disiapkan' ? 'selected' : '' ?>>Disiapkan</option>
                                    <option value="siap" <?= $order['status'] == 'siap' ? 'selected' : '' ?>>Siap</option>
                                    <option value="selesai" <?= $order['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                </select>
                                <button onclick="deleteOrder(<?= $order['id'] ?>)" class="btn btn-danger" style="margin-top: 0.5rem; padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function updateOrderStatus(orderId, status) {
            if (!confirm('Ubah status pesanan?')) {
                location.reload();
                return;
            }

            fetch('<?= base_url('order/update-status/') ?>' + orderId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'status=' + status
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Status berhasil diubah');
                    location.reload();
                } else {
                    alert('Gagal mengubah status');
                }
            });
        }

        function deleteOrder(orderId) {
            if (!confirm('Hapus pesanan ini? Stok akan dikembalikan.')) {
                return;
            }

            fetch('<?= base_url('admin/delete-order/') ?>' + orderId, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Gagal menghapus pesanan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }

        // Auto refresh setiap 30 detik
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>