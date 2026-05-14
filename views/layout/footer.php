</main>

<footer class="premium-footer text-light py-4 mt-auto">
    <div class="container text-center">
        <p class="mb-0 text-muted">&copy; <?= date('Y') ?> Mini-DNS. Дипломный проект.</p>
        <small class="text-muted">Разработано с душой.</small>
    </div>
</footer>

<!-- Модальное окно Корзины (общая структура) -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg premium-modal">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title fw-bold" id="cartModalLabel"><i class="bi bi-cart-check me-2 text-accent"></i> Ваша корзина</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="cart-content">
        <!-- Содержимое корзины будет загружаться через JS -->
        <div class="text-center py-5">
            <div class="spinner-border text-accent" role="status"></div>
            <p class="mt-2 text-muted">Загрузка корзины...</p>
        </div>
      </div>
      <div class="modal-footer border-top-0 d-flex justify-content-between align-items-center">
        <div class="fw-bold fs-5">Итого: <span id="cart-total-price">0</span> ₽</div>
        <div>
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Продолжить покупки</button>
            <button type="button" class="btn btn-accent rounded-pill px-4" id="btn-checkout" data-bs-toggle="modal" data-bs-target="#checkoutModal">Оформить заказ</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Модальное окно Оформления заказа -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg premium-modal">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title fw-bold">Оформление заказа</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="checkout-form">
            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase fw-bold">Имя и Фамилия</label>
                <input type="text" class="form-control premium-input" id="checkout-name" required>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase fw-bold">Телефон</label>
                <input type="tel" class="form-control premium-input" id="checkout-phone" required placeholder="+7 (999) 000-00-00">
            </div>
            <div class="alert alert-info rounded-3 mt-4 border-0 bg-accent-light">
                <i class="bi bi-info-circle me-2"></i> Оплата произойдет в демонстрационном режиме.
            </div>
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-accent rounded-pill py-2 fw-bold">Оплатить заказ</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="/public/js/main.js"></script>
</body>
</html>
