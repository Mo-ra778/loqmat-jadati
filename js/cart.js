// ===================================
// ملف إدارة السلة - cart.js
// ===================================

// عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    
    // تحديث عداد السلة عند تحميل الصفحة
    updateCartCount();
    
    // الحصول على جميع أزرار "إضافة للسلة"
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    // إضافة حدث النقر لكل زر
    addToCartButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // الحصول على بيانات المنتج من data attributes
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            const productPrice = this.getAttribute('data-price');
            const productImage = this.getAttribute('data-image');
            
            // إنشاء كائن المنتج
            const product = {
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage,
                quantity: 1
            };
            
            // إضافة المنتج للسلة
            addToCart(product);
            
            // تغيير نص الزر مؤقتاً
            const originalText = this.innerHTML;
            this.innerHTML = '✓ تمت الإضافة';
            this.style.backgroundColor = '#4caf50';
            
            // إرجاع النص الأصلي بعد ثانية
            setTimeout(() => {
                this.innerHTML = originalText;
                this.style.backgroundColor = '';
            }, 1000);
        });
    });
});

// ==================================
// وظيفة إضافة منتج للسلة
// ==================================
function addToCart(product) {
    // الحصول على السلة من localStorage (أو إنشاء سلة فارغة)
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // التحقق من وجود المنتج في السلة بالفعل
    const existingProductIndex = cart.findIndex(item => item.id === product.id);
    
    if (existingProductIndex > -1) {
        // إذا كان موجوداً، نزيد الكمية
        cart[existingProductIndex].quantity += 1;
    } else {
        // إذا لم يكن موجوداً، نضيفه للسلة
        cart.push(product);
    }
    
    // حفظ السلة في localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // تحديث عداد السلة
    updateCartCount();
}

// ==================================
// وظيفة تحديث عداد السلة
// ==================================
function updateCartCount() {
    // الحصول على السلة من localStorage
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // حساب العدد الإجمالي للمنتجات
    let totalCount = 0;
    cart.forEach(function(item) {
        totalCount += parseInt(item.quantity);
    });
    
    // تحديث العداد في الصفحة
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = totalCount;
        
        // إظهار العداد فقط إذا كان هناك منتجات
        if (totalCount > 0) {
            cartCountElement.style.display = 'inline-block';
        } else {
            cartCountElement.style.display = 'none';
        }
    }
}

// ==================================
// وظيفة الحصول على السلة
// ==================================
function getCart() {
    return JSON.parse(localStorage.getItem('cart')) || [];
}

// ==================================
// وظيفة حذف منتج من السلة
// ==================================
function removeFromCart(productId) {
    // الحصول على السلة
    let cart = getCart();
    
    // تصفية المنتج المراد حذفه
    cart = cart.filter(item => item.id !== productId);
    
    // حفظ السلة المحدثة
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // تحديث عداد السلة
    updateCartCount();
}

// ==================================
// وظيفة تحديث كمية منتج
// ==================================
function updateQuantity(productId, newQuantity) {
    // الحصول على السلة
    let cart = getCart();
    
    // البحث عن المنتج وتحديث الكمية
    const productIndex = cart.findIndex(item => item.id === productId);
    
    if (productIndex > -1) {
        if (newQuantity <= 0) {
            // إذا كانت الكمية صفر أو أقل، نحذف المنتج
            cart.splice(productIndex, 1);
        } else {
            // تحديث الكمية
            cart[productIndex].quantity = parseInt(newQuantity);
        }
    }
    
    // حفظ السلة المحدثة
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // تحديث عداد السلة
    updateCartCount();
}

// ==================================
// وظيفة حساب المجموع الكلي
// ==================================
function calculateTotal() {
    // الحصول على السلة
    let cart = getCart();
    
    // حساب المجموع
    let total = 0;
    cart.forEach(function(item) {
        total += parseFloat(item.price) * parseInt(item.quantity);
    });
    
    return total.toFixed(2);
}

// ==================================
// وظيفة تفريغ السلة
// ==================================
function clearCart() {
    localStorage.removeItem('cart');
    updateCartCount();
}
