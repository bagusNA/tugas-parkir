const totalPaidEl = document.getElementById('total_paid');
const totalBillEl = document.getElementById('total_bill');
const changeEl = document.getElementById('change');
const payButton = document.getElementById('pay_button');
// let error = false;

totalPaidEl.addEventListener('input', (ev) => {
  if (!totalPaidEl || !totalBillEl || !payButton) return;
  const totalPaid = parseInt(totalPaidEl.value);
  const totalBill = parseInt(totalBillEl.value);

  if (!totalPaid || !totalBill || totalPaid < totalBill) {
      payButton.classList.add('disabled');
      payButton.disabled = true;
      totalPaidEl.classList.add('is-invalid');
      changeEl.classList.add('text-danger');
  }
  else {
      payButton.classList.remove('disabled');
      payButton.disabled = false;
      totalPaidEl.classList.remove('is-invalid');
      changeEl.classList.remove('text-danger');
  }

  changeEl.innerHTML = !!(totalPaid - totalBill) ? totalPaid - totalBill : '-'
});