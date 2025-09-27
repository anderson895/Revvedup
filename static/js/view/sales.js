// THIS CODE IS GOOD FOR TEMPLATING FETCHING FOR PAGINATION LIMITER

let currentPage = 1;
const rowsPerPage = 10; // limit per page
let filterKeyword = "";

// Fetch data from server with pagination & filter
function fetchTransactions(page = 1, limit = rowsPerPage, filter = "") {
    $.ajax({
        url: "../controller/end-points/controller.php",
        method: "GET",
        data: { 
            requestType: "fetch_all_transaction",
            page: page,
            limit: limit,
            filter: filter
        },
        dataType: "json",
        success: function(res) {
            if(res.status === 200) {
                const transactions = res.data.transactions;
                const totalRows = res.data.totalRows;

                if(transactions.length > 0) {
                    renderTable(transactions);
                    renderPagination(totalRows);
                } else {
                    $("#transactionTableBody").html(`
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-400 italic">
                                <span class="material-icons" style="font-size: 48px; display: block; margin-bottom: 8px;">
                                    shopping_cart
                                </span>
                                No transaction found
                            </td>
                        </tr>
                    `);
                    $("#paginationControls").html("");
                }
            }
        }
    });
}

// Render table rows
function renderTable(data) {
    let html = "";
    data.forEach((item) => {
        const date = new Date(item.transaction_date);
        const formattedDate = date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });

        html += `
            <tr class="hover:bg-gray-200 transition-colors">
                <td class="p-3 text-left font-mono">${formattedDate}</td>
                <td class="p-3 text-left font-semibold">${item.transaction_id}</td>
                <td class="p-3 text-left font-semibold">₱ ${parseFloat(item.transaction_total).toFixed(2)}</td>
                <td class="p-3 text-center">
                    <button class="bg-red-800 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded view-btn" data-id="${item.transaction_id}">
                        View
                    </button>
                </td>
            </tr>
        `;
    });

    $("#transactionTableBody").html(html);

    $(".view-btn").on("click", function () {
        const transactionId = $(this).data("id");
        window.open(`receipt?transaction_id=${transactionId}`, "_blank");
    });
}

// Render pagination buttons
function renderPagination(totalRows) {
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    let paginationHtml = "";

    paginationHtml += `
        <button class="px-3 py-1 mx-1 rounded-md border ${currentPage === 1 ? "bg-gray-300 text-gray-500 cursor-not-allowed" : "bg-white hover:bg-gray-100 text-gray-700"} prev-btn" ${currentPage === 1 ? "disabled" : ""}>
            ‹ Prev
        </button>
    `;

    for (let i = 1; i <= totalPages; i++) {
        paginationHtml += `
            <button class="px-3 py-1 mx-1 rounded-md border ${i === currentPage ? "bg-red-800 text-white border-red-800" : "bg-white hover:bg-gray-100 text-gray-700"} page-btn" data-page="${i}">
                ${i}
            </button>
        `;
    }

    paginationHtml += `
        <button class="px-3 py-1 mx-1 rounded-md border ${currentPage === totalPages ? "bg-gray-300 text-gray-500 cursor-not-allowed" : "bg-white hover:bg-gray-100 text-gray-700"} next-btn" ${currentPage === totalPages ? "disabled" : ""}>
            Next ›
        </button>
    `;

    $("#paginationControls").html(`<div class="flex justify-center mt-4">${paginationHtml}</div>`);

    // Button events
    $(".page-btn").on("click", function () {
        currentPage = parseInt($(this).data("page"));
        fetchTransactions(currentPage, rowsPerPage, filterKeyword);
    });

    $(".prev-btn").on("click", function () {
        if (currentPage > 1) {
            currentPage--;
            fetchTransactions(currentPage, rowsPerPage, filterKeyword);
        }
    });

    $(".next-btn").on("click", function () {
        if (currentPage < totalPages) {
            currentPage++;
            fetchTransactions(currentPage, rowsPerPage, filterKeyword);
        }
    });
}

// Search box
$("#transactionSearch").on("input", function () {
    filterKeyword = $(this).val();
    currentPage = 1;
    fetchTransactions(currentPage, rowsPerPage, filterKeyword);
});

// Initial fetch
fetchTransactions(currentPage, rowsPerPage, filterKeyword);
