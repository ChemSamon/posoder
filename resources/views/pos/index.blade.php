@extends('layouts.app1')

@section('content')
<div class="row pt-3 px-3">
    <div class="col-md-8">
        <div class="mb-3 d-md-none">
            <input type="text" id="productSearch" class="form-control shadow-sm" placeholder="{{ __('messages.Search for a product') }}..." onkeyup="filterProducts()">
        </div>

        <div class="container mb-3 px-0">
            <div class="d-flex flex-wrap justify-content-start gap-2 category-btns">
                @foreach ($categories as $category)
                    <a href="{{ route('pos', ['category_id' => $category->id]) }}">
                        <button class="btn btn-sm px-3 {{ request('category_id') == $category->id ? 'btn-danger text-white' : 'btn-outline-secondary' }}">
                            {{ $category->name }}
                        </button>
                    </a>
                @endforeach
            </div>
        </div>

        <div style="max-height: calc(100vh - 160px); overflow-y: auto;" class="pe-2">
            <div class="row g-3" id="productGrid">
                @foreach ($products as $product)    
                    @php 
                        $exchangeRate = 4000; // 1 USD = 4000៛
                        $priceInRiel = $product->price * $exchangeRate;
                    @endphp
                    <div class="col-6 col-md-3 col-lg-3">
                        <div class="product-card">
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="mb-1">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center mb-1" style="height:110px; border-radius: 8px;">
                                            <span class="text-muted">No Image</span>
                                        </div>
                                    @endif
                                    <div class="fw-bold card-title">{{ $product->name }}</div>
                                    <div class="text-muted">
                                        ${{ number_format($product->price, 2) }}
                                        <br>
                                        <small class="text-success">{{ number_format($priceInRiel, 0) }} ៛</small>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div> 
        </div>

    </div>

    <div class="col-md-4">
        <div class="card shadow-lg" style="height: calc(100vh - 80px);">
            <div class="card-header bg-success text-white">{{ __('messages.Checkout') }}</div>

            <div class="card-body p-3" style="overflow-y: auto; height: calc(100vh - 350px);">
                @php 
                    $total = 0;
                    $exchangeRate = 4000;
                @endphp

                <ul class="list-group list-group-flush mb-3">
                    @foreach (session('cart', []) as $id => $item)
                        @php
                            $lineTotal = $item['quantity'] * $item['price'];
                            $total += $lineTotal;
                        @endphp
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold">{{ $item['name'] }}</span>
                                <span class="fw-bold text-success">${{ number_format($lineTotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                @php 
                                    $exchangeRate = 4000; // 1 USD = 4000៛
                                    $priceInRiel = $item['price'] * $exchangeRate;
                                @endphp
                                <div class="text-muted" style="font-size: 0.85rem;">
                                    {{ number_format($priceInRiel, 0) }} ៛
                                    <br>
                                </div>

                                <div class="d-inline-flex align-items-center cart-controls">
                                    <form method="POST" action="{{ route('cart.update', $id) }}" class="d-inline-flex align-items-center">
                                        @csrf
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm me-2">
                                        <button class="btn btn-sm btn-outline-secondary">↺</button>
                                    </form>
                                    <a href="{{ route('cart.remove', $id) }}" class="btn btn-sm btn-outline-danger ms-2">🗑</a>
                                </div>
                            </div>

                            <input type="text" 
                                    name="note[{{ $id }}]" 
                                    class="form-control form-control-sm item-note" 
                                    placeholder="Add item note (optional)" 
                                    value="{{ $item['note'] ?? '' }}">
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="p-3 border-top" style="background-color: #f8f9fa;">
                <div class="mb-2">
                    <label for="discount" class="form-label fw-semibold" style="font-size: 0.9rem;">{{ __('messages.Discount') }} (%)</label>
                    <input type="number" id="discount" class="form-control form-control-sm" placeholder="Enter discount %" min="0" max="100" oninput="updateTotalWithDiscount({{ $total }})">
                </div>

                <div class="mb-2">
                    <label for="note" class="form-label fw-semibold" style="font-size: 0.9rem;">
                        {{ __('messages.Note') }}
                    </label>
                    <input 
                        type="text" 
                        id="note" 
                        name="note"
                        class="form-control form-control-sm rounded-3 border border-secondary shadow-sm" 
                        placeholder="Enter order note..."
                        style="background-color: #ffffff;">
                </div>
                
                <h5 id="totalDisplay" class="py-2 px-3">
                    {{ __('messages.Total') }}: ${{ number_format($total, 2) }} / {{ __('messages.Riel') }}: {{ number_format($total * $exchangeRate, 0) }} ៛
                </h5>

                <button onclick="saveAndPrint()" class="btn btn-primary w-100 mt-2 print-btn">🖨 {{ __('messages.Print Receipt') }}</button>
                <button onclick="location.href='{{ route('cart.clear') }}'" class="btn btn-outline-danger w-100 mt-2 clear-btn">🗑 {{ __('messages.Clear Cart') }}</button>
            </div>
        </div>
    </div>
</div>

<div id="printArea" class="d-none">
        <style>
        @media print {
            @page {
                size: 58mm auto;
                margin: 0;
            }

            html, body {
                margin: 0;
                padding: 0;
                background: #fff;
                color: #000;
                font-family: 'Khmer OS Battambang', 'Courier New', monospace;
                font-size: 13px;
                font-weight: bold;
                -webkit-print-color-adjust: exact;
                width: 58mm;
            }

            .receipt-container {
                width: 100%;
                max-width: 58mm;
                margin: 0 auto;
                padding: 5px;
                box-sizing: border-box;
            }

            /* Header */
            .receipt-header {
                text-align: center;
                font-size: 16px;
                font-weight: 700;
                border-bottom: 2px solid #000;
                padding-bottom: 3px;
                margin-bottom: 5px;
            }

            /* Receipt number & date */
            #receipt-number {
                text-align: left;
                font-size: 13px;
                margin: 5px 0;
            }

            .receipt-line {
                border-top: 1px dashed #000;
                margin: 5px 0;
            }

            /* Table Style for Items */
            table.receipt-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 13px;
            }

            .receipt-table th,
            .receipt-table td {
                padding: 2px 0;
                text-align: left;
                vertical-align: top;
            }

            .receipt-table th:last-child,
            .receipt-table td:last-child {
                text-align: right;
            }

            .receipt-table th {
                border-bottom: 1px solid #000;
            }

            .receipt-table td {
                word-break: break-word;
            }

            .receipt-note {
                font-size: 12px;
                font-style: italic;
                margin-left: 2px;
                margin-bottom: 4px;
                word-wrap: break-word;
            }

            /* Totals */
            .receipt-total {
                border-top: 2px solid #000;
                margin-top: 6px;
                padding-top: 3px;
                font-size: 12px;
                line-height: 1.3;
            }

            .thank-you {
                text-align: center;
                margin-top: 8px;
                font-size: 11px;
                border-top: 1px dashed #000;
                padding-top: 5px;
            }

            .footer-info {
                text-align: center;
                font-size: 9px;
                margin-top: 4px;
                line-height: 1.2;
            }

            .receipt-container * {
                page-break-inside: avoid !important;
            }
        }
    </style>

    <div class="receipt-container">
        <div class="receipt-header">ចែរញាវ​ បុកល្ហុងកូនកាត់</div>

        <div id="receipt-number">
            @if(isset($orders))
                លេខវិក័យប័ត្រ #: {{ $orders->receipt_number }}<br>
                ថ្ងៃ: {{ $orders->created_at->format('Y-m-d H:i') }}
            @endif
        </div>

        <div class="receipt-line"></div>

        @php 
            $printTotal = 0;
            $exchangeRate = 4000;
        @endphp

        <!-- ✅ Table format for items -->
        <table class="receipt-table">
            <thead>
                <tr>
                    <th>មុខទំនិញ</th>
                    <th>តម្លៃ</th>
                </tr>
            </thead>
            <tbody>
                @foreach (session('cart', []) as $item)
                    @php
                        $line = $item['quantity'] * $item['price'];
                        $printTotal += $line;
                    @endphp
                    <tr>
                        <td>{{ $item['name'] }} x{{ $item['quantity'] }}</td>
                        <td>${{ number_format($line, 2) }}</td>
                    </tr>
                    @if(!empty($item['note']))
                        <tr>
                            <td colspan="2" class="receipt-note">ម្ទេស: {{ $item['note'] }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <div class="receipt-line"></div>

        <div class="receipt-total">
            ចំនួនសរុប: ${{ number_format($printTotal, 2) }}<br>
            = {{ number_format($printTotal * $exchangeRate, 0) }} ៛
        </div>

        <div id="receipt-order-note" class="receipt-note"></div>

        <div class="thank-you">🙏 សូមអរគុណ ម៉ូយៗ </div>

        <div class="footer-info">
            Tel: 016 789 312<br>
            Powered by Samon
        </div>
    </div>
    <div id="discountValue" data-discount="0"></div>
</div>

@endsection

@section('scripts')
<script>
function updateTotalWithDiscount(originalTotal) {
    const discountInput = document.getElementById('discount');
    const discount = parseFloat(discountInput.value);
    let discountedTotal = originalTotal;

    if (!isNaN(discount) && discount >= 0 && discount <= 100) {
        discountedTotal = originalTotal - (originalTotal * (discount / 100));
    }

    const totalKhr = discountedTotal * 4000;
    document.getElementById('totalDisplay').innerHTML = 
        `{{ __('messages.Total') }}: $${discountedTotal.toFixed(2)} / {{ __('messages.Riel') }}: ${Math.round(totalKhr).toLocaleString('en-US')} ៛`;

    document.getElementById('discountValue').setAttribute('data-discount', isNaN(discount) ? 0 : discount);
}

function filterProducts() {
    // Check if the search input in the navbar is used (on MD+ screens)
    let searchInput = document.querySelector('.navbar .search-bar');
    if (!searchInput || searchInput.style.display === 'none') {
        // Fallback to the search input in the content area for smaller screens
        searchInput = document.getElementById('productSearch');
    }
    
    if (!searchInput) return;

    const search = searchInput.value.toLowerCase();
    const cards = document.querySelectorAll('#productGrid .product-card');
    cards.forEach(card => {
        const title = card.querySelector('.card-title').textContent.toLowerCase();
        // The parent col- element is what needs to be hidden/shown
        card.closest('.col-6, .col-md-3, .col-lg-3').style.display = title.includes(search) ? '' : 'none';
    });
}

// Initial call to ensure the correct total is displayed on load
document.addEventListener('DOMContentLoaded', () => {
    // The original total is available in the PHP variable $total on page load
    updateTotalWithDiscount({{ $total }}); 

    // Add event listener to the navbar search bar if it exists
    const navbarSearch = document.querySelector('.navbar .search-bar');
    if (navbarSearch) {
        navbarSearch.addEventListener('keyup', filterProducts);
    }
});


function saveAndPrint() {
    const discount = parseFloat(document.getElementById('discountValue').getAttribute('data-discount')) || 0;
    const note = document.getElementById('note').value || '';
    const receiptNumber = Math.floor(1000 + Math.random() * 9000);

    // ✅ Collect item notes (each note input in checkout list)
    const itemNotes = {};
    document.querySelectorAll('.item-note').forEach((input) => {
        const nameAttr = input.getAttribute('name'); // e.g., note[12]
        const idMatch = nameAttr.match(/\[(.*?)\]/);
        if (idMatch && idMatch[1]) {
            itemNotes[idMatch[1]] = input.value; // store { product_id: "note text" }
        }
    });

    fetch("{{ route('orders.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            discount: discount,
            note: note,
            receipt_number: receiptNumber,
            item_notes: itemNotes // ✅ send item notes to backend
        })
    })
    .then(async res => {
        const contentType = res.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            const errorText = await res.text();
            throw new Error("Server returned non-JSON: " + errorText);
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            printReceipt(receiptNumber, note);
        } else {
            alert('Order save failed.');
        }
    })
    .catch(error => {
        console.error('Failed:', error);
        alert('Error storing order: ' + error.message);
    });
}


