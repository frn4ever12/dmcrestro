import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

import '../constants/app_constants.dart';

class StorageService {
  final SharedPreferences sharedPreferences;
  final FlutterSecureStorage secureStorage;

  StorageService({
    required this.sharedPreferences,
    required this.secureStorage,
  });

  // Token Management
  Future<void> saveAccessToken(String token) async {
    await secureStorage.write(key: AppConstants.accessTokenKey, value: token);
  }

  Future<String?> getAccessToken() async {
    return await secureStorage.read(key: AppConstants.accessTokenKey);
  }

  Future<void> saveRefreshToken(String token) async {
    await secureStorage.write(key: AppConstants.refreshTokenKey, value: token);
  }

  Future<String?> getRefreshToken() async {
    return await secureStorage.read(key: AppConstants.refreshTokenKey);
  }

  // User Management
  Future<void> saveUser(String userJson) async {
    await sharedPreferences.setString(AppConstants.userKey, userJson);
  }

  String? getUser() {
    return sharedPreferences.getString(AppConstants.userKey);
  }

  Future<void> clearUser() async {
    await sharedPreferences.remove(AppConstants.userKey);
  }

  // User Type
  Future<void> saveUserType(String userType) async {
    await sharedPreferences.setString(AppConstants.userTypeKey, userType);
  }

  String? getUserType() {
    return sharedPreferences.getString(AppConstants.userTypeKey);
  }

  // Restaurant/Branch ID
  Future<void> saveRestaurantId(String restaurantId) async {
    await sharedPreferences.setString(AppConstants.restaurantIdKey, restaurantId);
  }

  String? getRestaurantId() {
    return sharedPreferences.getString(AppConstants.restaurantIdKey);
  }

  Future<void> saveBranchId(String branchId) async {
    await sharedPreferences.setString(AppConstants.branchIdKey, branchId);
  }

  String? getBranchId() {
    return sharedPreferences.getString(AppConstants.branchIdKey);
  }

  // Clear All
  Future<void> clearAll() async {
    await secureStorage.delete(key: AppConstants.accessTokenKey);
    await secureStorage.delete(key: AppConstants.refreshTokenKey);
    await clearUser();
    await sharedPreferences.remove(AppConstants.userTypeKey);
    await sharedPreferences.remove(AppConstants.restaurantIdKey);
    await sharedPreferences.remove(AppConstants.branchIdKey);
  }
}
