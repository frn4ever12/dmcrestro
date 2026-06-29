import 'package:flutter/foundation.dart';
import 'dart:convert';

import '../constants/app_constants.dart';
import 'api_service.dart';
import 'storage_service.dart';
import '../models/user_model.dart';

class AuthService extends ChangeNotifier {
  final ApiService apiService;
  final StorageService storageService;

  User? _user;
  bool _isAuthenticated = false;
  bool _isLoading = false;
  String? _errorMessage;

  AuthService({
    required this.apiService,
    required this.storageService,
  }) {
    _loadUserFromStorage();
  }

  User? get user => _user;
  bool get isAuthenticated => _isAuthenticated;
  bool get isLoading => _isLoading;
  String? get errorMessage => _errorMessage;
  String? get userType => _user?.userType;
  
  Stream<bool> get stream async* {
    yield _isAuthenticated;
  }
  
  bool get isOwner => _user?.userType == 'owner';
  bool get isCashier => _user?.userType == 'cashier';
  bool get isWaiter => _user?.userType == 'waiter';
  bool get isKitchen => _user?.userType == 'kitchen';
  bool get isChef => _user?.userType == 'chef';
  bool get isCustomer => _user?.userType == 'customer';

  Future<void> _loadUserFromStorage() async {
    final userJson = storageService.getUser();
    if (userJson != null) {
      _user = User.fromJson(jsonDecode(userJson));
      _isAuthenticated = true;
      notifyListeners();
    }
  }

  Future<bool> login(String email, String password) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final response = await apiService.post('/login', data: {
        'email': email,
        'password': password,
      });

      if (response.statusCode == 200) {
        final data = response.data;
        _user = User.fromJson(data['user']);
        
        await storageService.saveAccessToken(data['token']);
        await storageService.saveUser(jsonEncode(data['user']));
        
        _isAuthenticated = true;
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _errorMessage = 'Login failed';
        _isLoading = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      _errorMessage = e.toString();
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<bool> register(Map<String, dynamic> userData) async {
    _isLoading = true;
    _errorMessage = null;
    notifyListeners();

    try {
      final response = await apiService.post('/register', data: userData);

      if (response.statusCode == 201) {
        final data = response.data;
        _user = User.fromJson(data['user']);
        
        await storageService.saveAccessToken(data['token']);
        await storageService.saveUser(jsonEncode(data['user']));
        
        _isAuthenticated = true;
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        _errorMessage = 'Registration failed';
        _isLoading = false;
        notifyListeners();
        return false;
      }
    } catch (e) {
      _errorMessage = e.toString();
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    try {
      await apiService.post('/logout');
    } catch (e) {
      // Ignore logout errors
    }

    await storageService.clearAll();
    _user = null;
    _isAuthenticated = false;
    notifyListeners();
  }

  Future<bool> updateProfile(Map<String, dynamic> data) async {
    try {
      final response = await apiService.put('/profile', data: data);

      if (response.statusCode == 200) {
        _user = User.fromJson(response.data['user']);
        await storageService.saveUser(jsonEncode(_user!.toJson()));
        notifyListeners();
        return true;
      }
      return false;
    } catch (e) {
      _errorMessage = e.toString();
      return false;
    }
  }
}
