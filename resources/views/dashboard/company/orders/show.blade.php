@extends('layouts.dashboard')

@section('dashboard-name', 'Company Dashboard')
@section('dashboard-icon', 'ri-building-line')
@section('page-title', 'Order Details')
@section('profile-route', route('company.profile'))
@section('settings-route', route('company.settings'))

@section('sidebar')
<nav class="flex-1 px-2 py-4 space-y-1">
    <a href="{{ route('company.dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-dashboard-3-line text-xl mr-3"></i>
        Dashboard
    </a>
    
    <a href="{{ route('company.setup') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-settings-3-line text-xl mr-3"></i>
        Setup
    </a>
    
    <a href="{{ route('company.profile') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-user-line text-xl mr-3"></i>
        Profile
    </a>
    
    <a href="{{ route('company.products') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-shopping-bag-line text-xl mr-3"></i>
        Products
    </a>
    
    <a href="{{ route('company.orders') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg bg-blue-50 text-blue-700">
        <i class="ri-shopping-cart-line text-xl mr-3"></i>
        Orders
    </a>
    
    <a href="{{ route('company.jobs') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-briefcase-line text-xl mr-3"></i>
        Job Listings
    </a>
    
    <a href="{{ route('company.settings') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-settings-3-line text-xl mr-3"></i>
        Settings
    </a>
</nav>

<div class="px-2 py-4 border-t border-gray-200">
    <a href="{{ route('home') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-100">
        <i class="ri-home-line text-xl mr-3"></i>
        Back to Site
    </a>
</div>
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Order Details</h1>
            <p class="mt-2 text-gray-600">Order #{{ $order->order_number }}</p>
        </div>
        <a href="{{ route('company.orders') }}" class="text-gray-600 hover:text-gray-900">
            <i class="ri-arrow-left-line mr-2"></i>Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Order Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Order Information</h2>
                    @php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'confirmed' => 'bg-blue-100 text-blue-800',
                            'processing' => 'bg-indigo-100 text-indigo-800',
                            'shipped' => 'bg-purple-100 text-purple-800',
                            'delivered' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                        $statusColor = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Order Number</p>
                        <p class="font-medium text-gray-900">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Order Date</p>
                        <p class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Payment Status</p>
                        @php
                            $paymentColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800',
                                'refunded' => 'bg-gray-100 text-gray-800',
                            ];
                            $paymentColor = $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $paymentColor }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-gray-500">Payment Method</p>
                        <p class="font-medium text-gray-900">{{ $order->payment_method ?? 'N/A' }}</p>
                    </div>
                    @if($order->tracking_number)
                    <div>
                        <p class="text-gray-500">Tracking Number</p>
                        <p class="font-medium text-gray-900">{{ $order->tracking_number }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Items ({{ $order->orderItems->count() }})</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name ?? 'Product Deleted' }}</div>
                                    @if($item->product && $item->product->sku)
                                    <div class="text-xs text-gray-500">SKU: {{ $item->product->sku }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($item->unit_price, 2) }} {{ $order->currency }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ number_format($item->total_price, 2) }} {{ $order->currency }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Customer Information</h2>
                
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Name</p>
                        <p class="font-medium text-gray-900">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ $order->customer_email }}</p>
                    </div>
                    @if($order->customer_phone)
                    <div>
                        <p class="text-gray-500">Phone</p>
                        <p class="font-medium text-gray-900">{{ $order->customer_phone }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Shipping Address</h2>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $order->shipping_address }}</p>
            </div>

            <!-- Order Summary -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-900">{{ number_format($order->subtotal, 2) }} {{ $order->currency }}</span>
                    </div>
                    @if($order->tax_amount > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-medium text-gray-900">{{ number_format($order->tax_amount, 2) }} {{ $order->currency }}</span>
                    </div>
                    @endif
                    @if($order->shipping_cost > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium text-gray-900">{{ number_format($order->shipping_cost, 2) }} {{ $order->currency }}</span>
                    </div>
                    @endif
                    <div class="pt-2 border-t border-gray-200 flex justify-between">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="font-bold text-lg text-gray-900">{{ number_format($order->total_amount, 2) }} {{ $order->currency }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Notes</h2>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $order->notes }}</p>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Actions</h2>
                <div class="space-y-2">
                    @if($order->status == 'pending')
                    <button onclick="updateOrderStatus({{ $order->id }}, 'confirmed')" 
                            class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="ri-check-line mr-2"></i>
                        Confirm Order
                    </button>
                    @endif
                    @if($order->status == 'confirmed')
                    <button onclick="updateOrderStatus({{ $order->id }}, 'processing')" 
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="ri-loader-4-line mr-2"></i>
                        Start Processing
                    </button>
                    @endif
                    @if($order->status == 'processing')
                    <button onclick="updateOrderStatus({{ $order->id }}, 'shipped')" 
                            class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="ri-truck-line mr-2"></i>
                        Mark as Shipped
                    </button>
                    @endif
                    @if($order->status == 'shipped')
                    <button onclick="updateOrderStatus({{ $order->id }}, 'delivered')" 
                            class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        <i class="ri-checkbox-circle-line mr-2"></i>
                        Mark as Delivered
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateOrderStatus(orderId, status) {
    if (confirm('Are you sure you want to update the order status?')) {
        // TODO: Implement status update functionality
        console.log('Update order', orderId, 'to status', status);
        alert('Status update functionality coming soon!');
    }
}
</script>
@endpush
@endsection








