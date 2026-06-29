import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../constants/app_constants.dart';
import '../services/auth_service.dart';
import '../../screens/auth/login_screen.dart';
import '../../screens/auth/register_screen.dart';
import '../../screens/owner/owner_dashboard_screen.dart';
import '../../screens/cashier/cashier_dashboard_screen.dart';
import '../../screens/waiter/waiter_dashboard_screen.dart';
import '../../screens/kitchen/kitchen_dashboard_screen.dart';
import '../../screens/customer/customer_dashboard_screen.dart';

class AppRouter {
  static GoRouter router(AuthService authService) {
    return GoRouter(
      initialLocation: '/login',
      refreshListenable: GoRouterRefreshStream(authService.stream),
      routes: [
        GoRoute(
          path: '/login',
          name: 'login',
          builder: (context, state) => const LoginScreen(),
        ),
        GoRoute(
          path: '/register',
          name: 'register',
          builder: (context, state) => const RegisterScreen(),
        ),
        GoRoute(
          path: '/owner',
          name: 'owner_dashboard',
          builder: (context, state) => const OwnerDashboardScreen(),
          redirect: (context, state) {
            if (!authService.isAuthenticated) return '/login';
            if (!authService.isOwner) return '/unauthorized';
            return null;
          },
        ),
        GoRoute(
          path: '/cashier',
          name: 'cashier_dashboard',
          builder: (context, state) => const CashierDashboardScreen(),
          redirect: (context, state) {
            if (!authService.isAuthenticated) return '/login';
            if (!authService.isCashier) return '/unauthorized';
            return null;
          },
        ),
        GoRoute(
          path: '/waiter',
          name: 'waiter_dashboard',
          builder: (context, state) => const WaiterDashboardScreen(),
          redirect: (context, state) {
            if (!authService.isAuthenticated) return '/login';
            if (!authService.isWaiter) return '/unauthorized';
            return null;
          },
        ),
        GoRoute(
          path: '/kitchen',
          name: 'kitchen_dashboard',
          builder: (context, state) => const KitchenDashboardScreen(),
          redirect: (context, state) {
            if (!authService.isAuthenticated) return '/login';
            if (!authService.isKitchen && !authService.isChef) return '/unauthorized';
            return null;
          },
        ),
        GoRoute(
          path: '/customer',
          name: 'customer_dashboard',
          builder: (context, state) => const CustomerDashboardScreen(),
          redirect: (context, state) {
            if (!authService.isAuthenticated) return '/login';
            if (!authService.isCustomer) return '/unauthorized';
            return null;
          },
        ),
        GoRoute(
          path: '/unauthorized',
          name: 'unauthorized',
          builder: (context, state) => const UnauthorizedScreen(),
        ),
      ],
    );
  }
}

class GoRouterRefreshStream extends ChangeNotifier {
  GoRouterRefreshStream(Stream<dynamic> stream) {
    notifyListeners();
    stream.asBroadcastStream().listen((_) => notifyListeners());
  }
}

class UnauthorizedScreen extends StatelessWidget {
  const UnauthorizedScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.lock, size: 64, color: Colors.grey),
            const SizedBox(height: 16),
            const Text(
              'Unauthorized Access',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            const Text('You do not have permission to access this page'),
          ],
        ),
      ),
    );
  }
}
