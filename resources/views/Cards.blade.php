<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>بطاقات الطلاب مع فلترة وإصدار</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              100: '#FFEDD5',
              500: '#F97316',
              600: '#EA580C',
              700: '#C2410C',
            }
          }
        }
      }
    }
  </script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap');
    body {
      font-family: 'Tajawal', sans-serif;
    }
    .card {
      width: 450px;
      height: 300px;
      border: 1px solid #ccc;
      margin: 10px;
      padding: 10px;
      position: relative;
      display: inline-block;
      box-sizing: border-box;
    }
    .qr {
      width: 60px;
      height: 60px;
      position: absolute;
      bottom: 10px;
      right: 10px;
    }
    .photo {
      width: 100px;
      height: 120px;
      border: 1px solid #aaa;
      object-fit: cover;
    }
    .footer {
      background-color: #2A3663;
      color: white;
      position: absolute;
      bottom: 0;
      width: 100%;
      text-align: center;
      font-size: 12px;
      height: 30px;
      line-height: 30px;
    }
  </style>
</head>
<body class="bg-gray-50">
  <div class="flex h-screen">
    @include('layouts.sidebar')

    <div class="flex-1 p-6 overflow-auto">
      <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4">
        <h1 class="text-lg font-bold text-primary-700 flex items-center">
          <span class="ml-2">💳</span>
        إصدار بطاقة الكترونية
        </h1>
      </div>

      <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 p-4">
        <div class="mb-4 flex flex-wrap items-center gap-4">
          <label for="filterClass" class="font-semibold">اختر الفصل الدراسي:</label>
          <select id="filterClass" class="border border-orange-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            <option value="">جميع الفصول</option>
          </select>

          <label class="inline-flex items-center ml-6">
            <input type="checkbox" id="selectAll" class="form-checkbox h-5 w-5 text-primary-600 rounded">
            <span class="mr-2">تحديد الكل</span>
          </label>

          <button id="printBtn" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-lg transition">
            🖨️ إصدار البطاقات
          </button>
        </div>

     <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
  <table class="min-w-full text-right divide-y divide-gray-200">
    <thead class="bg-gray-100 sticky top-0 z-10">
      <tr>
        <th class="p-3 text-sm font-medium text-gray-600">
          <input type="checkbox" id="headerCheckbox" class="rounded focus:ring-primary-500">
        </th>
        <th class="p-3 text-sm font-medium text-gray-600">الصورة</th>
        <th class="p-3 text-sm font-medium text-gray-600">الاسم</th>
        <th class="p-3 text-sm font-medium text-gray-600">الفصل الدراسي</th>
      </tr>
    </thead>
    <tbody id="studentsTableBody" class="bg-white divide-y divide-gray-100"></tbody>
  </table>
</div>

      </div>

      <div id="cardContainer" style="display:none;"></div>
    </div>
  </div>
<!-- مودال رسالة الخطأ -->
<div id="errorModal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full mx-4 text-center animate-scale-in">
    <p id="errorMessage" class="text-orange-600 text-lg mb-4"></p>
    <button id="closeModalBtn" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded">
      إغلاق
    </button>
  </div>
