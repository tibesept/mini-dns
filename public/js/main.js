// public/js/main.js

function formatPrice(price) {
    return new Intl.NumberFormat('ru-RU').format(price);
}

// Добавление в корзину
window.addToCart = function(productId) {
    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('id', productId);

    fetch('/cart_api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('cart-badge').textContent = data.cart_count;
            // Можно добавить тост или уведомление "Добавлено"
        }
    });
};

function loadCart() {
    const container = document.getElementById('cart-content');
    const totalEl = document.getElementById('cart-total-price');
    const btnCheckout = document.getElementById('btn-checkout');
    
    container.innerHTML = `<div class="text-center py-5"><div class="spinner-border text-accent" role="status"></div></div>`;

    fetch('/cart_api.php?action=get')
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('cart-badge').textContent = data.cart_count;
            totalEl.textContent = formatPrice(data.total);
            
            if (data.items.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-cart-x fs-1 d-block mb-3"></i>
                        <p>Корзина пока пуста</p>
                    </div>
                `;
                btnCheckout.disabled = true;
                return;
            }

            btnCheckout.disabled = false;
            let html = '<ul class="list-group list-group-flush mb-0">';
            data.items.forEach(item => {
                html += `
                    <li class="list-group-item py-3 px-0 d-flex align-items-center border-bottom">
                        <img src="/public/uploads/${item.image}" alt="" style="width: 50px; height: 50px; object-fit: contain;" class="me-3 bg-light rounded">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold">${item.name}</h6>
                            <small class="text-muted">${formatPrice(item.price)} ₽</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-sm btn-light px-2" onclick="updateCartQty(${item.id}, ${item.quantity - 1})">-</button>
                            <span class="mx-2 fw-bold" style="min-width: 20px; text-align: center;">${item.quantity}</span>
                            <button class="btn btn-sm btn-light px-2" onclick="updateCartQty(${item.id}, ${item.quantity + 1})">+</button>
                        </div>
                        <div class="ms-4 text-end" style="min-width: 80px;">
                            <div class="fw-bold mb-1">${formatPrice(item.price * item.quantity)} ₽</div>
                            <button class="btn btn-sm btn-link text-danger p-0 text-decoration-none" onclick="removeFromCart(${item.id})"><small>Удалить</small></button>
                        </div>
                    </li>
                `;
            });
            html += '</ul>';
            container.innerHTML = html;
        }
    });
}

window.updateCartQty = function(id, qty) {
    if (qty < 1) {
        removeFromCart(id);
        return;
    }
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('id', id);
    formData.append('qty', qty);

    fetch('/cart_api.php', { method: 'POST', body: formData })
    .then(() => loadCart());
};

window.removeFromCart = function(id) {
    const formData = new FormData();
    formData.append('action', 'remove');
    formData.append('id', id);

    fetch('/cart_api.php', { method: 'POST', body: formData })
    .then(() => loadCart());
};

document.addEventListener('DOMContentLoaded', () => {
    const cartModal = document.getElementById('cartModal');
    
    if (cartModal) {
        cartModal.addEventListener('show.bs.modal', loadCart);
    }

    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Обработка...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('name', document.getElementById('checkout-name').value);
            formData.append('phone', document.getElementById('checkout-phone').value);

            fetch('/checkout.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    btn.innerHTML = '<i class="bi bi-check-circle me-2"></i> Оплата прошла успешно!';
                    btn.classList.remove('btn-accent');
                    btn.classList.add('btn-success');
                    
                    setTimeout(() => {
                        window.location.href = '/index.php?order=success';
                    }, 1500);
                } else {
                    alert(data.message || 'Произошла ошибка при оформлении заказа');
                    btn.innerHTML = 'Оплатить заказ';
                    btn.disabled = false;
                }
            })
            .catch(() => {
                alert('Произошла ошибка соединения');
                btn.innerHTML = 'Оплатить заказ';
                btn.disabled = false;
            });
        });
    }
});
