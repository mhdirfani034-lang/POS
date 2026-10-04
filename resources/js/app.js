import './bootstrap';

const pos = document.querySelector('[data-pos]');

if (pos) {
    const cart = new Map();
    const form = pos.querySelector('[data-cart-form]');
    const rows = pos.querySelector('[data-cart-rows]');
    const empty = pos.querySelector('[data-cart-empty]');
    const total = pos.querySelector('[data-cart-total]');
    const count = pos.querySelector('[data-cart-count]');
    const hidden = pos.querySelector('[data-cart-inputs]');
    const formatMoney = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 2,
    });

    const renderCart = () => {
        rows.replaceChildren();
        hidden.replaceChildren();
        let itemCount = 0;
        let grandTotal = 0;

        [...cart.values()].forEach((item, index) => {
            itemCount += item.quantity;
            grandTotal += item.price * item.quantity;

            const row = document.createElement('div');
            row.className = 'flex items-start justify-between gap-3 border-b border-slate-100 py-4';
            const details = document.createElement('div');
            details.className = 'min-w-0';
            const name = document.createElement('p');
            name.className = 'truncate text-sm font-semibold text-slate-800';
            name.textContent = item.name;
            const unitPrice = document.createElement('p');
            unitPrice.className = 'mt-1 text-xs text-slate-500';
            unitPrice.textContent = `${formatMoney.format(item.price / 100)} / item`;
            const controls = document.createElement('div');
            controls.className = 'mt-2 inline-flex items-center gap-2 rounded-lg border border-slate-200 p-1';
            const decrement = document.createElement('button');
            decrement.type = 'button';
            decrement.className = 'grid h-6 w-6 place-items-center rounded text-sm hover:bg-slate-100';
            decrement.textContent = '−';
            decrement.setAttribute('aria-label', `Kurangi ${item.name}`);
            decrement.addEventListener('click', () => {
                if (item.quantity === 1) cart.delete(item.id);
                else item.quantity -= 1;
                renderCart();
            });
            const quantity = document.createElement('span');
            quantity.className = 'min-w-5 text-center text-xs font-semibold';
            quantity.textContent = String(item.quantity);
            const increment = document.createElement('button');
            increment.type = 'button';
            increment.className = 'grid h-6 w-6 place-items-center rounded text-sm hover:bg-slate-100';
            increment.textContent = '+';
            increment.setAttribute('aria-label', `Tambah ${item.name}`);
            increment.addEventListener('click', () => {
                if (item.quantity < item.stock) item.quantity += 1;
                renderCart();
            });
            controls.append(decrement, quantity, increment);
            details.append(name, unitPrice, controls);

            const lineTotal = document.createElement('span');
            lineTotal.className = 'shrink-0 text-sm font-bold text-slate-800';
            lineTotal.textContent = formatMoney.format(item.price * item.quantity / 100);
            row.append(details, lineTotal);
            rows.append(row);

            const productId = document.createElement('input');
            productId.type = 'hidden';
            productId.name = `items[${index}][product_id]`;
            productId.value = item.id;
            const productQuantity = document.createElement('input');
            productQuantity.type = 'hidden';
            productQuantity.name = `items[${index}][quantity]`;
            productQuantity.value = item.quantity;
            hidden.append(productId, productQuantity);
        });

        empty.classList.toggle('hidden', cart.size > 0);
        count.textContent = String(itemCount);
        total.textContent = formatMoney.format(grandTotal / 100);
    };

    pos.querySelectorAll('[data-add-product]').forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.dataset.productId;
            const item = cart.get(id);
            if (item) {
                if (item.quantity >= item.stock) return;
                item.quantity += 1;
            } else {
                cart.set(id, {
                    id,
                    name: button.dataset.productName,
                    price: Number(button.dataset.productPrice),
                    stock: Number(button.dataset.productStock),
                    quantity: 1,
                });
            }
            renderCart();
        });
    });

    const search = pos.querySelector('[data-product-search]');
    search?.addEventListener('input', () => {
        const term = search.value.trim().toLocaleLowerCase('id-ID');
        pos.querySelectorAll('[data-product-card]').forEach((card) => {
            card.classList.toggle('hidden', !card.dataset.searchText.includes(term));
        });
    });

    form.addEventListener('submit', (event) => {
        if (cart.size === 0) {
            event.preventDefault();
            empty.classList.remove('hidden');
            return;
        }

        const submit = form.querySelector('[data-submit-order]');
        submit.disabled = true;
        submit.textContent = 'Menyimpan transaksi…';
    });
}