</div>
  <script>
    const students = @json($students);

    const filterClass = document.getElementById('filterClass');
    const studentsTableBody = document.getElementById('studentsTableBody');
    const selectAllCheckbox = document.getElementById('selectAll');
    const headerCheckbox = document.getElementById('headerCheckbox');
    const printBtn = document.getElementById('printBtn');
    const cardContainer = document.getElementById('cardContainer');

    const classes = [...new Set(students.map(s => s.class).filter(c => c && c.trim() !== ''))];
    classes.forEach(cls => {
      const option = document.createElement('option');
      option.value = cls;
      option.textContent = cls;
      filterClass.appendChild(option);
    });

    function displayStudents(filter = '') {
      studentsTableBody.innerHTML = '';
      const filtered = filter ? students.filter(s => s.class === filter) : students;

      filtered.forEach(student => {
        const tr = document.createElement('tr');
      tr.className = 'hover:bg-orange-50 transition text-center text-sm';


        tr.innerHTML = `
          <td class="p-3">
            <input type="checkbox" class="rowCheckbox form-checkbox h-5 w-5 text-primary-600 rounded" data-id="${student.student_id}">
          </td>
          <td class="p-3">
<img src="/storage/${student.image_path ?? 'default.png'}" alt="صورة" class="inline-block w-16 h-20 object-cover border rounded">
          </td>
          <td class="p-3 text-sm">${student.full_name}</td>
          <td class="p-3 text-sm">${student.class ?? 'غير محدد'}</td>
        `;

        studentsTableBody.appendChild(tr);
      });
      updateSelectAllState();
    }

    function updateSelectAllState() {
      const checkboxes = document.querySelectorAll('.rowCheckbox');
      const checkedBoxes = document.querySelectorAll('.rowCheckbox:checked');
      headerCheckbox.checked = (checkboxes.length > 0 && checkboxes.length === checkedBoxes.length);
      selectAllCheckbox.checked = headerCheckbox.checked;
    }

    filterClass.addEventListener('change', () => displayStudents(filterClass.value));
    selectAllCheckbox.addEventListener('change', () => {
      const checkboxes = document.querySelectorAll('.rowCheckbox');
      checkboxes.forEach(cb => cb.checked = selectAllCheckbox.checked);
      headerCheckbox.checked = selectAllCheckbox.checked;
    });
    headerCheckbox.addEventListener('change', () => {
      const checkboxes = document.querySelectorAll('.rowCheckbox');
      checkboxes.forEach(cb => cb.checked = headerCheckbox.checked);
      selectAllCheckbox.checked = headerCheckbox.checked;
    });
    studentsTableBody.addEventListener('change', e => {
      if (e.target.classList.contains('rowCheckbox')) updateSelectAllState();
    });

    // إنشاء وطباعة البطاقات للطلاب المحددين باستخدام QR Code API
    function generateCards() {
      const selectedCheckboxes = document.querySelectorAll('.rowCheckbox:checked');
      if (selectedCheckboxes.length === 0) {
           showErrorModal('يرجى اختيار طالب واحد على الأقل لإصدار البطاقة.');
    return;
      }

      cardContainer.innerHTML = '';

      selectedCheckboxes.forEach(cb => {
        const studentId = cb.dataset.id;
        const student = students.find(s => s.student_id == studentId);

        // إنشاء QR Code باستخدام API مع معرّف الطالب فقط
        const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=${encodeURIComponent(student.student_id)}`;

        const card = document.createElement('div');
        card.className = 'card';

        card.innerHTML = `
          <div class="flex">
<img src="/storage/${student.image_path ?? 'default.png'}" alt="Photo" class="photo mr-4">
            <div>
              <h2 class="font-bold text-lg text-primary-700">مدرسة المستقبل</h2>
              <p><strong>الاسم:</strong> ${student.full_name}</p>
              <p><strong>الصف:</strong> ${student.class ?? 'غير محدد'}</p>
            </div>
          </div>
          <img src="${qrCodeUrl}" alt="QR Code" class="qr">
        `;

        cardContainer.appendChild(card);
      });

      const printWindow = window.open('', '_blank');
      printWindow.document.write(`
        <html lang="ar" dir="rtl">
          <head>
            <title>طباعة البطاقات</title>
            <style>
              body { font-family: 'Tajawal', sans-serif; padding: 20px; }
              .card {
                width: 450px;
                height: 300px;
                border: 1px solid #ccc;
                margin: 10px;
                padding: 10px;
                position: relative;
                display: inline-block;
                box-sizing: border-box;
              }
             .qr {
  width: 100px;
  height: 100px;
  position: absolute;
   bottom: 10px;
  left: 10px;
}

              .photo {
                width: 100px;
                height: 120px;
                border: 1px solid #aaa;
                object-fit: cover;
              }
              .footer {
                background-color: #2A3663;
                color: white;
                position: absolute;
                bottom: 0;
                width: 100%;
                text-align: center;
                font-size: 12px;
                height: 30px;
                line-height: 30px;
              }
              @media print {
                body { padding: 0; }
                .card { page-break-inside: avoid; margin: 0 0 10px 0; }
              }
            </style>
          </head>
          <body>
            ${cardContainer.innerHTML}
            <script>
              window.onload = function() {
                window.print();
                setTimeout(() => window.close(), 500);
              };
            <\/script>
          </body>
        </html>
      `);
      printWindow.document.close();
    }

    displayStudents();

    // حدث زر الطباعة
    printBtn.addEventListener('click', generateCards);
    const errorModal = document.getElementById('errorModal');
const errorMessage = document.getElementById('errorMessage');
const closeModalBtn = document.getElementById('closeModalBtn');

function showErrorModal(message) {
  errorMessage.textContent = message;
  errorModal.classList.remove('hidden');
}

closeModalBtn.addEventListener('click', () => {
  errorModal.classList.add('hidden');
    errorModal.classList.add('flex');
});

  </script>
</body>
</html>
