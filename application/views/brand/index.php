function loadBrandTable(page = 1, search = '') {
    $.ajax({
        url: base_url + "brand/fetchBrandData",
        type: "GET",
        data: { 
            page: page,
            search: search
        },
        dataType: "json",
        success: function(response) {
            // ... existing code ...
            // Generate pagination
            let totalPages = Math.ceil(total / 10);
            let paginationHtml = '';
            // ... existing code ...
            if (totalPages > 1) {
                paginationHtml += `
                    <li class="page-item ${page <= 1 ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadBrandTable(${page - 1}, '${search}')">
                            <i class="ti ti-chevron-left"></i>
                        </a>
                    </li>
                `;
                // ... existing code ...
                paginationHtml += `
                    <li class="page-item ${page >= totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="javascript:void(0);" onclick="loadBrandTable(${page + 1}, '${search}')">
                            <i class="ti ti-chevron-right"></i>
                        </a>
                    </li>
                `;
                // ... existing code ...
                $(".pagination").html(paginationHtml).fadeIn();
            } else {
                $(".pagination").hide();
            }
            // ... existing code ...
        },
        // ... existing code ...
    });
} 