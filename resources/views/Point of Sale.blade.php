<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Smart Canteen</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer>
    document.addEventListener('DOMContentLoaded', () => {
      const orders = [
        { name: 'T-Bone Steak', qty: 2, price: 33.00 },
        { name: 'Soup of the Day', qty: 1, price: 7.50 },
        { name: 'Pancakes', qty: 2, price: 13.50 }
      ];

      function updateSummary() {
        const subtotal = orders.reduce((sum, item) => sum + item.qty * item.price, 0);
        const discount = 8.00;
        const tax = subtotal * 0.12;
        const total = subtotal - discount + tax;

        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('discount').textContent = `-$${discount.toFixed(2)}`;
        document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;
      }

      function renderOrders() {
        const container = document.getElementById('order-list');
        container.innerHTML = '';
        orders.forEach((item, index) => {
          const div = document.createElement('div');
          div.className = 'flex justify-between items-center border-b pb-2';
          div.innerHTML = `
            <div>
              <div class="font-semibold">${item.name}</div>
              <div class="text-sm text-orange-600">x${item.qty}</div>
            </div>
            <div class="text-right">
              <button class="bg-orange-500 text-white px-2 rounded mr-2" onclick="changeQty(${index}, -1)">-</button>
              <span>$${(item.qty * item.price).toFixed(2)}</span>
                  <button class="bg-orange-500 text-white px-2 rounded" onclick="changeQty(${index}, 1)">+</button>
            </div>
          `;
          container.appendChild(div);
        });
        updateSummary();
      }

      window.changeQty = function(index, delta) {
        orders[index].qty += delta;
        if (orders[index].qty <= 0) orders.splice(index, 1);
        renderOrders();
      }

      renderOrders();
    });
  </script>
</head>
<body class="bg-white text-black">
  <div class="flex h-screen">
      @include('layouts.sidebar')

    <!-- Main Content -->
 <main class="flex-1 p-6 overflow-auto">
      <!-- Categories -->
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="border rounded-lg p-4 text-center hover:bg-orange-50 transition cursor-pointer">
          🍽️ <div class="text-lg font-semibold">Lunch</div>
          <div class="text-sm text-orange-600">Items</div>
        </div>
        <div class="border rounded-lg p-4 text-center hover:bg-orange-50 transition cursor-pointer">
          🥗 <div class="text-lg font-semibold">Salad</div>
          <div class="text-sm text-orange-600">Items</div>
        </div>
        <div class="border rounded-lg p-4 text-center hover:bg-orange-50 transition cursor-pointer">
          🍔 <div class="text-lg font-semibold">Burger</div>
          <div class="text-sm text-orange-600">Items</div>
        </div>
        <div class="border rounded-lg p-4 text-center hover:bg-orange-50 transition cursor-pointer">
          ☕ <div class="text-lg font-semibold">Coffee</div>
          <div class="text-sm text-orange-600">Items</div>
        </div>
        <div class="border rounded-lg p-4 text-center hover:bg-orange-50 transition cursor-pointer">
          🍰 <div class="text-lg font-semibold">Dessert</div>
          <div class="text-sm text-orange-600">Items</div>
        </div>
      </div>

      <!-- Products -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="border rounded-lg p-4 hover:shadow">
          <img src="https://via.placeholder.com/150" class="rounded mb-2 w-full" />
          <div class="font-semibold text-lg">Chicken Salad</div>
          <div class="text-sm text-orange-600">$12.99</div>
        </div>
        <div class="border rounded-lg p-4 hover:shadow">
          <img src="https://via.placeholder.com/150" class="rounded mb-2 w-full" />
          <div class="font-semibold text-lg">Ramen</div>
          <div class="text-sm text-orange-600">$14.99</div>
        </div>
        <div class="border rounded-lg p-4 hover:shadow">
          <img src="https://via.placeholder.com/150" class="rounded mb-2 w-full" />
          <div class="font-semibold text-lg">Steak</div>
          <div class="text-sm text-orange-600">$33.00</div>
        </div>
      </div>
    </main>

    <!-- Order Summary -->
       <aside class="w-80 border-l p-6 bg-white overflow-auto">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-orange-500">Current Order</h2>
        <button class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded" onclick="location.reload()">Clear All</button>
      </div>

      <div id="order-list" class="space-y-4 mb-6"></div>

      <div class="bg-orange-100 text-black p-4 rounded-lg">
        <div class="flex justify-between">
          <span>Subtotal</span>
          <span id="subtotal">$0.00</span>
        </div>
        <div class="flex justify-between">
          <span>Discounts</span>
          <span id="discount">-$0.00</span>
        </div>
        <div class="flex justify-between">
          <span>Tax(12%)</span>
          <span id="tax">$0.00</span>
        </div>
        <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2">
          <span>Total</span>
          <span id="total">$0.00</span>
        </div>
      </div>
    </aside>
  </div>
</body>
</html>

