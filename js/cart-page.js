// ===================================
// ملف عرض السلة - cart-page.js
// ===================================

// عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function () {
    // عرض محتوى السلة
    displayCart();

    // تحديث عداد السلة
    updateCartCount();
});

// ==================================
// وظيفة عرض محتوى السلة
// ==================================
function displayCart() {
    // الحصول على السلة من localStorage
    let cart = getCart();

    // العناصر في الصفحة
    const emptyCart = document.getElementById('empty-cart');
    const cartContent = document.getElementById('cart-content');
    const cartItemsList = document.getElementById('cart-items-list');

    // التحقق من وجود منتجات
    if (cart.length === 0) {
        // إذا كانت السلة فارغة
        emptyCart.style.display = 'block';
        cartContent.style.display = 'none';
    } else {
        // إذا كان هناك منتجات
        emptyCart.style.display = 'none';
        cartContent.style.display = 'block';

        // بناء HTML للمنتجات
        let html = '';
        cart.forEach(function (item) {
            const itemTotal = (parseFloat(item.price) * parseInt(item.quantity)).toFixed(2);

            html += `
                <div class="cart-item">
                    <div class="cart-item-image">
                        <img src="uploads/${item.image}" alt="${item.name}">
                    </div>
                    <div class="cart-item-details">
                        <h4>${item.name}</h4>
                        <p class="item-price">${parseFloat(item.price).toFixed(2)} ريال</p>
                    </div>
                    <div class="cart-item-quantity">
                        <button class="qty-btn" onclick="changeQuantity('${item.id}', -1)">-</button>
                        <input type="number" value="${item.quantity}" min="1" 
                               onchange="setQuantity('${item.id}', this.value)" class="qty-input">
                        <button class="qty-btn" onclick="changeQuantity('${item.id}', 1)">+</button>
                    </div>
                    <div class="cart-item-total">
                        <p class="item-total">${itemTotal} ريال</p>
                        <button class="remove-btn" onclick="removeItem('${item.id}')">حذف 🗑️</button>
                    </div>
                </div>
            `;
        });

        // إضافة HTML للصفحة
        cartItemsList.innerHTML = html;

        // تحديث الملخص
        updateSummary();
    }
}

// ==================================
// وظيفة تحديث ملخص الطلب
// ==================================
function updateSummary() {
    // الحصول على السلة
    let cart = getCart();

    // حساب عدد المنتجات
    let totalItems = 0;
    cart.forEach(function (item) {
        totalItems += parseInt(item.quantity);
    });

    // حساب المجموع الكلي
    const totalPrice = calculateTotal();

    // تحديث العناصر في الصفحة
    document.getElementById('total-items').textContent = totalItems;
    document.getElementById('total-price').textContent = totalPrice + ' ريال';
}

// ==================================
// وظيفة تغيير الكمية
// ==================================
function changeQuantity(productId, change) {
    // الحصول على السلة
    let cart = getCart();

    // البحث عن المنتج
    const product = cart.find(item => item.id === productId);

    if (product) {
        // تحديث الكمية
        const newQuantity = parseInt(product.quantity) + change;

        if (newQuantity > 0) {
            // تحديث الكمية
            updateQuantity(productId, newQuantity);
            // إعادة عرض السلة
            displayCart();
        } else {
            // إذا وصلت الكمية لصفر، نحذف المنتج
            removeItem(productId);
        }
    }
}

// ==================================
// وظيفة تعيين الكمية مباشرة
// ==================================
function setQuantity(productId, newQuantity) {
    newQuantity = parseInt(newQuantity);

    if (newQuantity > 0) {
        // تحديث الكمية
        updateQuantity(productId, newQuantity);
        // إعادة عرض السلة
        displayCart();
    } else {
        // إذا كانت القيمة غير صالحة
        alert('الرجاء إدخال كمية صحيحة');
        displayCart();
    }
}

// ==================================
// وظيفة حذف منتج
// ==================================
function removeItem(productId) {
    // تأكيد الحذف
    if (confirm('هل تريد حذف هذا المنتج من السلة؟')) {
        // حذف المنتج
        removeFromCart(productId);
        // إعادة عرض السلة
        displayCart();
    }
}

// ==================================
// وظيفة تأكيد تفريغ السلة
// ==================================
function confirmClearCart() {
    if (confirm('هل تريد تفريغ السلة بالكامل؟')) {
        clearCart();
        displayCart();
    }
}

// ==================================
// وظيفة الانتقال لنموذج الطلب
// ==================================
function proceedToCheckout() {
    // الحصول على السلة
    let cart = getCart();

    if (cart.length === 0) {
        alert('السلة فارغة!');
        return;
    }

    // إخفاء محتوى السلة
    document.getElementById('cart-content').style.display = 'none';
    // إظهار نموذج الطلب
    document.getElementById('checkout-form').style.display = 'block';
}

// ==================================
// وظيفة العودة للسلة
// ==================================
function backToCart() {
    // إخفاء نموذج الطلب
    document.getElementById('checkout-form').style.display = 'none';
    // إظهار محتوى السلة
    document.getElementById('cart-content').style.display = 'block';
}

// ==================================
// معالجة إرسال النموذج
// ==================================
document.addEventListener('DOMContentLoaded', function () {
    const orderForm = document.getElementById('order-form');

    if (orderForm) {
        orderForm.addEventListener('submit', function (e) {
            // الحصول على السلة
            let cart = getCart();

            // تحويل السلة إلى JSON وإضافتها للحقل المخفي
            document.getElementById('order_data').value = JSON.stringify(cart);
        });
    }
});
