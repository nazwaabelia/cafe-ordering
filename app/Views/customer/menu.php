<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">Cookie & Tea</div>
            <ul class="nav-menu">
                <li><a href="#home" class="nav-link active">Home</a></li>
                <li><a href="#signature" class="nav-link">Our Story</a></li>
                <li><a href="#menu" class="nav-link">Cookie Gallery</a></li>
                <li><a href="#contact" class="nav-link">Contact Us</a></li>
            </ul>
            <div class="cart-icon" onclick="toggleCart()">
                🛒 <span id="cartCount">0</span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1 class="hero-title">Delicious</h1>
            <h2 class="hero-subtitle">SWEET, RICH, FAMOUS.</h2>
            <p class="hero-text">We specialize in the most unique and glamorous cookies, for your party, birthday, or special event.</p>
            <button class="btn-hero" onclick="document.getElementById('menu').scrollIntoView({behavior: 'smooth'})">
                PLACE AN ORDER
            </button>
        </div>
    </section>

    <!-- Signature Cookies Section -->
    <section class="signature-section" id="signature">
        <div class="container">
            <h2 class="section-title">Signature Cookies</h2>
            <p class="section-subtitle">We specialize in the most unique and glamorous cookies, for your party, birthday, or special event.</p>
        </div>
    </section>

    <!-- Menu Section TEH -->
    <section class="menu-section" id="menu">
        <div class="container">
            <h2 class="section-title">Menu Teh</h2>
            <div class="menu-grid-new">
                <?php foreach ($menuTeh as $menu): ?>
                <div class="menu-card-new <?= $menu['status'] == 'habis' ? 'out-of-stock' : '' ?>">
                    <div class="menu-image">
                        <img src="<?= base_url('uploads/' . $menu['gambar']) ?>" alt="<?= esc($menu['nama_menu']) ?>" onerror="this.src='<?= base_url('uploads/placeholder-tea.jpg') ?>'">
                        <?php if ($menu['status'] == 'habis'): ?>
                            <div class="sold-out-badge">HABIS</div>
                        <?php endif; ?>
                    </div>
                    <div class="menu-info">
                        <h3 class="menu-name"><?= esc($menu['nama_menu']) ?></h3>
                        <p class="menu-desc">Mix and Match</p>
                        <div class="menu-footer">
                            <span class="menu-price">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></span>
                            <?php if ($menu['status'] != 'habis'): ?>
                                <div class="menu-qty">
                                    <input type="number" min="1" max="<?= $menu['stok'] ?>" value="1" id="qty-<?= $menu['id'] ?>" class="qty-input">
                                    <button class="btn-add" onclick="addToCart(<?= $menu['id'] ?>, '<?= esc($menu['nama_menu']) ?>', <?= $menu['harga'] ?>, <?= $menu['stok'] ?>)">
                                        +
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <p class="menu-stock">Stok: <?= $menu['stok'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Menu Section COOKIES -->
    <section class="menu-section cookies-section">
        <div class="container">
            <h2 class="section-title">Menu Cookies</h2>
            <div class="menu-grid-new">
                <?php foreach ($menuCookies as $menu): ?>
                <div class="menu-card-new <?= $menu['status'] == 'habis' ? 'out-of-stock' : '' ?>">
                    <div class="menu-image">
                        <img src="<?= base_url('uploads/' . $menu['gambar']) ?>" alt="<?= esc($menu['nama_menu']) ?>" onerror="this.src='<?= base_url('uploads/placeholder-cookie.jpg') ?>'">
                        <?php if ($menu['status'] == 'habis'): ?>
                            <div class="sold-out-badge">HABIS</div>
                        <?php endif; ?>
                    </div>
                    <div class="menu-info">
                        <h3 class="menu-name"><?= esc($menu['nama_menu']) ?></h3>
                        <p class="menu-desc">Mix and Match</p>
                        <div class="menu-footer">
                            <span class="menu-price">Rp <?= number_format($menu['harga'], 0, ',', '.') ?></span>
                            <?php if ($menu['status'] != 'habis'): ?>
                                <div class="menu-qty">
                                    <input type="number" min="1" max="<?= $menu['stok'] ?>" value="1" id="qty-<?= $menu['id'] ?>" class="qty-input">
                                    <button class="btn-add" onclick="addToCart(<?= $menu['id'] ?>, '<?= esc($menu['nama_menu']) ?>', <?= $menu['harga'] ?>, <?= $menu['stok'] ?>)">
                                        +
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                        <p class="menu-stock">Stok: <?= $menu['stok'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar" id="cartSidebar">
        <div class="cart-header">
            <h3>Keranjang Belanja</h3>
            <button class="btn-close" onclick="toggleCart()">×</button>
        </div>
        <div class="cart-body" id="cartItems">
            <p class="empty-cart">Keranjang masih kosong</p>
        </div>
        <div class="cart-footer">
            <div class="cart-total" id="cartTotal">Total: Rp 0</div>
            <button class="btn-checkout" onclick="showOrderForm()">Checkout</button>
            <button class="btn-clear" onclick="clearCart()">Kosongkan</button>
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" onclick="closeAll()"></div>

    <!-- Order Modal -->
    <div class="modal" id="orderModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Form Pemesanan</h2>
                <button class="btn-close" onclick="closeOrderForm()">×</button>
            </div>
            <form id="orderForm">
                <div class="form-group-new">
                    <label>Nama Pelanggan *</label>
                    <input type="text" name="nama_pelanggan" placeholder="Masukkan nama Anda" required>
                </div>
                <div class="form-group-new">
                    <label>Nomor Meja / Area *</label>
                    <input type="text" name="nomor_meja" placeholder="Contoh: Meja 5, Area Outdoor" required>
                </div>
                <div class="form-group-new">
                    <label>Tipe Pesanan *</label>
                    <select name="tipe_pesanan" required>
                        <option value="">Pilih tipe pesanan</option>
                        <option value="dine-in">Makan di Tempat</option>
                        <option value="take-away">Bawa Pulang</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeOrderForm()">Batal</button>
                    <button type="submit" class="btn-submit">Kirim Pesanan</button>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= base_url('js/script.js') ?>"></script>
</body>
</html>