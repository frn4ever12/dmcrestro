class AppConstants {
  // API Configuration
  static const String baseUrl = String.fromEnvironment(
    'API_BASE_URL',
    defaultValue: 'http://localhost:8000/api',
  );

  static const String apiVersion = 'v1';
  static const Duration apiTimeout = Duration(seconds: 30);

  // Storage Keys
  static const String accessTokenKey = 'access_token';
  static const String refreshTokenKey = 'refresh_token';
  static const String userKey = 'user';
  static const String userTypeKey = 'user_type';
  static const String restaurantIdKey = 'restaurant_id';
  static const String branchIdKey = 'branch_id';

  // User Types
  static const String userTypeSuperAdmin = 'super_admin';
  static const String userTypeOwner = 'owner';
  static const String userTypeManager = 'manager';
  static const String userTypeCashier = 'cashier';
  static const String userTypeWaiter = 'waiter';
  static const String userTypeKitchen = 'kitchen';
  static const String userTypeChef = 'chef';
  static const String userTypeCustomer = 'customer';

  // Payment Methods
  static const String paymentCash = 'cash';
  static const String paymentCard = 'card';
  static const String paymentEsewa = 'esewa';
  static const String paymentKhalti = 'khalti';
  static const String paymentFonepay = 'fonepay';
  static const String paymentConnectips = 'connectips';

  // Order Status
  static const String orderPending = 'pending';
  static const String orderConfirmed = 'confirmed';
  static const String orderPreparing = 'preparing';
  static const String orderReady = 'ready';
  static const String orderServed = 'served';
  static const String orderCompleted = 'completed';
  static const String orderCancelled = 'cancelled';

  // Table Status
  static const String tableAvailable = 'available';
  static const String tableOccupied = 'occupied';
  static const String tableReserved = 'reserved';
  static const String tableCleaning = 'cleaning';

  // Pagination
  static const int defaultPageSize = 20;
}
