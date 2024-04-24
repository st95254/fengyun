// 全局作用域中的 escapeHTML 函數
function escapeHTML(str) {
    return str.replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
}

document.addEventListener('DOMContentLoaded', () => {
    // Function to fetch history items
    const fetchHistoryItems = () => {
        // Get the history_id parameter from the URL
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const historyId = urlParams.get('history_id');

        // Make a fetch request to the server
        fetch(`../php/get-history-item.php?history_id=${historyId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error:', data.error);
                } else if (data.no_records) {
                    renderHistoryItems([], 0); // No records, pass 0 for shipping fee
                } else {
                    renderHistoryItems(data.items, data.shipping_fee); // Pass shipping fee
                }
            })
            .catch(error => {
                console.error('Error loading history items:', error);
            });
    };

    // Function to render history items
    const renderHistoryItems = (historyItems, shippingFee) => {
        const itemContainer = document.querySelector('.item_container');
        let totalAmount = 0;

        if (historyItems.length === 0) {
            itemContainer.textContent = '沒有購物記錄。';
        } else {
            // Generate HTML for each item and calculate the total amount
            const itemsHtml = historyItems.map(item => {
                totalAmount += item.price * item.quantity;
                return `
                    <div class="item_body">
                        <div class="item"><img src="${escapeHTML(item.image)}" alt="${escapeHTML(item.name)}"></div>
                        <div class="name">${escapeHTML(item.name)}</div>
                        <div class="price"><span>$</span>${escapeHTML(item.price.toString())}</div>
                        <div class="count">${escapeHTML(item.quantity.toString())}</div> 
                        <div class="sum"><span>$</span>${item.total}</div>
                    </div>
                `;
            }).join('');

            itemContainer.innerHTML = itemsHtml;
        }

        // Update the UI for money
        document.getElementById('product').textContent = `$${totalAmount}`;
        document.getElementById('delivery_fee').textContent = `$${shippingFee}`;
        document.getElementById('total').textContent = `$${totalAmount + shippingFee}`;
    };

    // Start by fetching the history items
    fetchHistoryItems();
});
