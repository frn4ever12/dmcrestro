class User {
  final int id;
  final String name;
  final String email;
  final String? phone;
  final String? avatar;
  final String userType;
  final int? tenantId;
  final int? restaurantId;
  final int? branchId;
  final bool isActive;

  User({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
    this.avatar,
    required this.userType,
    this.tenantId,
    this.restaurantId,
    this.branchId,
    required this.isActive,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] as int,
      name: json['name'] as String,
      email: json['email'] as String,
      phone: json['phone'] as String?,
      avatar: json['avatar'] as String?,
      userType: json['user_type'] as String,
      tenantId: json['tenant_id'] as int?,
      restaurantId: json['restaurant_id'] as int?,
      branchId: json['branch_id'] as int?,
      isActive: json['is_active'] as bool,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'phone': phone,
      'avatar': avatar,
      'user_type': userType,
      'tenant_id': tenantId,
      'restaurant_id': restaurantId,
      'branch_id': branchId,
      'is_active': isActive,
    };
  }

  bool get isSuperAdmin => userType == 'super_admin';
  bool get isOwner => userType == 'owner';
  bool get isManager => userType == 'manager';
  bool get isCashier => userType == 'cashier';
  bool get isWaiter => userType == 'waiter';
  bool get isKitchen => userType == 'kitchen';
  bool get isChef => userType == 'chef';
  bool get isCustomer => userType == 'customer';
}
