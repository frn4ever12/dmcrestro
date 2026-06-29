<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nepal Restaurant SaaS - Complete Restaurant Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .hero-pattern { background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .animate-float { animation: float 3s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
        .slide-track { animation: scroll 30s linear infinite; }
        @keyframes scroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="fixed w-full bg-white/95 backdrop-blur-sm shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-utensils text-white text-xl"></i>
                    </div>
                    <div>
                        <span class="font-bold text-xl text-gray-800">Nepal Restaurant</span>
                        <span class="text-orange-500 font-semibold">SaaS</span>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-orange-500 transition">Features</a>
                    <a href="#pricing" class="text-gray-600 hover:text-orange-500 transition">Pricing</a>
                    <a href="#testimonials" class="text-gray-600 hover:text-orange-500 transition">Testimonials</a>
                    <a href="#contact" class="text-gray-600 hover:text-orange-500 transition">Contact</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/login" class="text-gray-600 hover:text-orange-500 font-medium">Login</a>
                    <a href="/register" class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-2.5 rounded-xl font-semibold hover:shadow-lg transition">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-purple-900/90 via-purple-800/85 to-indigo-900/90"></div>
        <div class="absolute inset-0 hero-pattern opacity-30"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <div class="inline-block bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full mb-6">
                        <span class="text-sm font-medium">🚀 #1 Restaurant Management System in Nepal</span>
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                        नेपाल रेस्टुरेन्ट<br>
                        <span class="text-yellow-300">SaaS Platform</span>
                    </h1>
                    <h2 class="text-2xl md:text-3xl font-semibold mb-4 opacity-90">Complete Restaurant Management System</h2>
                    <p class="text-xl mb-8 opacity-80 leading-relaxed">
                        तपाईंको रेस्टुरेन्टलाई हाम्रो एक-इन-वन SaaS समाधानले सहजतापूर्वक व्यवस्थापन गर्नुहोस्। 
                        अर्डर, इन्भेन्टरी, स्टाफ, र रिपोर्टिङ - सबै एउटै ठाउँमा।
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/register" class="bg-white text-purple-600 px-8 py-4 rounded-xl font-bold hover:shadow-2xl transition flex items-center justify-center">
                            <i class="fas fa-rocket mr-2"></i> निःशुल्क सुरु गर्नुहोस्
                        </a>
                        <a href="#features" class="border-2 border-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-purple-600 transition flex items-center justify-center">
                            <i class="fas fa-play-circle mr-2"></i> डेमो हेर्नुहोस्
                        </a>
                    </div>
                    <div class="mt-10 flex items-center gap-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold">500+</div>
                            <div class="text-sm opacity-80">Restaurants</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">50K+</div>
                            <div class="text-sm opacity-80">Daily Orders</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold">99.9%</div>
                            <div class="text-sm opacity-80">Uptime</div>
                        </div>
                    </div>
                </div>
                <div class="relative hidden lg:block">
                    <div class="animate-float">
                        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20">
                            <div class="bg-white rounded-2xl p-6 shadow-2xl">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-bold text-gray-800">Today's Orders</h3>
                                    <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-medium">+23%</span>
                                </div>
                                <div class="text-4xl font-bold text-gray-800 mb-2">Rs. 1,25,000</div>
                                <div class="text-gray-500 text-sm">Total Revenue Today</div>
                                <div class="mt-6 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-utensils text-orange-500"></i>
                                            </div>
                                            <span class="text-gray-700">Table Orders</span>
                                        </div>
                                        <span class="font-semibold">45</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-mobile-alt text-blue-500"></i>
                                            </div>
                                            <span class="text-gray-700">Online Orders</span>
                                        </div>
                                        <span class="font-semibold">78</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Trusted By Section -->
    <div class="bg-white py-12 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 font-medium mb-8">TRUSTED BY TOP RESTAURANTS IN NEPAL</p>
            <div class="overflow-hidden">
                <div class="slide-track flex items-center space-x-16">
                    <!-- Restaurant Logos - Duplicated for seamless loop -->
                    <div class="flex items-center space-x-16 min-w-max">
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-utensils text-orange-500 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Kathmandu Kitchen</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-fire text-red-500 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Himalayan Foods</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-mountain text-green-500 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Pokhara Palace</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-leaf text-emerald-500 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Thamel Bistro</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-star text-yellow-500 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Nepal Spice</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-pepper-hot text-red-600 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Lalitpur Lounge</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-fish text-blue-500 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Bhaktapur Bites</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-drumstick-bite text-orange-600 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Biratnagar BBQ</span>
                        </div>
                    </div>
                    <!-- Duplicate for seamless loop -->
                    <div class="flex items-center space-x-16 min-w-max">
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-utensils text-orange-500 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Kathmandu Kitchen</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-fire text-red-500 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Himalayan Foods</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-mountain text-green-500 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Pokhara Palace</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-leaf text-emerald-500 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Thamel Bistro</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-star text-yellow-500 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Nepal Spice</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-pepper-hot text-red-600 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Lalitpur Lounge</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-fish text-blue-500 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Bhaktapur Bites</span>
                        </div>
                        <div class="flex items-center space-x-2 opacity-60 hover:opacity-100 transition">
                            <i class="fas fa-drumstick-bite text-orange-600 text-2xl"></i>
                            <span class="font-bold text-gray-700 text-xl">Biratnagar BBQ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="bg-orange-100 text-orange-600 px-4 py-2 rounded-full text-sm font-semibold">FEATURES</span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mt-4 mb-4">Everything You Need</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Complete restaurant management solution with powerful features</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card-hover bg-gradient-to-br from-orange-50 to-red-50 p-8 rounded-2xl border border-orange-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-utensils text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Menu Management</h3>
                    <p class="text-gray-600 leading-relaxed">Create and manage your menu items, categories, and modifiers with ease. Real-time updates across all platforms.</p>
                </div>
                <!-- Feature 2 -->
                <div class="card-hover bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl border border-blue-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-receipt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Order Management</h3>
                    <p class="text-gray-600 leading-relaxed">Track orders from table to kitchen with real-time updates. Seamless integration with POS systems.</p>
                </div>
                <!-- Feature 3 -->
                <div class="card-hover bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl border border-green-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-boxes text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Inventory Control</h3>
                    <p class="text-gray-600 leading-relaxed">Monitor stock levels, track ingredients, and reduce waste. Smart alerts for low stock items.</p>
                </div>
                <!-- Feature 4 -->
                <div class="card-hover bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl border border-purple-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Staff Management</h3>
                    <p class="text-gray-600 leading-relaxed">Manage employees, roles, and schedules efficiently. Track performance and attendance.</p>
                </div>
                <!-- Feature 5 -->
                <div class="card-hover bg-gradient-to-br from-yellow-50 to-orange-50 p-8 rounded-2xl border border-yellow-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-chart-line text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Analytics & Reports</h3>
                    <p class="text-gray-600 leading-relaxed">Get insights into sales, performance, and customer behavior. Detailed reports for better decisions.</p>
                </div>
                <!-- Feature 6 -->
                <div class="card-hover bg-gradient-to-br from-cyan-50 to-blue-50 p-8 rounded-2xl border border-cyan-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <i class="fas fa-building text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">Multi-Branch Support</h3>
                    <p class="text-gray-600 leading-relaxed">Manage multiple restaurant branches from a single dashboard. Centralized control with local flexibility.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Preview Section -->
    <div class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-sm font-semibold">SYSTEM PREVIEW</span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mt-4 mb-4">Powerful System Interface</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">See our intuitive system in action</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- KDS View -->
                <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="h-48 bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6">
                            <i class="fas fa-tv text-white text-6xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Kitchen Display System (KDS)</h3>
                        <p class="text-gray-600">Real-time order display for kitchen staff. Color-coded orders, preparation timers, and automatic order routing to different stations.</p>
                    </div>
                </div>
                <!-- Billing System -->
                <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="h-48 bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6">
                            <i class="fas fa-file-invoice-dollar text-white text-6xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Smart Billing System</h3>
                        <p class="text-gray-600">Quick billing with split payments, multiple payment methods, tax calculation, and instant receipt generation. Integrated with POS.</p>
                    </div>
                </div>
                <!-- Order Management -->
                <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="h-48 bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6">
                            <i class="fas fa-clipboard-list text-white text-6xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Order Management</h3>
                        <p class="text-gray-600">Complete order tracking from placement to delivery. Table management, order modifications, and real-time status updates.</p>
                    </div>
                </div>
                <!-- Inventory Dashboard -->
                <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="h-48 bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6">
                            <i class="fas fa-boxes text-white text-6xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Inventory Dashboard</h3>
                        <p class="text-gray-600">Track stock levels in real-time. Low stock alerts, ingredient usage tracking, and automatic purchase order suggestions.</p>
                    </div>
                </div>
                <!-- Analytics Dashboard -->
                <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="h-48 bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6">
                            <i class="fas fa-chart-pie text-white text-6xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Analytics Dashboard</h3>
                        <p class="text-gray-600">Comprehensive reports on sales, peak hours, popular items, and customer preferences. Data-driven insights for growth.</p>
                    </div>
                </div>
                <!-- Staff Management -->
                <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-lg">
                    <div class="h-48 bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-6">
                            <i class="fas fa-users-cog text-white text-6xl"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Staff Management</h3>
                        <p class="text-gray-600">Manage employee roles, schedules, and performance. Track attendance, calculate tips, and generate payroll reports.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div id="pricing" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="bg-purple-100 text-purple-600 px-4 py-2 rounded-full text-sm font-semibold">PRICING</span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mt-4 mb-4">Simple, Transparent Pricing</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Choose the perfect plan for your restaurant</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Starter Plan -->
                <div class="card-hover bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Starter</h3>
                    <p class="text-gray-600 mb-6">Perfect for small restaurants</p>
                    <div class="mb-6">
                        <span class="text-5xl font-bold text-gray-800">Rs. 2,999</span>
                        <span class="text-gray-500">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i> Up to 50 orders/day
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i> 1 Location
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i> Basic Reports
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i> Email Support
                        </li>
                    </ul>
                    <a href="/register" class="block text-center bg-gray-100 text-gray-800 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 transition">Get Started</a>
                </div>
                <!-- Pro Plan -->
                <div class="card-hover bg-gradient-to-br from-purple-600 to-indigo-600 p-8 rounded-2xl shadow-xl transform scale-105 relative">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-yellow-400 text-yellow-900 px-4 py-1 rounded-full text-sm font-bold">MOST POPULAR</div>
                    <h3 class="text-2xl font-bold text-white mb-2">Professional</h3>
                    <p class="text-purple-200 mb-6">For growing restaurants</p>
                    <div class="mb-6">
                        <span class="text-5xl font-bold text-white">Rs. 5,999</span>
                        <span class="text-purple-200">/month</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-white">
                            <i class="fas fa-check text-yellow-400 mr-3"></i> Unlimited orders
                        </li>
                        <li class="flex items-center text-white">
                            <i class="fas fa-check text-yellow-400 mr-3"></i> Up to 5 Locations
                        </li>
                        <li class="flex items-center text-white">
                            <i class="fas fa-check text-yellow-400 mr-3"></i> Advanced Analytics
                        </li>
                        <li class="flex items-center text-white">
                            <i class="fas fa-check text-yellow-400 mr-3"></i> Priority Support
                        </li>
                        <li class="flex items-center text-white">
                            <i class="fas fa-check text-yellow-400 mr-3"></i> API Access
                        </li>
                    </ul>
                    <a href="/register" class="block text-center bg-white text-purple-600 px-6 py-3 rounded-xl font-bold hover:bg-gray-100 transition">Get Started</a>
                </div>
                <!-- Enterprise Plan -->
                <div class="card-hover bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Enterprise</h3>
                    <p class="text-gray-600 mb-6">For restaurant chains</p>
                    <div class="mb-6">
                        <span class="text-5xl font-bold text-gray-800">Custom</span>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i> Unlimited everything
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i> Unlimited Locations
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i> Custom Integrations
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i> Dedicated Manager
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-3"></i> 24/7 Phone Support
                        </li>
                    </ul>
                    <a href="/register" class="block text-center bg-gray-100 text-gray-800 px-6 py-3 rounded-xl font-semibold hover:bg-gray-200 transition">Contact Sales</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div id="testimonials" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="bg-green-100 text-green-600 px-4 py-2 rounded-full text-sm font-semibold">TESTIMONIALS</span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mt-4 mb-4">Loved by Restaurant Owners</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">See what our customers say about us</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-orange-50 to-red-50 p-8 rounded-2xl border border-orange-100">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"This system transformed our restaurant operations. We've seen 40% increase in efficiency and our customers love the faster service."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold mr-4">RK</div>
                        <div>
                            <div class="font-semibold text-gray-800">Rajesh Karki</div>
                            <div class="text-gray-600 text-sm">Owner, Kathmandu Kitchen</div>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl border border-blue-100">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"Managing 5 branches was a nightmare before. Now everything is centralized and we have real-time visibility across all locations."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-4">ST</div>
                        <div>
                            <div class="font-semibold text-gray-800">Sita Thapa</div>
                            <div class="text-gray-600 text-sm">Manager, Himalayan Foods</div>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl border border-green-100">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-700 mb-6 italic">"The inventory management alone saved us thousands in reduced waste. The analytics help us make data-driven decisions daily."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-4">AS</div>
                        <div>
                            <div class="font-semibold text-gray-800">Anil Sharma</div>
                            <div class="text-gray-600 text-sm">Chef, Pokhara Palace</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="gradient-bg hero-pattern py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Ready to Transform Your Restaurant?</h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">Join 500+ restaurants already using our platform to grow their business</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-white text-purple-600 px-8 py-4 rounded-xl font-bold hover:shadow-2xl transition">Start Free Trial</a>
                <a href="#contact" class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-purple-600 transition">Contact Sales</a>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div id="contact" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12">
                <div>
                    <span class="bg-orange-100 text-orange-600 px-4 py-2 rounded-full text-sm font-semibold">CONTACT US</span>
                    <h2 class="text-4xl font-bold text-gray-800 mt-4 mb-4">Get in Touch</h2>
                    <p class="text-xl text-gray-600 mb-8">Have questions? We'd love to hear from you.</p>
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-orange-500 text-xl"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">Phone</div>
                                <div class="text-gray-600">+977 1-4XXXXXX</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-orange-500 text-xl"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">Email</div>
                                <div class="text-gray-600">info@nepalrestaurantsaas.com</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-orange-500 text-xl"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800">Address</div>
                                <div class="text-gray-600">Kathmandu, Nepal</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Name</label>
                                <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" placeholder="Your name">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Email</label>
                                <input type="email" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" placeholder="your@email.com">
                            </div>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 font-medium mb-2">Subject</label>
                            <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition" placeholder="How can we help?">
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 font-medium mb-2">Message</label>
                            <textarea rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition resize-none" placeholder="Your message..."></textarea>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-4 rounded-xl font-bold hover:shadow-lg transition">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <div>
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-utensils text-white"></i>
                        </div>
                        <span class="font-bold text-xl">Nepal Restaurant SaaS</span>
                    </div>
                    <p class="text-gray-400">Complete restaurant management solution for modern restaurants in Nepal.</p>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Product</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Features</a></li>
                        <li><a href="#" class="hover:text-white transition">Pricing</a></li>
                        <li><a href="#" class="hover:text-white transition">Integrations</a></li>
                        <li><a href="#" class="hover:text-white transition">Updates</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Company</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-4">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-orange-500 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-orange-500 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-orange-500 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-orange-500 transition">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Nepal Restaurant SaaS. All rights reserved. Made with ❤️ in Nepal</p>
            </div>
        </div>
    </footer>
</body>
</html>
