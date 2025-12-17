// Cart management
let cart = [];

function addToCart(id, name, price, maxStock) {
    const qtyInput = document.getElementById('qty-' + id);
    const qty = parseInt(qtyInput.value);

    if (qty <= 0 || qty > maxStock) {
        alert('Jumlah tidak valid');
        return;
    }

    const existingItem = cart.find(item => item.id === id);
    
    if (existingItem) {
        const newQty = existingItem.qty + qty;
        if (newQty > maxStock) {
            alert('Stok tidak mencukupi');
            return;
        }
        existingItem.qty = newQty;
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            qty: qty
        });
    }

    updateCartDisplay();
    qtyInput.value = 1;
    
    // Show notification
    showNotification('Item ditambahkan ke keranjang!');
}

function updateCartDisplay() {
    const cartItems = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');
    const cartCount = document.getElementById('cartCount');

    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="empty-cart">Keranjang masih kosong</p>';
        cartTotal.innerHTML = 'Total: Rp 0';
        cartCount.textContent = '0';
        return;
    }

    let html = '';
    let total = 0;
    let totalItems = 0;

    cart.forEach((item, index) => {
        const subtotal = item.price * item.qty;
        total += subtotal;
        totalItems += item.qty;

        html += `
            <div class="cart-item">
                <div class="cart-item-header">
                    <span class="cart-item-name">${item.name}</span>
                    <button onclick="removeFromCart(${index})" class="btn-remove">Hapus</button>
                </div>
                <div class="cart-item-details">
                    ${item.qty} x Rp ${formatPrice(item.price)}
                </div>
                <div class="cart-item-subtotal">
                    Rp ${formatPrice(subtotal)}
                </div>
            </div>
        `;
    });

    cartItems.innerHTML = html;
    cartTotal.innerHTML = `Total: Rp ${formatPrice(total)}`;
    cartCount.textContent = totalItems;
}

function removeFromCart(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}

function clearCart() {
    if (confirm('Kosongkan keranjang?')) {
        cart = [];
        updateCartDisplay();
    }
}

function toggleCart() {
    const cartSidebar = document.getElementById('cartSidebar');
    const overlay = document.getElementById('overlay');
    
    cartSidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

function closeAll() {
    document.getElementById('cartSidebar').classList.remove('active');
    document.getElementById('overlay').classList.remove('active');
    document.getElementById('orderModal').classList.remove('active');
}

function formatPrice(price) {
    return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function showOrderForm() {
    if (cart.length === 0) {
        alert('Keranjang kosong');
        return;
    }
    
    document.getElementById('orderModal').classList.add('active');
    document.getElementById('overlay').classList.add('active');
}

function closeOrderForm() {
    document.getElementById('orderModal').classList.remove('active');
    if (!document.getElementById('cartSidebar').classList.contains('active')) {
        document.getElementById('overlay').classList.remove('active');
    }
}

function showNotification(message) {
    // Simple alert for now, can be replaced with toast notification
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: #4CAF50;
        color: white;
        padding: 1rem 2rem;
        border-radius: 10px;
        z-index: 3000;
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}

// Order form submission
document.addEventListener('DOMContentLoaded', function() {
    const orderForm = document.getElementById('orderForm');
    
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(orderForm);
            formData.append('items', JSON.stringify(cart));

            fetch('/order/create', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    cart = [];
                    updateCartDisplay();
                    closeAll();
                    orderForm.reset();
                    
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    alert(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim pesanan');
            });
        });
    }
    
    // Smooth scroll for navigation
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.getAttribute('href').startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });
});