function printReceipt(receiptNumber, orderNote) {
    const receiptNumberElement = document.getElementById('receipt-number');
    receiptNumberElement.innerHTML = `លេខវិក័យប័ត្រ #: ${receiptNumber}<br>ថ្ងៃ: ${new Date().toLocaleString()}`;

    const discount = parseFloat(document.getElementById('discountValue').getAttribute('data-discount')) || 0;
    let total = 0;
    
    // Clear existing item notes and re-render the cart items in the print area 
    // to include the latest item notes from the form
    const printAreaContainer = document.querySelector('#printArea .receipt-container');
    
    // Get all item notes from the current form state
    const currentItemNotes = {};
    document.querySelectorAll('.item-note').forEach((input) => {
        const nameAttr = input.getAttribute('name');
        const idMatch = nameAttr.match(/\[(.*?)\]/);
        if (idMatch && idMatch[1]) {
            currentItemNotes[idMatch[1]] = input.value;
        }
    });

    // Rebuild the item list in the print area (A simpler approach for this structure)
    // This part assumes session('cart') in the Blade template is still valid, but we need
    // to update the item notes dynamically in the print content before printing.
    // For simplicity and since we can't re-render Blade, we'll manually update the print content.

    const cartItems = @json(session('cart', [])); // Use the Blade helper to get the cart data

    let itemsHtml = '';
    let rawTotal = 0;
    
    for (const id in cartItems) {
        const item = cartItems[id];
        const line = item.quantity * item.price;
        rawTotal += line;
        
        // Use the note from the form if available, otherwise use what was in the session (if any)
        const note = currentItemNotes[id] || item.note || ''; 

        itemsHtml += `
            <div class="receipt-item">
                <span>${item.name} x${item.quantity}</span>
                <span>$${line.toFixed(2)}</span>
            </div>
        `;
        if (note) {
             itemsHtml += `<div class="receipt-note">ម្ទេស: ${note}</div>`;
        }
    }
    
    // Find where the items list is located in the print area (between the lines)
    let firstLine = printAreaContainer.querySelector('.receipt-line');
    let secondLine = firstLine ? firstLine.nextElementSibling : null;

    // Remove old item lines and the line separator
    while (secondLine && secondLine.className !== 'receipt-line') {
        let nextSibling = secondLine.nextElementSibling;
        secondLine.remove();
        secondLine = nextSibling;
    }

    // Insert new items HTML
    if (firstLine) {
        firstLine.insertAdjacentHTML('afterend', itemsHtml);
    }
    

    const discountedTotal = rawTotal - (rawTotal * (discount / 100));
    const totalKhr = discountedTotal * 4000;

    // Update Discount Line
    let discountLine = document.getElementById('receipt-discount');
    if (discount > 0) {
        if (!discountLine) {
            discountLine = document.createElement('div');
            discountLine.id = 'receipt-discount';
            discountLine.classList.add('receipt-item');
            discountLine.style.fontStyle = 'italic';
            const receiptLineAfterItems = document.querySelectorAll('#printArea .receipt-line')[1];
            receiptLineAfterItems.insertAdjacentElement('afterend', discountLine);
        }
        discountLine.innerHTML = `<span>Discount (${discount.toFixed(2)}%)</span><span>-$${(rawTotal * (discount/100)).toFixed(2)}</span>`;
    } else if (discountLine) {
        discountLine.remove();
    }
    
    // Update Order Note
    let orderNoteElement = document.getElementById('receipt-order-note');
    if (orderNote) {
        orderNoteElement.textContent = `Order Note: ${orderNote}`;
        orderNoteElement.style.display = 'block';
    } else {
        orderNoteElement.style.display = 'none';
        orderNoteElement.textContent = '';
    }

    // Update Total
    const receiptTotal = document.querySelector('#printArea .receipt-total');
    receiptTotal.innerHTML = `Total: $${discountedTotal.toFixed(2)}<br>= ${Math.round(totalKhr).toLocaleString('en-US')} ៛`;

    // Print
    const printContent = document.getElementById('printArea').innerHTML;
    const win = window.open('', '', 'width=400,height=600');
    win.document.write(`
        <html>
            <head><title>POS Receipt</title></head>
            <body onload="window.print(); window.close();">
                ${printContent}
            </body>
        </html>
    `);
    win.document.close();
}
</script>
@endsection