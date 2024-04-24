document.addEventListener('DOMContentLoaded', () => {
    const fetchHistoryItems = () => {
        fetch('../php/get-history.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.message);
                } else if (data.no_records) {
                    renderHistoryItems([]); // No history records found, pass an empty array
                } else {
                    renderHistoryItems(data.items); // Use the server's returned data to render history records
                }
            })
            .catch(error => {
                console.error('Error loading history:', error);
            });
    };

    const escapeHTML = (str) => {
        return str.replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
    }

    const renderHistoryItems = (historyItems) => {
        const container = document.querySelector('.container');
        if (historyItems.length === 0) {
            container.textContent = '沒有購物記錄。';
            return;
        }

        const itemsHtml = historyItems.map(item => {
            const safeTradeNo = escapeHTML(item.trade_no);
            const dateOnly = item.date.split(' ')[0];
            const safeTotal = escapeHTML(item.total.toString());
            const safeStatus = escapeHTML(item.status);
            const historyId = item.id;

            return `
                <div class="item_body">
                    <div class="trade_no">${safeTradeNo}</div>
                    <div class="date">${dateOnly}</div>
                    <div class="total"><span>$</span>${safeTotal}</div>
                    <div class="status">${safeStatus}</div>
                    <button onclick="window.location.href='history-item.php?history_id=${historyId}'" id="btn">查看訂單</button>
                </div>
            `;
        }).join('');

        container.innerHTML += itemsHtml; // append the items after the existing content, which is the header

    };

    fetchHistoryItems();
});
